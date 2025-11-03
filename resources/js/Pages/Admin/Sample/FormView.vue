<template>
    <AppLayout title="Formulário de Amostras">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Formulário de Amostras
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Action Buttons -->
                    <div class="p-6 border-b border-gray-200 no-print">
                        <div class="flex justify-between items-center">
                            <button @click="goBackToSamples"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                ← Voltar
                            </button>
                            <div class="space-x-2">
                                <button @click="saveForm"
                                    class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded"
                                    :disabled="isSaving">
                                    <span v-if="isSaving">💾 Salvando...</span>
                                    <span v-else>💾 Gerar Formulário</span>
                                </button>
                                <button @click="printForm"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    🖨️ Imprimir
                                </button>
                                <button @click="exportForm"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    📄 Exportar PDF
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Form Content -->
                    <div id="printable-form" class="lab-form">
                        <!-- Form Header -->
                        <div class="form-header">
                            <div class="logo-section">
                                <img src="/logo-form.png" alt="Logo" class="logo-image">
                            </div>
                            <div class="form-titles">
                                <h1 class="form-title">FORMULÁRIO DE REGISTRO</h1>
                                <h2 class="form-subtitle">PLANILHA DE EXAMES - SNP</h2>
                                <div class="form-meta">
                                    <span class="meta-item">Nº DA CORRIDA:</span>
                                    <span class="meta-value">{{ formNumber }}</span>
                                    <span class="meta-item">DATA:</span>
                                    <span class="meta-date">{{ formatDateShort(generatedAt) }}</span>
                                </div>
                            </div>
                            <div class="quality-box">
                                <div class="quality-title">GARANTIA DE QUALIDADE</div>
                                <div class="quality-line">Código: FR081 &nbsp;&nbsp;&nbsp;&nbsp; Página 1 de 2</div>
                                <div class="quality-line">Revisão: 07/02/2025 &nbsp;&nbsp; Versão: 1.1</div>
                            </div>
                        </div>

                        <!-- Sample Grid -->
                        <table class="sample-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th v-for="col in 12" :key="col">{{ col }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(rowLetter, rowIndex) in ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']" :key="rowLetter">
                                    <td class="row-header">{{ rowLetter }}</td>
                                    <td v-for="col in 12" :key="col" 
                                        class="sample-cell"
                                        :class="{ urgent: isSampleUrgent(rowIndex, col - 1) }">
                                        <template v-if="getSampleForPosition(rowIndex, col - 1)">
                                            <div class="sample-label" 
                                                 :class="{ urgent: isSampleUrgent(rowIndex, col - 1) }">
                                                {{ getSampleLabel(rowIndex, col - 1) }}
                                            </div>
                                            <div class="sample-id">{{ getSampleForPosition(rowIndex, col - 1) }}</div>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Bottom Sections -->
                        <div class="bottom-sections">
                            <!-- TRIAGEM Section -->
                            <div class="section-column">
                                <div class="section-title">TRIAGEM</div>
                                <div class="section-content">
                                    <div class="field-row">Data:</div>
                                    <div class="field-row">Horário:</div>
                                    <div class="field-row">Observações:</div>
                                </div>
                            </div>

                            <!-- EXTRAÇÃO PELO Section -->
                            <div class="section-column">
                                <div class="section-title">EXTRAÇÃO PELO</div>
                                <div class="section-content">
                                    <div class="field-row">Data:</div>
                                    <div class="field-row">Horário:</div>
                                    <div class="field-row"><span class="field-label">Preparo do Tampão de Extração</span></div>
                                    <div class="field-row">Data de preparo:</div>
                                    <div class="field-row">Validade:</div>
                                    <div class="field-row"><span class="field-label">Reagentes usados no preparo:</span></div>
                                    <div class="field-row">Tampão 10X &nbsp;&nbsp; Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Val.:</div>
                                    <div class="field-row">Tween 20 &nbsp;&nbsp;&nbsp;&nbsp; Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Val.:</div>
                                    <div class="field-row">Etanol 100% &nbsp; Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Val.:</div>
                                    <div class="field-row">Etanol 70% &nbsp;&nbsp; Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Val.:</div>
                                    <div class="field-row">Água Ultrap. &nbsp; Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Val.:</div>
                                    <div class="field-row"><span class="field-label">Proteinase K 10 mg/mL</span></div>
                                    <div class="field-row">Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Validade:</div>
                                    <div class="field-row"><span class="field-label">Equipamentos utilizados</span></div>
                                    <div class="field-row">Termobloco:</div>
                                    <div class="field-row">Centrífuga:</div>
                                    <div class="field-row">Vórtex:</div>
                                    <div class="field-row">Micropipetas:</div>
                                    <div class="field-row">Resp.:</div>
                                </div>
                            </div>

                            <!-- EXTRAÇÃO SANGUE Section -->
                            <div class="section-column">
                                <div class="section-title">EXTRAÇÃO: SANGUE (✓) SÊMEN ( )</div>
                                <div class="section-content">
                                    <div class="field-row">Data:</div>
                                    <div class="field-row">Horário:</div>
                                    <div class="field-row"><span class="field-label">Tampão PBS</span></div>
                                    <div class="field-row">Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Val.:</div>
                                    <div class="field-row"><span class="field-label">Tampão de Extração</span></div>
                                    <div class="field-row">Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Val.:</div>
                                    <div class="field-row">Tampão de Lise Celular (TLC) &nbsp;&nbsp; Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Val.:</div>
                                    <div class="field-row">Tampão de Lise Nuclear (TLN) &nbsp; Lote:</div>
                                    <div class="field-row">NaCl 5M &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Val.:</div>
                                    <div class="field-row">Etanol 100% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Val.:</div>
                                    <div class="field-row">Etanol 70% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Val.:</div>
                                    <div class="field-row">Água Ultrapura &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Val.:</div>
                                    <div class="field-row"><span class="field-label">Proteinase K 10 mg/mL</span></div>
                                    <div class="field-row">Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Validade:</div>
                                    <div class="field-row"><span class="field-label">Equipamentos utilizados</span></div>
                                    <div class="field-row">Termobloco:</div>
                                    <div class="field-row">Centrífuga:</div>
                                    <div class="field-row">Vórtex:</div>
                                    <div class="field-row">Micropipetas:</div>
                                    <div class="field-row">Resp.:</div>
                                </div>
                            </div>

                            <!-- QUANTIFICAÇÃO DNA Section -->
                            <div class="section-column">
                                <div class="section-title">QUANTIFICAÇÃO DNA</div>
                                <div class="section-content">
                                    <div class="field-row">Data:</div>
                                    <div class="field-row">Horário:</div>
                                    <div class="field-row"><span class="field-label">Kit dsDNA HS Assay</span></div>
                                    <div class="field-row">Lote: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Val.:</div>
                                    <div class="field-row"><span class="field-label">Equipamentos utilizados</span></div>
                                    <div class="field-row">Fluorímetro:</div>
                                    <div class="field-row">Centrífuga:</div>
                                    <div class="field-row">Vórtex:</div>
                                    <div class="field-row">Micropipetas:</div>
                                    <div class="field-row">Observações:</div>
                                    <div class="field-row" style="margin-top: 2rem;">Resp.:</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

