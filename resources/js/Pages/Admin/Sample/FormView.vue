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
                        <!-- Header -->
                        <div class="form-header">
                            <div class="logo-section" style="width: 180px;">
                                <img src="/logo-form.png" alt="Laboratório Raça DNA Animal" class="logo-image" />
                            </div>

                            <div class="form-titles">
                                <h1 class="form-title">FORMULÁRIO DE REGISTRO</h1>
                                <h2 class="form-subtitle">PLANILHA DE EXAMES</h2>
                                <div class="form-meta">
                                    N° DA CORRIDA: {{ formNumber }} &nbsp;&nbsp;&nbsp; DATA: {{ formatDate(generatedAt)
                                    }}
                                </div>
                            </div>

                            <div class="quality-box">
                                <strong>GARANTIA DA QUALIDADE</strong><br />
                                Código: FR 026 &nbsp; Página 1 de 2<br />
                                Revisão: 13/09/2022 Versão: 2.4
                            </div>
                        </div>

                        <!-- Sample Grid Table -->
                        <table class="sample-table">
                            <thead>
                                <tr>
                                    <th style="width: 30px;"></th>
                                    <th v-for="col in 12" :key="col">{{ col }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(rowLetter, rowIndex) in ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']"
                                    :key="rowLetter">
                                    <th>{{ rowLetter }}</th>
                                    <td v-for="col in 12" :key="col" class="sample-cell">
                                        <span class="sample-label">{{ getSampleLabel(rowIndex, col - 1) }}</span>
                                        <span class="sample-id">{{ getSampleForPosition(rowIndex, col - 1) }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Bottom Section -->
                        <table class="section-table">
                            <tbody>
                                <tr>
                                    <td style="width: 33%;">
                                        <div class="section-header">TRIAGEM</div>
                                        <table class="info-table">
                                            <tbody>
                                                <tr>
                                                    <td>Data:</td>
                                                    <td><span class="underline-field">&nbsp;</span>/<span
                                                            class="underline-field">&nbsp;</span>/<span
                                                            class="underline-field">&nbsp;</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Horário:</td>
                                                    <td><span class="underline-field">&nbsp;</span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Observações:</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="observation-box"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="width: 33%;">
                                        <div class="section-header">EXTRAÇÃO PELO</div>
                                        <table class="info-table">
                                            <tbody>
                                                <tr>
                                                    <td>Data:</td>
                                                    <td><span class="underline-field">&nbsp;</span>/<span
                                                            class="underline-field">&nbsp;</span>/<span
                                                            class="underline-field">&nbsp;</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Horário:</td>
                                                    <td><span class="underline-field">&nbsp;</span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><strong>Preparo do Tampão de Extração</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Data de preparo:</td>
                                                    <td><span class="underline-field">&nbsp;</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Validade:</td>
                                                    <td><span class="underline-field">&nbsp;</span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><strong>Reagentes utilizados no preparo:</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tampão 10X:</td>
                                                    <td>Lote: <span class="field-line">&nbsp;</span> validade: <span
                                                            class="underline-field">&nbsp;</span>/<span
                                                            class="underline-field">&nbsp;</span>/<span
                                                            class="underline-field">&nbsp;</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Tween 20X:</td>
                                                    <td>Lote: <span class="field-line">&nbsp;</span> validade: <span
                                                            class="underline-field">&nbsp;</span>/<span
                                                            class="underline-field">&nbsp;</span>/<span
                                                            class="underline-field">&nbsp;</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Água Ultrapura:</td>
                                                    <td>Lote: <span class="field-line">&nbsp;</span> validade: <span
                                                            class="underline-field">&nbsp;</span>/<span
                                                            class="underline-field">&nbsp;</span>/<span
                                                            class="underline-field">&nbsp;</span></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="width: 33%;">
                                        <div class="section-header">EXTRAÇÃO PELO</div>
                                        <table class="info-table">
                                            <tbody>
                                                <tr>
                                                    <td colspan="2"><strong>Proteinase K 10 mg/mL:</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Lote:</td>
                                                    <td><span class="underline-field">&nbsp;</span> validade: <span
                                                            class="underline-field">&nbsp;</span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><strong>Equipamentos Utilizados:</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Termobloco:</td>
                                                    <td>Centrífuga: <span class="underline-field">&nbsp;</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Micropipetas:</td>
                                                    <td><span class="underline-field">&nbsp;</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="width: 33%;">
                                        <div class="section-header">EXTRAÇÃO: &nbsp;&nbsp;&nbsp; SANGUE( ) SÊMEN( )
                                        </div>
                                        <table class="info-table">
                                            <tbody>
                                                <tr>
                                                    <td>Data:</td>
                                                    <td><span class="underline-field">&nbsp;</span>/<span
                                                            class="underline-field">&nbsp;</span>/<span
                                                            class="underline-field">&nbsp;</span></td>
                                                    <td>Horário:</td>
                                                    <td><span class="underline-field">&nbsp;</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Tampão PBS</td>
                                                    <td>Lote: <span class="field-line">&nbsp;</span></td>
                                                    <td>Validade:</td>
                                                    <td><span class="underline-field">&nbsp;</span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Tampão de Extração</td>
                                                    <td>Lote:</td>
                                                    <td>Validade:</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Tampão Lise Celular (TLC)</td>
                                                    <td>Lote</td>
                                                    <td>Validade:</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Tampão Lise Nuclear (TLN)</td>
                                                    <td>Lote</td>
                                                    <td>Validade:</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><strong>Proteína K 10 mg/ml</strong></td>
                                                    <td>Lote</td>
                                                    <td>Validade:</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"><strong>Mercaptoetanol ( ) ou Ditiotreitol 40 mM (
                                                            )</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Lote</td>
                                                    <td colspan="3">Validade</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><strong>NaCl 5M</strong></td>
                                                    <td>Lote</td>
                                                    <td>Validade:</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Isopropanol</strong></td>
                                                    <td></td>
                                                    <td>Lote</td>
                                                    <td>Validade:</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><strong>Etanol 70%</strong></td>
                                                    <td>Lote:</td>
                                                    <td>Validade:</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><strong>Água Ultrapura</strong></td>
                                                    <td>Lote:</td>
                                                    <td>Validade:</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"><strong>Equipamentos Utilizados:</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Termobloco:</td>
                                                    <td colspan="3">Centrífuga: <span
                                                            class="underline-field">&nbsp;</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Micropipetas:</td>
                                                    <td colspan="3"><span class="underline-field">&nbsp;</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        Resp.: <span
                                            className="underline-field">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        Resp.: <span
                                            className="underline-field">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        Resp.: <span
                                            className="underline-field">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    samples: Array,
    formNumber: String,
    generatedAt: String
})

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
    // Labels específicos para algumas posições especiais
    const specialLabels = {
        '2-10': '05/05/2020', // C11 (C13 na imagem)
        '3-10': '05/05/2020', // D11 (C14 na imagem)
        '4-4': '07',          // E5
        '4-5': 'BRANCO'       // E6
    };

    const key = `${row}-${col}`;
    return specialLabels[key] || 'URGENTE 14/05/2020';
};

