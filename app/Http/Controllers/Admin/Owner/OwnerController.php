<?php

namespace App\Http\Controllers\Admin\Owner;

use App\Http\Controllers\Controller;
use App\Models\Admin\Owner\Owner;
use App\Models\Admin\Owner\OwnerPhone;
use App\Models\Admin\Owner\OwnerEmail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Admin\Owner\OwnerRequest;
use App\Http\Resources\Admin\Owner\OwnerResource;
use App\Models\User;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Owner::with(['phones', 'emails']);
        
        // Aplicar filtro de busca se fornecido
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('rg', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('cnpj', 'like', "%{$search}%");
            });
        }
        
        $owners = $query->orderByDesc('id')->paginate(5)->withQueryString();
        
        return Inertia::render("Admin/Owner/Index", [
            'owners' => OwnerResource::collection($owners)->response()->getData(true),
            'filters' => [
                'search' => $request->get('search', ''),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OwnerRequest $request)
    {
        $data = $request->validated();
        
        // Extrair telefones e emails dos dados
        $phones = $data['phones'] ?? [];
        $emails = $data['emails'] ?? [];
        unset($data['phones'], $data['emails']);
        
        // Mapear property_name para property se existir
        if (isset($data['property_name'])) {
            $data['property'] = $data['property_name'];
            unset($data['property_name']);
        }

        // Mapear campos de valores do frontend para backend
        $fieldMapping = [
            'valor_identificacao_genetica' => 'valor_identificacao_genetica_bovino',
            'valor_beta_caseina' => 'valor_beta_lactoglobulina_bovino',
            'valor_kappa_caseina' => 'valor_kappa_caseina_bovino',
            'valor_identificacao_genetica_bovinos' => 'valor_identificacao_genetica_bovino',
            'valor_identificacao_genetica_caprinos' => 'valor_identificacao_genetica_caprino',
            'valor_identificacao_genetica_ovinos' => 'valor_identificacao_genetica_equino',
            'valor_hvp' => 'valor_hvip_equino',
            'valor_five_panel' => 'valor_five_panel_obed_hibrida_hty_equino'
        ];

        foreach ($fieldMapping as $frontendField => $backendField) {
            if (isset($data[$frontendField])) {
                $data[$backendField] = $data[$frontendField];
                unset($data[$frontendField]);
            }
        }

        // Mapear campos de valores do frontend para backend
        $fieldMapping = [
            'valor_identificacao_genetica' => 'valor_identificacao_genetica_bovino',
            'valor_beta_caseina' => 'valor_beta_lactoglobulina_bovino',
            'valor_kappa_caseina' => 'valor_kappa_caseina_bovino',
            'valor_identificacao_genetica_bovinos' => 'valor_identificacao_genetica_bovino',
            'valor_identificacao_genetica_caprinos' => 'valor_identificacao_genetica_caprino',
            'valor_identificacao_genetica_ovinos' => 'valor_identificacao_genetica_equino',
            'valor_hvp' => 'valor_hvip_equino',
            'valor_five_panel' => 'valor_five_panel_obed_hibrida_hty_equino'
        ];

        foreach ($fieldMapping as $frontendField => $backendField) {
            if (isset($data[$frontendField])) {
                $data[$backendField] = $data[$frontendField];
                unset($data[$frontendField]);
            }
        }

        // Remover formatação de CPF e CNPJ
        if (isset($data['cpf'])) {
            $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);
        }
        if (isset($data['cnpj'])) {
            $data['cnpj'] = preg_replace('/[^0-9]/', '', $data['cnpj']);
        }

        // Remover formatação de CPF e CNPJ
        if (isset($data['cpf'])) {
            $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);
        }
        if (isset($data['cnpj'])) {
            $data['cnpj'] = preg_replace('/[^0-9]/', '', $data['cnpj']);
        }

        // Converter valores monetários de string para decimal
        $valueFields = [
            'valor_identificacao_genetica_bovino',
            'valor_dna_caseira_bovino',
            'valor_kappa_caseina_bovino',
            'valor_beta_lactoglobulina_bovino',
            'valor_identificacao_genetica_equino',
            'valor_identificacao_genetica_caprino',
            'valor_hvip_equino',
            'valor_five_panel_obed_hibrida_hty_equino'
        ];

        foreach ($valueFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                // Remove formatação monetária (R$, pontos, vírgulas) e converte para decimal
                $data[$field] = (float) str_replace(['R$', '.', ','], ['', '', '.'], $data[$field]);
            }
        }

        // Criar o proprietário
        $owner = Owner::create($data);

        // Criar telefones associados
        foreach ($phones as $phone) {
            if (!empty($phone['number'])) {
                OwnerPhone::create([
                    'owner_id' => $owner->id,
                    'number' => $phone['number'],
                    'type' => $phone['type'] ?? 'principal'
                ]);
            }
        }

        // Criar emails associados
        foreach ($emails as $email) {
            if (!empty($email['address'])) {
                OwnerEmail::create([
                    'owner_id' => $owner->id,
                    'address' => $email['address'],
                    'type' => $email['type'] ?? 'principal'
                ]);
            }
        }

        return redirect()->route('admin.owners.index')->with('success', 'Proprietário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $owner = Owner::with(['phones', 'emails'])->findOrFail($id);
        return Inertia::render("Admin/Owner/Show", [
            'owner' => new OwnerResource($owner),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OwnerRequest $request, string $id)
    {
        $owner = Owner::findOrFail($id);
        $data = $request->validated();
        
        // Extrair telefones e emails dos dados
        $phones = $data['phones'] ?? [];
        $emails = $data['emails'] ?? [];
        unset($data['phones'], $data['emails']);
        
        // Mapear property_name para property se existir
        if (isset($data['property_name'])) {
            $data['property'] = $data['property_name'];
            unset($data['property_name']);
        }

        // Remover formatação de CPF e CNPJ
        if (isset($data['cpf'])) {
            $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);
        }
        if (isset($data['cnpj'])) {
            $data['cnpj'] = preg_replace('/[^0-9]/', '', $data['cnpj']);
        }

        // Converter valores monetários de string para decimal
        $valueFields = [
            'valor_identificacao_genetica_bovino',
            'valor_dna_caseira_bovino',
            'valor_kappa_caseina_bovino',
            'valor_beta_lactoglobulina_bovino',
            'valor_identificacao_genetica_equino',
            'valor_identificacao_genetica_caprino',
            'valor_hvip_equino',
            'valor_five_panel_obed_hibrida_hty_equino'
        ];

        foreach ($valueFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                // Remove formatação monetária (R$, pontos, vírgulas) e converte para decimal
                $data[$field] = (float) str_replace(['R$', '.', ','], ['', '', '.'], $data[$field]);
            }
        }

        // Atualizar o proprietário
        $owner->update($data);

        // Atualizar telefones
        $owner->phones()->delete();
        foreach ($phones as $phone) {
            if (!empty($phone['number'])) {
                OwnerPhone::create([
                    'owner_id' => $owner->id,
                    'number' => $phone['number'],
                    'type' => $phone['type'] ?? 'principal'
                ]);
            }
        }

        // Atualizar emails
        $owner->emails()->delete();
        foreach ($emails as $email) {
            if (!empty($email['address'])) {
                OwnerEmail::create([
                    'owner_id' => $owner->id,
                    'address' => $email['address'],
                    'type' => $email['type'] ?? 'principal'
                ]);
            }
        }

        return redirect()->route('admin.owners.index')->with('success', 'Proprietário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $owner = Owner::findOrFail($id);
        
        // Deletar telefones e emails associados
        $owner->phones()->delete();
        $owner->emails()->delete();
        
        // Deletar o proprietário
        $owner->delete();

        return redirect()->route('admin.owners.index')->with('success', 'Proprietário deletado com sucesso!');
    }
}