// Função global route (disponível globalmente no Laravel)
const route = window.route

const props = defineProps({
    samples: Array,
    formNumber: String,
    generatedAt: String,
    isStoredForm: {
        type: Boolean,
        default: false
    },
    labForm: Object
})

// Debug: verificar dados recebidos
console.log('FormView - Props recebidas:', {
    samples: props.samples,
    formNumber: props.formNumber,
    generatedAt: props.generatedAt,
    isStoredForm: props.isStoredForm
})

// Estado para salvar formulário
const isSaving = ref(false)

// Computed properties
const samplesWithObservations = computed(() => {
    return props.samples.filter(sample => sample.observations && sample.observations.trim() !== '')
})

const formNumber = computed(() => {
    return props.formNumber || '33783';
});

const generatedAt = computed(() => {
    return props.generatedAt || new Date().toISOString();
});

const getSampleLabel = (row, col) => {
    // Calcula a posição linear na grade
    const position = row * 12 + col;
    
    // Retorna dados da amostra real se existir
    if (props.samples && props.samples[position]) {
        const sample = props.samples[position];
        
        // Verifica se é urgente baseado no campo priority
        const isUrgent = sample.priority > 0;
        
        // Formatar a data de criação da amostra
        const createdDate = sample.created_at ? formatDateShort(sample.created_at) : '';
        
        // Retorna label com urgência e data
        if (isUrgent && createdDate) {
            return `URGENTE ${createdDate}`;
        } else if (createdDate) {
            return createdDate;
        }
    }
    
    // Retorna vazio se não há amostra nesta posição
    return '';
};