const getSampleForPosition = (row, col) => {
    // Números específicos da imagem para cada posição da grade
    const sampleNumbers = [
        // Linha A
        ['1009055', '1009058', '1009061', '1009064', '1009067', '1009070', '1009073', '1009076', '1009079', '1009082', '1009085', '1009088'],
        // Linha B
        ['1009057', '1009060', '1009063', '1009066', '1009069', '1009072', '1009075', '1009078', '1009081', '1009084', '1009087', '1009090'],
        // Linha C
        ['1009059', '1009062', '1009065', '1009068', '1009071', '1009074', '1009077', '1009080', '1009083', '1009086', 'C11', '1009092'],
        // Linha D
        ['1009078', '1009091', '1009169', '1009192', '1009207', '1009247', '1009391', '1009109', '1009118', '1009127', '', '1009276'],
        // Linha E
        ['1009079', '1009092', '1009174', '1009194', 'E5', '1009253', '1009102', '1009110', '1009119', '1009128', '1009134', '1009277'],
        // Linha F
        ['1009080', '1009375', '1009176', '1009196', '1009225', '1009460', '1009103', '1009111', '1009121', '1009129', '1009140', '1009279'],
        // Linha G
        ['1009081', '1009156', '1009178', '1009198', '1009227', '1009271', '1009104', '1009112', '1009122', '1009130', '1009142', '1009280'],
        // Linha H
        ['1009085', '1009157', '1009179', '1009201', '1009231', '1009273', '1009105', '1009113', '1009123', '1009131', '1009143', '1009210']
    ];

    if (row >= 0 && row < sampleNumbers.length && col >= 0 && col < sampleNumbers[row].length) {
        return sampleNumbers[row][col];
    }
    return '';
};

const getSampleDetails = (row, col) => {
    // Calcula a posição linear na grade
    const position = row * 12 + col;

    // Retorna detalhes da amostra se existir
    if (props.samples && props.samples[position]) {
        const sample = props.samples[position];
        return {
            id: sample.id || getSampleForPosition(row, col),
            owner: sample.owner?.name || '',
            animal: sample.child?.name || '',
            date: sample.collection_date ? formatDateShort(sample.collection_date) : '',
            observations: sample.observations || '',
            created_at: sample.created_at,
            species: sample.child?.species || 'bovino'
        };
    }

    return null;
};

const goBackToSamples = () => {
    router.visit(route('admin.samples.add-to-form'))
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
                    
                    .sample-label {
                        font-size: 6pt;
                        color: #666;
                        display: block;
                        margin-bottom: 1mm;
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

.quality-box {
    text-align: right;
    font-size: 7pt;
    line-height: 1.3;
    min-width: 50mm;
}

.quality-box strong {
    font-size: 8pt;
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

.sample-cell {
    font-size: 7pt;
    position: relative;
    height: 12mm;
}

.sample-label {
    font-size: 6pt;
    color: #666;
    display: block;
    margin-bottom: 1mm;
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
</style>