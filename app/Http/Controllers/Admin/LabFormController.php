<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\LabForm;
use App\Models\Admin\Sample\Sample;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LabFormController extends Controller
{
    /**
     * Listar formulários salvos
     */
    public function index(Request $request)
    {
        $query = LabForm::with('creator')
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('form_number', 'like', "%{$search}%")
                  ->orWhereHas('creator', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('generated_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('generated_at', '<=', $request->date_to);
        }

        $forms = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/LabForm/Index', [
            'forms' => $forms,
            'filters' => $request->only(['search', 'date_from', 'date_to'])
        ]);
    }

    /**
     * Salvar formulário
     */
    public function store(Request $request)
    {
        $request->validate([
            'samples' => 'required|array',
            'form_number' => 'nullable|string',
            'sample_positions' => 'required|array'
        ]);

        // Gerar número do formulário se não fornecido
        $formNumber = $request->form_number ?: LabForm::generateFormNumber();

        // Verificar se já existe
        if (LabForm::where('form_number', $formNumber)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Número do formulário já existe.'
            ], 422);
        }

        // Preparar dados das amostras com suas posições
        $samplePositions = [];
        $formData = [
            'samples' => [],
            'metadata' => [
                'total_samples' => count($request->samples),
                'generated_by' => Auth::user()->name,
                'generated_at' => now()->toISOString()
            ]
        ];

        foreach ($request->samples as $index => $sample) {
            $sampleData = Sample::with(['animal', 'owner', 'examType'])
                ->find($sample['id']);
            
            if ($sampleData) {
                // Armazenar posição no grid
                $position = $request->sample_positions[$index] ?? null;
                if ($position) {
                    $samplePositions[] = [
                        'sample_id' => $sampleData->id,
                        'position' => $position,
                        'row' => $position['row'] ?? null,
                        'col' => $position['col'] ?? null,
                        'urgency' => $sampleData->priority > 0
                    ];
                }

                // Dados completos da amostra
                $formData['samples'][] = [
                    'id' => $sampleData->id,
                    'label' => $sample['label'] ?? '',
                    'animal_name' => $sampleData->animal->name ?? '',
                    'owner_name' => $sampleData->owner->name ?? '',
                    'exam_type' => $sampleData->examType->name ?? '',
                    'priority' => $sampleData->priority,
                    'created_at' => $sampleData->created_at->toISOString(),
                    'position' => $position
                ];
            }
        }

        // Criar o formulário
        $labForm = LabForm::create([
            'form_number' => $formNumber,
            'sample_positions' => $samplePositions,
            'form_data' => $formData,
            'user_created' => Auth::id(),
            'generated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Formulário salvo com sucesso!',
            'form' => $labForm,
            'form_number' => $formNumber
        ]);
    }

    /**
     * Visualizar formulário salvo
     */
    public function show(LabForm $labForm)
    {
        $labForm->load('creator');

        // Reconstruir dados das amostras com suas posições originais
        $samples = new \SplFixedArray(96); // Array fixo de 96 posições (8x12)
        
        // Inicializar todas as posições como null
        for ($i = 0; $i < 96; $i++) {
            $samples[$i] = null;
        }

        // Buscar dados atuais das amostras
        $sampleIds = collect($labForm->sample_positions)->pluck('sample_id');
        $currentSamples = Sample::with(['animal', 'owner', 'examType', 'tests'])
            ->whereIn('id', $sampleIds)
            ->get()
            ->keyBy('id');

        // Posicionar amostras no array baseado nas posições salvas
        foreach ($labForm->sample_positions as $positionData) {
            $sampleId = $positionData['sample_id'];
            $position = $positionData['position'];
            $index = $position['index'] ?? null;

            if ($index !== null && $index >= 0 && $index < 96) {
                $currentSample = $currentSamples->get($sampleId);
                
                if ($currentSample) {
                    $samples[$index] = [
                        'id' => $currentSample->id,
                        'label' => $currentSample->id, // Usar ID como label
                        'animal' => $currentSample->animal,
                        'owner' => $currentSample->owner,
                        'examType' => $currentSample->examType,
                        'priority' => $currentSample->priority,
                        'created_at' => $currentSample->created_at,
                        'tests' => $currentSample->tests,
                        'urgency' => $currentSample->priority > 0,
                        'position' => $position
                    ];
                }
            }
        }

        // Converter para array normal, mantendo nulls para posições vazias
        $samplesArray = [];
        for ($i = 0; $i < 96; $i++) {
            $samplesArray[] = $samples[$i];
        }

        // Filtrar apenas amostras não-null para o componente
        $filteredSamples = array_filter($samplesArray, function($sample) {
            return $sample !== null;
        });

        return Inertia::render('Admin/Sample/FormView', [
            'samples' => array_values($filteredSamples), // Re-indexar array
            'formNumber' => $labForm->form_number,
            'generatedAt' => $labForm->generated_at->toISOString(),
            'isStoredForm' => true,
            'labForm' => $labForm
        ]);
    }

    /**
     * Excluir formulário
     */
    public function destroy(LabForm $labForm)
    {
        $labForm->delete();

        return response()->json([
            'success' => true,
            'message' => 'Formulário excluído com sucesso!'
        ]);
    }
}