const getSampleForPosition = (row, col) => {
    // Calcula a posição linear na grade
    const position = row * 12 + col;
    
    // Retorna ID da amostra real se existir
    if (props.samples && props.samples[position]) {
        return props.samples[position].id;
    }
    
    // Retorna null se não há amostra nesta posição
    return null;
};

const isSampleUrgent = (row, col) => {
    const position = row * 12 + col;
    if (props.samples && props.samples[position]) {
        return props.samples[position].priority > 0;
    }
    return false;
};

const getSampleDetails = (row, col) => {
    // Calcula a posição linear na grade
    const position = row * 12 + col;

    // Retorna detalhes da amostra se existir
    if (props.samples && props.samples[position]) {
        const sample = props.samples[position];
        
        // Obter dados dos pais através dos testes
        const test = sample.tests && sample.tests.length > 0 ? sample.tests[0] : null;
        const father = test?.father;
        const mother = test?.mother;
        
        return {
            id: sample.id || '',
            childName: sample.animal?.name || 'N/A',
            childRg: sample.animal?.register || 'N/A',
            childSex: sample.animal?.genre === 1 ? 'M' : sample.animal?.genre === 2 ? 'F' : 'N/A',
            childBirth: sample.animal?.birth_date ? formatDateShort(sample.animal.birth_date) : 'N/A',
            fatherName: father?.name || 'N/A',
            fatherRg: father?.register || 'N/A',
            motherName: mother?.name || 'N/A',
            motherRg: mother?.register || 'N/A',
            ownerName: sample.owner?.name || 'N/A',
            collectionDate: sample.collected_at ? formatDateShort(sample.collected_at) : 'N/A',
            owner: sample.owner?.name || '',
            animal: sample.animal?.name || '',
            date: sample.collected_at ? formatDateShort(sample.collected_at) : '',
            observations: sample.observations || '',
            created_at: sample.created_at,
            species: sample.animal?.species || 'bovino',
            isUrgent: sample.priority > 0,
            insertionDate: sample.created_at ? formatDateShort(sample.created_at) : 'N/A',
            urgencyLabel: sample.priority > 0 ? 'URGENTE' : 'NORMAL'
        };
    }

    return null;
};

const goBackToSamples = () => {
    if (props.isStoredForm) {
        router.visit(route('admin.lab-forms.index'))
    } else {
        router.visit(route('admin.samples.add-to-form'))
    }
}

// Função para salvar o formulário no banco de dados
const saveForm = async () => {
    if (props.isStoredForm) {
        alert('Este formulário já está salvo no banco de dados.')
        return
    }

    isSaving.value = true

    try {
        // Preparar dados das amostras com suas posições
        const samplePositions = []
        const samplesData = []

        props.samples.forEach((sample, index) => {
            // Calcular posição no grid (8 linhas x 12 colunas)
            const row = Math.floor(index / 12)
            const col = index % 12
            const rowLetter = String.fromCharCode(65 + row) // A, B, C, etc.

            samplePositions.push({
                sample_id: sample.id,
                position: {
                    row: rowLetter,
                    col: col + 1,
                    index: index
                }
            })

            samplesData.push({
                id: sample.id,
                label: sample.label || `${rowLetter}${col + 1}`,
                urgency: sample.urgency || false
            })
        })

        const response = await fetch(route('admin.lab-forms.store'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                samples: samplesData,
                form_number: props.formNumber,
                sample_positions: samplePositions
            })
        })

        const result = await response.json()

        if (result.success) {
            alert(`Formulário salvo com sucesso! Número: ${result.form_number}`)
            // Redirecionar para a lista de formulários
            router.visit(route('admin.lab-forms.index'))
        } else {
            alert(result.message || 'Erro ao salvar formulário.')
        }
    } catch (error) {
        console.error('Erro ao salvar formulário:', error)
        alert('Erro ao salvar formulário. Tente novamente.')
    } finally {
        isSaving.value = false
    }
}

