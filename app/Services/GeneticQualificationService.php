<?php

namespace App\Services;

use App\Models\Admin\Test;
use App\Models\Admin\GeneticQualification;
use App\Models\GeneticResult;
use App\Models\GeneticMarker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class GeneticQualificationService
{
    /**
     * Calculate qualification for a test
     */
    public function calculateQualification(Test $test): GeneticQualification
    {
        Log::info("Calculating qualification for test ID: {$test->id}");

        // Get or create qualification record
        $qualification = $test->qualification ?? new GeneticQualification([
            'test_id' => $test->id,
            'calculated_by' => Auth::id(),
            'calculated_at' => now(),
        ]);

        // Determine qualification type based on test type
        $qualification->type = $this->mapTestTypeToQualificationType($test->testType->name);

        // Calculate based on type
        switch ($qualification->type) {
            case GeneticQualification::TYPE_PERMANENT_ARCHIVE:
                $result = $this->calculatePermanentArchiveQualification($test);
                break;
            case GeneticQualification::TYPE_PARENTAGE:
                $result = $this->calculateParentageQualification($test);
                break;
            case GeneticQualification::TYPE_PATERNITY:
                $result = $this->calculatePaternityQualification($test);
                break;
            case GeneticQualification::TYPE_MATERNITY:
                $result = $this->calculateMaternityQualification($test);
                break;
            default:
                $result = [
                    'status' => GeneticQualification::STATUS_PENDING,
                    'confidence_score' => 0,
                    'details' => ['error' => 'Unknown test type'],
                ];
        }

        // Update qualification with results
        $qualification->fill($result);
        $qualification->save();

        Log::info("Qualification calculated", [
            'test_id' => $test->id,
            'status' => $qualification->status,
            'confidence_score' => $qualification->confidence_score,
        ]);

        return $qualification;
    }

    /**
     * Calculate qualification for permanent archive (basic genetic identification)
     */
    private function calculatePermanentArchiveQualification(Test $test): array
    {
        $sample = $test->sample;
        
        // Get genetic results for this sample
        $geneticResults = GeneticResult::where('sample_id', $sample->id)
            ->with('marker')
            ->get();

        if ($geneticResults->isEmpty()) {
            return [
                'status' => GeneticQualification::STATUS_PENDING,
                'confidence_score' => 0,
                'details' => [
                    'message' => 'Nenhum resultado genético encontrado',
                    'total_markers' => 0,
                    'valid_results' => 0,
                ],
                'notes' => 'Aguardando resultados genéticos para análise.',
            ];
        }

        $totalMarkers = $geneticResults->count();
        $validResults = $geneticResults->where('genotype', '!=', null)->count();
        $qualityScore = $totalMarkers > 0 ? ($validResults / $totalMarkers) * 100 : 0;

        // Determine status based on quality score
        $status = match(true) {
            $qualityScore >= 90 => GeneticQualification::STATUS_QUALIFIED,
            $qualityScore >= 70 => GeneticQualification::STATUS_INCONCLUSIVE,
            default => GeneticQualification::STATUS_NOT_QUALIFIED,
        };

        return [
            'status' => $status,
            'confidence_score' => round($qualityScore, 2),
            'details' => [
                'total_markers' => $totalMarkers,
                'valid_results' => $validResults,
                'quality_percentage' => round($qualityScore, 2),
                'analysis_type' => 'permanent_archive',
            ],
            'notes' => $this->generateNotesForPermanentArchive($qualityScore, $totalMarkers, $validResults),
        ];
    }

    /**
     * Calculate qualification for parentage (father and mother)
     */
    private function calculateParentageQualification(Test $test): array
    {
        if (!$test->father || !$test->mother) {
            return [
                'status' => GeneticQualification::STATUS_NOT_QUALIFIED,
                'confidence_score' => 0,
                'details' => [
                    'error' => 'Pai e/ou mãe não especificados',
                    'has_father' => (bool) $test->father,
                    'has_mother' => (bool) $test->mother,
                ],
                'notes' => 'Teste de paternidade requer informações de pai e mãe.',
            ];
        }

        // Get genetic results for child, father, and mother
        $childResults = $this->getGeneticResultsForAnimal($test->sample->animal_id);
        $fatherResults = $this->getGeneticResultsForAnimal($test->father->id);
        $motherResults = $this->getGeneticResultsForAnimal($test->mother->id);

        if ($childResults->isEmpty() || $fatherResults->isEmpty() || $motherResults->isEmpty()) {
            return [
                'status' => GeneticQualification::STATUS_PENDING,
                'confidence_score' => 0,
                'details' => [
                    'message' => 'Resultados genéticos incompletos',
                    'child_results' => $childResults->count(),
                    'father_results' => $fatherResults->count(),
                    'mother_results' => $motherResults->count(),
                ],
                'notes' => 'Aguardando resultados genéticos completos para análise de parentesco.',
            ];
        }

        // Perform parentage analysis
        $analysis = $this->performParentageAnalysis($childResults, $fatherResults, $motherResults);

        return [
            'status' => $analysis['status'],
            'confidence_score' => $analysis['confidence_score'],
            'details' => $analysis['details'],
            'notes' => $analysis['notes'],
        ];
    }

    /**
     * Calculate qualification for paternity only
     */
    private function calculatePaternityQualification(Test $test): array
    {
        if (!$test->father) {
            return [
                'status' => GeneticQualification::STATUS_NOT_QUALIFIED,
                'confidence_score' => 0,
                'details' => ['error' => 'Pai não especificado'],
                'notes' => 'Teste de paternidade requer informações do pai.',
            ];
        }

        $childResults = $this->getGeneticResultsForAnimal($test->sample->animal_id);
        $fatherResults = $this->getGeneticResultsForAnimal($test->father->id);

        if ($childResults->isEmpty() || $fatherResults->isEmpty()) {
            return [
                'status' => GeneticQualification::STATUS_PENDING,
                'confidence_score' => 0,
                'details' => [
                    'child_results' => $childResults->count(),
                    'father_results' => $fatherResults->count(),
                ],
                'notes' => 'Aguardando resultados genéticos para análise de paternidade.',
            ];
        }

        $analysis = $this->performPaternityAnalysis($childResults, $fatherResults);

        return [
            'status' => $analysis['status'],
            'confidence_score' => $analysis['confidence_score'],
            'details' => $analysis['details'],
            'notes' => $analysis['notes'],
        ];
    }

    /**
     * Calculate qualification for maternity only
     */
    private function calculateMaternityQualification(Test $test): array
    {
        if (!$test->mother) {
            return [
                'status' => GeneticQualification::STATUS_NOT_QUALIFIED,
                'confidence_score' => 0,
                'details' => ['error' => 'Mãe não especificada'],
                'notes' => 'Teste de maternidade requer informações da mãe.',
            ];
        }

        $childResults = $this->getGeneticResultsForAnimal($test->sample->animal_id);
        $motherResults = $this->getGeneticResultsForAnimal($test->mother->id);

        if ($childResults->isEmpty() || $motherResults->isEmpty()) {
            return [
                'status' => GeneticQualification::STATUS_PENDING,
                'confidence_score' => 0,
                'details' => [
                    'child_results' => $childResults->count(),
                    'mother_results' => $motherResults->count(),
                ],
                'notes' => 'Aguardando resultados genéticos para análise de maternidade.',
            ];
        }

        $analysis = $this->performMaternityAnalysis($childResults, $motherResults);

        return [
            'status' => $analysis['status'],
            'confidence_score' => $analysis['confidence_score'],
            'details' => $analysis['details'],
            'notes' => $analysis['notes'],
        ];
    }

    /**
     * Map test type name to qualification type
     */
    private function mapTestTypeToQualificationType(string $testTypeName): string
    {
        return match($testTypeName) {
            'Arquivo Permanente' => GeneticQualification::TYPE_PERMANENT_ARCHIVE,
            'Pai e Mãe' => GeneticQualification::TYPE_PARENTAGE,
            'Apenas Pai' => GeneticQualification::TYPE_PATERNITY,
            'Apenas Mãe' => GeneticQualification::TYPE_MATERNITY,
            default => GeneticQualification::TYPE_PERMANENT_ARCHIVE,
        };
    }

    /**
     * Get genetic results for an animal
     */
    private function getGeneticResultsForAnimal(int $animalId)
    {
        return GeneticResult::whereHas('sample', function ($query) use ($animalId) {
            $query->where('animal_id', $animalId);
        })->with(['marker', 'sample'])->get();
    }

    /**
     * Perform parentage analysis
     */
    private function performParentageAnalysis($childResults, $fatherResults, $motherResults): array
    {
        $compatibleMarkers = 0;
        $totalMarkers = 0;
        $incompatibleMarkers = [];

        foreach ($childResults as $childResult) {
            $markerId = $childResult->marker_id;
            $fatherResult = $fatherResults->where('marker_id', $markerId)->first();
            $motherResult = $motherResults->where('marker_id', $markerId)->first();

            if (!$fatherResult || !$motherResult) {
                continue;
            }

            $totalMarkers++;

            // Check if child's alleles are compatible with parents
            $childAlleles = [$childResult->allele_1, $childResult->allele_2];
            $fatherAlleles = [$fatherResult->allele_1, $fatherResult->allele_2];
            $motherAlleles = [$motherResult->allele_1, $motherResult->allele_2];

            $isCompatible = $this->checkParentageCompatibility($childAlleles, $fatherAlleles, $motherAlleles);

            if ($isCompatible) {
                $compatibleMarkers++;
            } else {
                $incompatibleMarkers[] = [
                    'marker' => $childResult->marker->name,
                    'child' => implode('/', $childAlleles),
                    'father' => implode('/', $fatherAlleles),
                    'mother' => implode('/', $motherAlleles),
                ];
            }
        }

        $compatibilityScore = $totalMarkers > 0 ? ($compatibleMarkers / $totalMarkers) * 100 : 0;

        $status = match(true) {
            $compatibilityScore >= 95 => GeneticQualification::STATUS_QUALIFIED,
            $compatibilityScore >= 80 => GeneticQualification::STATUS_INCONCLUSIVE,
            default => GeneticQualification::STATUS_NOT_QUALIFIED,
        };

        return [
            'status' => $status,
            'confidence_score' => round($compatibilityScore, 2),
            'details' => [
                'total_markers' => $totalMarkers,
                'compatible_markers' => $compatibleMarkers,
                'incompatible_markers' => count($incompatibleMarkers),
                'compatibility_score' => round($compatibilityScore, 2),
                'incompatible_details' => $incompatibleMarkers,
                'analysis_type' => 'parentage',
            ],
            'notes' => $this->generateNotesForParentage($compatibilityScore, $totalMarkers, $compatibleMarkers),
        ];
    }

    /**
     * Perform paternity analysis
     */
    private function performPaternityAnalysis($childResults, $fatherResults): array
    {
        $compatibleMarkers = 0;
        $totalMarkers = 0;

        foreach ($childResults as $childResult) {
            $fatherResult = $fatherResults->where('marker_id', $childResult->marker_id)->first();
            if (!$fatherResult) continue;

            $totalMarkers++;

            $childAlleles = [$childResult->allele_1, $childResult->allele_2];
            $fatherAlleles = [$fatherResult->allele_1, $fatherResult->allele_2];

            if ($this->checkPaternityCompatibility($childAlleles, $fatherAlleles)) {
                $compatibleMarkers++;
            }
        }

        $compatibilityScore = $totalMarkers > 0 ? ($compatibleMarkers / $totalMarkers) * 100 : 0;

        $status = match(true) {
            $compatibilityScore >= 90 => GeneticQualification::STATUS_QUALIFIED,
            $compatibilityScore >= 75 => GeneticQualification::STATUS_INCONCLUSIVE,
            default => GeneticQualification::STATUS_NOT_QUALIFIED,
        };

        return [
            'status' => $status,
            'confidence_score' => round($compatibilityScore, 2),
            'details' => [
                'total_markers' => $totalMarkers,
                'compatible_markers' => $compatibleMarkers,
                'compatibility_score' => round($compatibilityScore, 2),
                'analysis_type' => 'paternity',
            ],
            'notes' => $this->generateNotesForPaternity($compatibilityScore, $totalMarkers, $compatibleMarkers),
        ];
    }

    /**
     * Perform maternity analysis
     */
    private function performMaternityAnalysis($childResults, $motherResults): array
    {
        $compatibleMarkers = 0;
        $totalMarkers = 0;

        foreach ($childResults as $childResult) {
            $motherResult = $motherResults->where('marker_id', $childResult->marker_id)->first();
            if (!$motherResult) continue;

            $totalMarkers++;

            $childAlleles = [$childResult->allele_1, $childResult->allele_2];
            $motherAlleles = [$motherResult->allele_1, $motherResult->allele_2];

            if ($this->checkMaternityCompatibility($childAlleles, $motherAlleles)) {
                $compatibleMarkers++;
            }
        }

        $compatibilityScore = $totalMarkers > 0 ? ($compatibleMarkers / $totalMarkers) * 100 : 0;

        $status = match(true) {
            $compatibilityScore >= 90 => GeneticQualification::STATUS_QUALIFIED,
            $compatibilityScore >= 75 => GeneticQualification::STATUS_INCONCLUSIVE,
            default => GeneticQualification::STATUS_NOT_QUALIFIED,
        };

        return [
            'status' => $status,
            'confidence_score' => round($compatibilityScore, 2),
            'details' => [
                'total_markers' => $totalMarkers,
                'compatible_markers' => $compatibleMarkers,
                'compatibility_score' => round($compatibilityScore, 2),
                'analysis_type' => 'maternity',
            ],
            'notes' => $this->generateNotesForMaternity($compatibilityScore, $totalMarkers, $compatibleMarkers),
        ];
    }

    /**
     * Check parentage compatibility
     */
    private function checkParentageCompatibility(array $childAlleles, array $fatherAlleles, array $motherAlleles): bool
    {
        // Each child allele must come from either father or mother
        foreach ($childAlleles as $childAllele) {
            $fromFather = in_array($childAllele, $fatherAlleles);
            $fromMother = in_array($childAllele, $motherAlleles);
            
            if (!$fromFather && !$fromMother) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Check paternity compatibility
     */
    private function checkPaternityCompatibility(array $childAlleles, array $fatherAlleles): bool
    {
        // At least one child allele must come from father
        foreach ($childAlleles as $childAllele) {
            if (in_array($childAllele, $fatherAlleles)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check maternity compatibility
     */
    private function checkMaternityCompatibility(array $childAlleles, array $motherAlleles): bool
    {
        // At least one child allele must come from mother
        foreach ($childAlleles as $childAllele) {
            if (in_array($childAllele, $motherAlleles)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Generate notes for permanent archive
     */
    private function generateNotesForPermanentArchive(float $qualityScore, int $totalMarkers, int $validResults): string
    {
        if ($qualityScore >= 90) {
            return "Qualificação aprovada com {$qualityScore}% de qualidade. {$validResults} de {$totalMarkers} marcadores válidos.";
        } elseif ($qualityScore >= 70) {
            return "Qualificação inconclusiva com {$qualityScore}% de qualidade. Recomenda-se nova análise.";
        } else {
            return "Qualificação reprovada com {$qualityScore}% de qualidade. {$validResults} de {$totalMarkers} marcadores válidos.";
        }
    }

    /**
     * Generate notes for parentage
     */
    private function generateNotesForParentage(float $score, int $total, int $compatible): string
    {
        if ($score >= 95) {
            return "Parentesco confirmado com {$score}% de compatibilidade. {$compatible} de {$total} marcadores compatíveis.";
        } elseif ($score >= 80) {
            return "Parentesco inconclusivo com {$score}% de compatibilidade. Recomenda-se análise adicional.";
        } else {
            return "Parentesco não confirmado com {$score}% de compatibilidade. {$compatible} de {$total} marcadores compatíveis.";
        }
    }

    /**
     * Generate notes for paternity
     */
    private function generateNotesForPaternity(float $score, int $total, int $compatible): string
    {
        if ($score >= 90) {
            return "Paternidade confirmada com {$score}% de compatibilidade. {$compatible} de {$total} marcadores compatíveis.";
        } elseif ($score >= 75) {
            return "Paternidade inconclusiva com {$score}% de compatibilidade. Recomenda-se análise adicional.";
        } else {
            return "Paternidade não confirmada com {$score}% de compatibilidade. {$compatible} de {$total} marcadores compatíveis.";
        }
    }

    /**
     * Generate notes for maternity
     */
    private function generateNotesForMaternity(float $score, int $total, int $compatible): string
    {
        if ($score >= 90) {
            return "Maternidade confirmada com {$score}% de compatibilidade. {$compatible} de {$total} marcadores compatíveis.";
        } elseif ($score >= 75) {
            return "Maternidade inconclusiva com {$score}% de compatibilidade. Recomenda-se análise adicional.";
        } else {
            return "Maternidade não confirmada com {$score}% de compatibilidade. {$compatible} de {$total} marcadores compatíveis.";
        }
    }
}