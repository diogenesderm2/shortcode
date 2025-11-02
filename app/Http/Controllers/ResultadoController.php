<?php

namespace App\Http\Controllers;

use App\Models\Admin\Animal\Animal;
use App\Models\Admin\Sample\Sample;
use App\Models\Admin\Owner\Owner;
use App\Models\GeneticMarker;
use App\Models\GeneticResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResultadoController extends Controller
{
    /**
     * Upload e processamento de arquivo de resultado de DNA
     */
    public function uploadResultado(Request $request)
    {
        try {
            // Validação do arquivo
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:tsv,txt|max:10240' // Max 10MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo inválido. Apenas arquivos .tsv são permitidos.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            
            // Verificar se o arquivo tem extensão .tsv
            if (!in_array($file->getClientOriginalExtension(), ['tsv', 'txt'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas arquivos .tsv são permitidos.'
                ], 422);
            }

            // Processar o arquivo
            $result = $this->processarArquivoTSV($file);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Erro no upload de resultado DNA: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Processar arquivo TSV
     */
    private function processarArquivoTSV($file)
    {
        // Aumentar limite de tempo de execução para arquivos grandes
        set_time_limit(300); // 5 minutos
        ini_set('memory_limit', '512M'); // Aumentar limite de memória

        DB::beginTransaction();
        
        try {
            $handle = fopen($file->getPathname(), 'r');
            
            if (!$handle) {
                throw new \Exception('Não foi possível abrir o arquivo.');
            }

            // Ler cabeçalho (primeira linha)
            $header = fgetcsv($handle, 0, "\t");
            
            if (!$header || count($header) < 6) {
                throw new \Exception('Formato de arquivo inválido. Cabeçalho não encontrado.');
            }

            // Extrair IDs das amostras (colunas 6 em diante)
            $sampleIds = array_slice($header, 5);
            
            // Buscar todas as amostras existentes pelos IDs
            $samples = Sample::whereIn('id', $sampleIds)->get()->keyBy('id');
            
            if ($samples->isEmpty()) {
                throw new \Exception('Nenhuma amostra encontrada com os IDs fornecidos: ' . implode(', ', $sampleIds));
            }
            
            $processedMarkers = 0;
            $processedSamples = 0;
            $processedResults = 0;

            // Processar cada linha (marcador genético)
            while (($row = fgetcsv($handle, 0, "\t")) !== false) {
                if (count($row) < 6) continue;

                // Dados do marcador
                $markerName = $row[0]; // TARGET
                $chromosome = $row[1]; // CHROM
                $position = $row[2];   // POS
                $refAllele = $row[3];  // REF
                $altAllele = $row[4];  // ALT

                // Criar ou obter marcador genético com informações adicionais
                $marker = GeneticMarker::firstOrCreate(
                    ['name' => $markerName],
                    [
                        'chromosome' => $chromosome,
                        'position' => $position,
                        'ref_allele' => $refAllele,
                        'alt_allele' => $altAllele
                    ]
                );

                if ($marker->wasRecentlyCreated) {
                    $processedMarkers++;
                }

                // Processar resultados para cada amostra
                $genotypeData = array_slice($row, 5);
                
                foreach ($sampleIds as $index => $sampleId) {
                    if (!isset($genotypeData[$index])) continue;
                    
                    $genotype = trim($genotypeData[$index]);
                    
                    // Pular valores vazios ou ".."
                    if (empty($genotype) || $genotype === '..' || $genotype === '.') {
                        continue;
                    }

                    // Verificar se a amostra existe
                    if (!isset($samples[$sampleId])) {
                        continue; // Pular se a amostra não foi encontrada
                    }

                    $sample = $samples[$sampleId];

                    // Extrair alelos do genótipo
                    $alleles = $this->extrairAlelos($genotype);

                    // Criar resultado genético
                    $geneticResult = GeneticResult::updateOrCreate(
                        [
                            'sample_id' => $sample->id,
                            'marker_id' => $marker->id
                        ],
                        [
                            'allele_1' => $alleles['allele_1'],
                            'allele_2' => $alleles['allele_2'],
                            'genotype' => $genotype
                        ]
                    );

                    if ($geneticResult->wasRecentlyCreated) {
                        $processedResults++;
                    }
                }
            }

            fclose($handle);
            DB::commit();

            return [
                'success' => true,
                'message' => 'Arquivo processado com sucesso!',
                'data' => [
                    'marcadores_processados' => $processedMarkers,
                    'amostras_encontradas' => $samples->count(),
                    'resultados_processados' => $processedResults
                ]
            ];

        } catch (\Exception $e) {
            DB::rollback();
            if (isset($handle)) {
                fclose($handle);
            }
            throw $e;
        }
    }

    /**
     * Extrair alelos individuais do genótipo
     */
    private function extrairAlelos($genotype)
    {
        $genotype = trim($genotype);
        
        if (strlen($genotype) === 2) {
            return [
                'allele_1' => $genotype[0],
                'allele_2' => $genotype[1]
            ];
        }
        
        // Para genótipos mais complexos, tentar dividir por caracteres comuns
        if (strpos($genotype, '/') !== false) {
            $parts = explode('/', $genotype);
            return [
                'allele_1' => trim($parts[0]),
                'allele_2' => isset($parts[1]) ? trim($parts[1]) : trim($parts[0])
            ];
        }
        
        // Caso padrão: assumir que são dois caracteres
        return [
            'allele_1' => $genotype,
            'allele_2' => $genotype
        ];
    }
}