// Methods
const printForm = () => {
    // Criar uma nova janela para impressão
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    
    // Obter o conteúdo do formulário
    const formContent = document.getElementById('printable-form');
    
    if (formContent && printWindow) {
        // HTML completo para a janela de impressão
        const printHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <title>Formulário de Registro - Planilha de Exames</title>
                <style>
                    @page {
                        size: A4;
                        margin: 10mm;
                    }
                    
                    body {
                        margin: 0;
                        padding: 0;
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 9pt;
                        color: #000;
                        background: white;
                    }
                    
                    .lab-form {
                        width: 100%;
                        background: white;
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 9pt;
                        color: #000;
                    }
                    
                    .form-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-start;
                        margin-bottom: 2mm;
                        border: 2px solid #000;
                        padding: 3mm;
                        page-break-inside: avoid;
                    }
                    
                    .logo-section {
                        display: flex;
                        align-items: center;
                        width: 120px;
                    }
                    
                    .logo-image {
                        height: 40px;
                        width: auto;
                        max-width: 120px;
                    }
                    
                    .form-titles {
                        text-align: center;
                        flex: 1;
                        padding: 0 5mm;
                    }
                    
                    .form-title {
                        font-size: 14pt;
                        font-weight: bold;
                        margin: 0 0 2mm 0;
                    }
                    
                    .form-subtitle {
                        font-size: 12pt;
                        font-weight: bold;
                        margin: 0 0 2mm 0;
                    }
                    
                    .form-meta {
                        font-size: 9pt;
                        margin: 0;
                    }
                    
                    .quality-box {
                        text-align: center;
                        font-size: 7pt;
                        border: 1px solid #000;
                        padding: 2mm;
                        width: 120px;
                    }
                    
                    .sample-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 3mm;
                        page-break-inside: avoid;
                    }
                    
                    .sample-table th,
                    .sample-table td {
                        border: 1px solid #000;
                        padding: 1mm;
                        text-align: center;
                        font-size: 6pt;
                        vertical-align: middle;
                        height: 12mm;
                    }
                    
                    .sample-table th {
                        background: #f0f0f0;
                        font-weight: bold;
                    }
                    
                    .sample-cell {
                        position: relative;
                        padding: 0.5mm;
                    }
                    
                    .sample-cell.urgent {
                        background-color: #ffebee;
                        border: 1px solid #f44336;
                    }
                    
                    .sample-label {
                        font-size: 6pt;
                        color: #666;
                        display: block;
                        margin-bottom: 1mm;
                    }
                    
                    .sample-label.urgent {
                        color: #d32f2f;
                        font-weight: bold;
                    }
                    
                    .sample-id {
                        font-weight: bold;
                        font-size: 8pt;
                    }
                    
                    .section-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 3mm;
                        page-break-inside: avoid;
                    }
                    
                    .section-table td {
                        border: 1px solid #000;
                        padding: 1mm;
                        font-size: 7pt;
                        vertical-align: top;
                    }
                    
                    .section-header {
                        font-weight: bold;
                        text-align: center;
                        background: #fff;
                        padding: 1mm;
                        font-size: 7pt;
                    }
                    
                    .info-table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    
                    .info-table td {
                        border: none;
                        padding: 0.5mm;
                        font-size: 6pt;
                        vertical-align: top;
                    }
                    
                    .underline-field {
                        border-bottom: 1px solid #000;
                        display: inline-block;
                        width: 15mm;
                    }
                    
                    .field-line {
                        border-bottom: 1px solid #000;
                        display: inline-block;
                        min-width: 12mm;
                    }
                    
                    .observation-box {
                        min-height: 15mm;
                        border: 1px solid #000;
                        padding: 1mm;
                    }
                </style>
            </head>
            <body>
                ${formContent.outerHTML}
            </body>
            </html>
        `;
        
        // Escrever o HTML na nova janela
        printWindow.document.write(printHTML);
        printWindow.document.close();
        
        // Aguardar o carregamento e imprimir
        printWindow.onload = () => {
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        };
    }
}

const goBackToForm = () => {
    router.visit(route('admin.samples.add-to-form'))
}

const exportForm = () => {
    // Criar um elemento temporário para capturar o conteúdo do formulário
    const printContent = document.getElementById('printable-form').cloneNode(true)

    // Criar uma nova janela para o PDF
    const printWindow = window.open('', '_blank')

    // Configurar o documento da nova janela
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Formulário ${props.formNumber} - Laboratório Raça</title>
            <meta charset="UTF-8">
            <style>
                @page {
                    size: A4;
                    margin: 10mm;
                }
                
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 8px;
                    line-height: 1.2;
                    background: white;
                }
                
                .container {
                    width: 100%;
                    max-width: 210mm;
                    margin: 0 auto;
                    border: 2px solid black;
                }
                
                .header {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    padding: 5px;
                    border-bottom: 2px solid black;
                    background: white;
                }
                
                .logo-section {
                    display: flex;
                    align-items: center;
                    width: 120px;
                }
                
                .logo-section .logo-image {
                    height: 40px;
                    width: auto;
                    max-width: 120px;
                }
                
                .logo {
                    width: 60px;
                    height: 60px;
                    background: #e0e0e0;
                    border: 1px solid #999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 10px;
                    font-weight: bold;
                    margin-right: 10px;
                }
                
                .title-section {
                    flex: 1;
                    text-align: center;
                    padding: 0 20px;
                }
                
                .main-title {
                    font-size: 14px;
                    font-weight: bold;
                    margin-bottom: 2px;
                }
                
                .subtitle {
                    font-size: 16px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                
                .run-info {
                    font-size: 10px;
                    font-weight: bold;
                }
                
                .quality-section {
                    width: 120px;
                    text-align: right;
                    font-size: 8px;
                    line-height: 1.1;
                }
                
                .quality-title {
                    font-weight: bold;
                    margin-bottom: 2px;
                }
                
                .main-grid {
                    width: 100%;
                    border-collapse: collapse;
                }
                
                .main-grid td, .main-grid th {
                    border: 1px solid black;
                    text-align: center;
                    vertical-align: middle;
                    padding: 2px;
                    font-size: 7px;
                    height: 20px;
                }
                
                .main-grid th {
                    background: #f0f0f0;
                    font-weight: bold;
                }
                
                .row-header {
                    background: #f0f0f0;
                    font-weight: bold;
                    width: 25px;
                }
                
                .col-header {
                    background: #f0f0f0;
                    font-weight: bold;
                    width: 25px;
                }
                
                .cell-content {
                    font-size: 6px;
                    line-height: 1;
                }
                
                .urgent-label {
                    color: red;
                    font-size: 5px;
                    display: block;
                }
                
                .code-number {
                    font-weight: bold;
                    font-size: 7px;
                }
                
                .bottom-section {
                    display: flex;
                    border-top: 2px solid black;
                }
                
                .left-section {
                    width: 35%;
                    border-right: 2px solid black;
                    padding: 5px;
                }
                
                .middle-section {
                    width: 35%;
                    border-right: 2px solid black;
                    padding: 5px;
                }
                
                .right-section {
                    width: 30%;
                    padding: 5px;
                }
                
                .section-title {
                    font-weight: bold;
                    text-align: center;
                    margin-bottom: 5px;
                    font-size: 9px;
                }
                
                .form-row {
                    margin-bottom: 3px;
                    font-size: 7px;
                }
                
                .form-label {
                    font-weight: bold;
                    display: inline-block;
                    margin-right: 5px;
                }
                
                .form-field {
                    border-bottom: 1px solid black;
                    display: inline-block;
                    min-width: 50px;
                    height: 12px;
                }
                
                .observations-box {
                    border: 1px solid black;
                    height: 60px;
                    width: 100%;
                    margin-top: 5px;
                }
                
                .equipment-section {
                    margin-top: 10px;
                }
                
                .equipment-row {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 2px;
                    font-size: 7px;
                }
                
                .resp-field {
                    border: 1px solid black;
                    height: 15px;
                    margin-top: 5px;
                }
            </style>
        </head>
        <body>
            ${printContent.outerHTML}
        </body>
        </html>
    `)

    printWindow.document.close()

    // Aguardar o carregamento e então imprimir/salvar como PDF
    setTimeout(() => {
        printWindow.print()
        // A janela será fechada automaticamente após a impressão/salvamento
        setTimeout(() => {
            printWindow.close()
        }, 1000)
    }, 500)
}

// Utility functions
const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('pt-BR')
}

const formatDateShort = (dateString) => {
    if (!dateString) return '01/11/2025'
    const date = new Date(dateString)
    const day = String(date.getDate()).padStart(2, '0')
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const year = date.getFullYear()
    return `${day}/${month}/${year}`
}

const formatSampleType = (type) => {
    const types = {
        'sangue': 'Sangue',
        'pelo': 'Pelo',
        'saliva': 'Saliva'
    }
    return types[type] || type
}

const formatStatus = (status) => {
    const statuses = {
        'pendente': 'Pendente',
        'processando': 'Em Processamento',
        'concluido': 'Concluído'
    }
    return statuses[status] || status
}

const getStatusClass = (status) => {
    const classes = {
        'pendente': 'bg-yellow-100 text-yellow-800',
        'processando': 'bg-blue-100 text-blue-800',
        'concluido': 'bg-green-100 text-green-800'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}
</script>

<style>
@media print {
    body {
        margin: 0;
        padding: 0;
    }

    .no-print {
        display: none !important;
    }

    /* Ocultar elementos da interface que não devem ser impressos */
    nav, header, .bg-white.overflow-hidden.shadow-xl.sm\\:rounded-lg > .p-6.border-b.border-gray-200.no-print,
    .max-w-7xl, .py-12 {
        display: none !important;
    }

    /* Mostrar apenas o conteúdo do formulário */
    .lab-form {
        width: 100% !important;
        min-height: auto !important;
        margin: 0 !important;
        padding: 10mm !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    /* Ajustar o layout para impressão */
    .form-header {
        page-break-inside: avoid;
    }

    .sample-table {
        page-break-inside: avoid;
        margin: 0 !important;
    }

    .section-table {
        page-break-inside: avoid;
    }
}

.lab-form {
    width: 210mm;
    min-height: 297mm;
    margin: 0 auto;
    background: white;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 9pt;
    color: #000;
    padding: 5mm 8mm;
    box-sizing: border-box;
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2mm;
    border: 2px solid #000;
    padding: 3mm;
}

.logo-section {
    display: flex;
    align-items: center;
    gap: 2mm;
}

.logo-image {
    height: 40px;
    width: auto;
    max-width: 120px;
}

.form-titles {
    text-align: center;
    flex: 1;
    padding: 0 5mm;
}

.form-title {
    font-weight: bold;
    font-size: 12pt;
    margin: 0;
    letter-spacing: 1px;
}

.form-subtitle {
    font-weight: bold;
    font-size: 14pt;
    margin: 2mm 0;
    letter-spacing: 2px;
}

.form-meta {
    font-size: 8pt;
    margin: 1mm 0;
}

.meta-item {
    font-weight: normal;
}

.meta-value, .meta-date {
    font-weight: normal;
    margin-left: 2mm;
    margin-right: 8mm;
}

.quality-box {
    text-align: right;
    font-size: 7pt;
    line-height: 1.3;
    min-width: 50mm;
    border: 1px solid #000;
    padding: 2mm;
}

.quality-title {
    font-weight: bold;
    font-size: 8pt;
    margin-bottom: 1mm;
}

.quality-line {
    margin-bottom: 0.5mm;
}

.sample-table {
    width: 100%;
    border-collapse: collapse;
    margin: 3mm 0;
}

.sample-table th,
.sample-table td {
    border: 1px solid #000;
    padding: 1mm 2mm;
    text-align: center;
    font-size: 8pt;
    font-weight: normal;
}

.sample-table th {
    background: #fff;
    font-weight: bold;
}

.row-header {
    background: #fff;
    font-weight: bold;
    width: 20px;
}

.sample-cell {
    font-size: 7pt;
    position: relative;
    height: 12mm;
    vertical-align: top;
    padding: 1mm;
}

.sample-cell.urgent {
    background-color: #fef2f2;
    border: 2px solid #ef4444;
}

.sample-label {
    font-size: 6pt;
    color: #666;
    display: block;
    margin-bottom: 1mm;
    line-height: 1.1;
}

.sample-label.urgent {
    color: #dc2626;
    font-weight: bold;
}

.sample-id {
    font-weight: bold;
    font-size: 8pt;
}

.section-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 3mm;
}

.section-table td {
    border: 1px solid #000;
    padding: 1mm;
    font-size: 7pt;
    vertical-align: top;
}

.section-header {
    font-weight: bold;
    text-align: center;
    background: #fff;
    padding: 1mm;
    font-size: 7pt;
}

.field-label {
    font-weight: bold;
    margin-bottom: 1mm;
}

.field-line {
    border-bottom: 1px solid #000;
    display: inline-block;
    min-width: 12mm;
}

.underline-field {
    border-bottom: 1px solid #000;
    display: inline-block;
    width: 15mm;
}

.observation-box {
    min-height: 15mm;
}

.info-table {
    width: 100%;
    font-size: 6.5pt;
    line-height: 1.2;
}

.info-table td {
    padding: 0.5mm 0.5mm;
}

.info-table strong {
    font-size: 7pt;
}

/* Print styles for bottom sections */
.bottom-sections {
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) !important;
    gap: 0 !important;
    margin-top: 1rem !important;
    border: 1px solid #000 !important;
}

.section-column {
    border-right: 1px solid #000 !important;
    padding: 0.3rem !important;
    break-inside: avoid !important;
    font-size: 6pt !important;
    line-height: 1.1 !important;
}

.section-column:last-child {
    border-right: none !important;
}

.section-title {
    font-weight: bold !important;
    font-size: 6pt !important;
    text-align: center !important;
    padding: 0.2rem !important;
    border-bottom: 1px solid #000 !important;
    margin-bottom: 0.3rem !important;
    background-color: #fff !important;
}

.section-content {
    font-size: 5.5pt !important;
    line-height: 1.2 !important;
}

.field-row {
    margin-bottom: 0.1rem !important;
    line-height: 1.1 !important;
}

.field-label {
    font-weight: bold !important;
}

/* Styles for urgent samples */
.sample-cell.urgent {
    background-color: #fef2f2;
    border: 2px solid #ef4444;
}

.sample-label.urgent {
    color: #dc2626;
    font-weight: bold;
}

/* Sample table styles */
.sample-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
    font-size: 8pt;
}

.sample-table th,
.sample-table td {
    border: 1px solid #000;
    padding: 0.5rem;
    text-align: center;
    vertical-align: middle;
}

.sample-table th {
    background: #fff;
    font-weight: bold;
    font-size: 9pt;
}

.row-header {
    background: #fff;
    font-weight: bold;
    width: 30px;
    font-size: 9pt;
}

.sample-cell {
    height: 40px;
    position: relative;
    padding: 0.2rem;
    font-size: 7pt;
    vertical-align: top;
}

.sample-label {
    font-size: 6pt;
    color: #666;
    display: block;
    margin-bottom: 0.2rem;
    line-height: 1.1;
}

.sample-id {
    font-weight: bold;
    font-size: 8pt;
    color: #000;
}

/* Bottom sections grid layout */
.bottom-sections {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
    margin-top: 2rem;
    border: 1px solid #000;
}

.section-column {
    border-right: 1px solid #000;
    padding: 0.5rem;
    font-size: 7pt;
    line-height: 1.2;
}

.section-column:last-child {
    border-right: none;
}

.section-title {
    font-weight: bold;
    font-size: 7pt;
    text-align: center;
    padding: 0.3rem;
    border-bottom: 1px solid #000;
    margin-bottom: 0.5rem;
    background-color: #fff;
}

.section-content {
    font-size: 6.5pt;
    line-height: 1.3;
}

.field-row {
    margin-bottom: 0.2rem;
    line-height: 1.2;
}

.field-label {
    font-weight: bold;
}
</style>