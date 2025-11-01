<template>
    <AppLayout title="Formulário de Amostras">
        <template #header>
            <div class="flex items-center justify-between no-print">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Formulário Nº {{ formNumber }}
                </h2>
                <div class="flex space-x-3">
                    <button
                        @click="goBackToForm"
                        class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors"
                    >
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Voltar ao Formulário
                    </button>
                    <button
                        @click="exportForm"
                        class="px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors"
                    >
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exportar PDF
                    </button>
                    <button
                        @click="printForm"
                        class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                    >
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Imprimir
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12 no-print">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                    <div class="p-6 text-center">
                        <p class="text-gray-600 mb-4">Visualização do formulário para impressão</p>
                        <p class="text-sm text-gray-500 mb-6">Este formulário será impresso em duas páginas (frente e verso)</p>
                        
                        <!-- Botões de Ação -->
                        <div class="flex justify-center space-x-4">
                            <button
                                @click="goBackToSamples"
                                class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors shadow-md"
                            >
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Voltar para Inserir Amostras
                            </button>
                            <button
                                @click="exportForm"
                                class="px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors shadow-md"
                            >
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Exportar PDF
                            </button>
                            <button
                                @click="printForm"
                                class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors shadow-md"
                            >
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Imprimir Formulário
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PÁGINA 1 - FRENTE -->
        <div id="printable-form" class="form-page page-1">
            <!-- Cabeçalho -->
            <div class="form-header">
                <div class="logo-section">
                    <div class="logo-placeholder">
                        <span class="logo-text">Laboratório<br>Raça</span>
                    </div>
                </div>
                <div class="title-section">
                    <h1>FORMULÁRIO DE REGISTRO</h1>
                    <h2>PLANILHA DE EXAMES</h2>
                    <div class="form-details">
                        <div>Nº DA CORRIDA: {{ formNumber }}</div>
                        <div>DATA: {{ formatDateShort(generatedAt) }}</div>
                    </div>
                </div>
                <div class="info-section">
                    <div class="info-box">
                        <div class="info-label">GARANTIA DA QUALIDADE</div>
                        <div class="info-content">
                            <div>Código: FR 026 Página 1 de 2</div>
                            <div>Revisão: 13/09/2022 Versão: 2.4</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grade de Amostras -->
            <div class="samples-grid">
                <table class="grid-table">
                    <thead>
                        <tr>
                            <th class="grid-corner"></th>
                            <th v-for="col in 12" :key="col" class="grid-header">{{ col }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, rowIndex) in ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']" :key="row">
                            <td class="grid-row-header">{{ row }}</td>
                            <td v-for="col in 12" :key="col" class="grid-cell">
                                <div class="sample-cell">
                                    <div class="sample-number">{{ getSampleForPosition(rowIndex, col - 1) }}</div>
                                    <div class="sample-details">
                                        <template v-if="getSampleDetails(rowIndex, col - 1)">
                                            <div class="sample-line">URGENTE</div>
                                            <div class="sample-line">{{ formatDateShort(getSampleDetails(rowIndex, col - 1).created_at) }}</div>
                                            <div class="sample-line">{{ getSampleDetails(rowIndex, col - 1).species?.toUpperCase() || 'BOVINO' }}</div>
                                            <div class="sample-line">&nbsp;</div>
                                        </template>
                                        <template v-else>
                                            <div class="sample-line">&nbsp;</div>
                                            <div class="sample-line">&nbsp;</div>
                                            <div class="sample-line">&nbsp;</div>
                                            <div class="sample-line">&nbsp;</div>
                                        </template>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Seções de Processo -->
            <div class="process-sections">
                <div class="section-row">
                    <div class="section-title">TRIAGEM</div>
                    <div class="section-title">EXTRAÇÃO PELO</div>
                    <div class="section-title">EXTRAÇÃO:</div>
                    <div class="section-title">SANGUE ( ) SÊMEN ( )</div>
                </div>
                
                <div class="section-content">
                    <div class="section-column">
                        <div class="field-row">
                            <span class="field-label">Data:</span>
                            <span class="field-line">___/___/___</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Horário:</span>
                            <span class="field-line">_______</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Observações:</span>
                            <span class="field-line">_______</span>
                        </div>
                    </div>
                    
                    <div class="section-column">
                        <div class="field-row">
                            <span class="field-label">Data:</span>
                            <span class="field-line">___/___/___</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Horário:</span>
                            <span class="field-line">_______</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Preparo do Tampão de Extração</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Data de preparo:</span>
                            <span class="field-line">_______</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Validade:</span>
                            <span class="field-line">_______</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Reagentes utilizados no preparo:</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Tampão 10X:</span>
                            <span class="field-line">Lote: _____ validade: ___/___/___</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Tween 20X:</span>
                            <span class="field-line">Lote: _____ validade: ___/___/___</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Água Ultrapura:</span>
                            <span class="field-line">Lote: _____ validade: ___/___/___</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Proteinase K 10 mg/mL:</span>
                        </div>
                        <div class="field-row">
                            <span class="field-line">Lote: _____________ validade: _______</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Equipamentos Utilizados:</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Termobloco:</span>
                            <span class="field-line">_______ Centrífuga: _______</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Micropipetas:</span>
                            <span class="field-line">_______</span>
                        </div>
                    </div>
                    
                    <div class="section-column">
                        <div class="field-row">
                            <span class="field-label">Data:</span>
                            <span class="field-line">___/___/___</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Horário:</span>
                            <span class="field-line">_______</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Tampão PBS</span>
                            <span class="field-line">Lote: _____ Validade: _____</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Tampão de Extração</span>
                            <span class="field-line">Lote: _____ Validade: _____</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Tampão Lise Celular (TLC)</span>
                            <span class="field-line">Lote: _____ Validade: _____</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Tampão Lise Nuclear (TLN)</span>
                            <span class="field-line">Lote: _____ Validade: _____</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Proteína K 10 mg/ml</span>
                            <span class="field-line">Lote: _____ Validade: _____</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Mercaptoetanol ( ) ou Ditiotreitol 40 mM ( )</span>
                        </div>
                        <div class="field-row">
                            <span class="field-line">Lote: _____________ Validade: _______</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">NaCl 5M</span>
                            <span class="field-line">Lote: _____ Validade: _____</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Isopropanol</span>
                            <span class="field-line">Lote: _____ Validade: _____</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Etanol 70%</span>
                            <span class="field-line">Lote: _____ Validade: _____</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Água Ultrapura</span>
                            <span class="field-line">Lote: _____ Validade: _____</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Equipamentos Utilizados:</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Termobloco:</span>
                            <span class="field-line">_______ Centrífuga: _______</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Micropipetas:</span>
                            <span class="field-line">_______</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rodapé da Página 1 -->
            <div class="page-footer">
                <div class="footer-left">
                    <span>Resp.: _______________</span>
                </div>
                <div class="footer-center">
                    <span>Resp.: _______________</span>
                </div>
                <div class="footer-right">
                    <span>Resp.: _______________</span>
                </div>
            </div>
        </div>

        <!-- PÁGINA 2 - VERSO -->
        <div class="form-page page-2">
            <!-- Cabeçalho da Página 2 -->
            <div class="form-header">
                <div class="logo-section">
                    <div class="logo-placeholder">
                        <span class="logo-text">Laboratório<br>Raça</span>
                    </div>
                </div>
                <div class="title-section">
                    <h1>FORMULÁRIO DE REGISTRO</h1>
                    <h2>PLANILHA DE EXAMES - SNP</h2>
                </div>
                <div class="info-section">
                    <div class="info-box">
                        <div class="info-label">GARANTIA DE QUALIDADE</div>
                        <div class="info-content">
                            <div>Código: 7001</div>
                            <div>Página 2 de 2</div>
                            <div>Revisão: 07/02/2024 - Versão: 1.1</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seções da Página 2 -->
            <div class="page2-sections">
                <!-- Seção de Amplificação Genômica -->
                <div class="section-block">
                    <div class="section-header">AMPLIFICAÇÃO GENÔMICA</div>
                    <div class="section-fields">
                        <div class="field-row">
                            <span class="field-label">Data:</span>
                            <span class="field-line">_________________</span>
                            <span class="field-label">Purificação:</span>
                            <span class="field-line">_________________</span>
                            <span class="field-label">Quantificação DNA:</span>
                            <span class="field-line">_________________</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Amp Seq One Prep:</span>
                            <span class="field-line">Lote: _______ Val: _______</span>
                            <span class="field-label">Aparelho:</span>
                            <span class="field-line">_________________</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Amp Seq One Cleaning Solution:</span>
                            <span class="field-line">Lote: _______ Val: _______</span>
                            <span class="field-label">Observação:</span>
                            <span class="field-line">_________________</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Etanol 80%:</span>
                            <span class="field-line">Lote: _______ Val: _______</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Água Ultrapura:</span>
                            <span class="field-line">Lote: _______ Val: _______</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Equipamentos utilizados:</span>
                            <span class="field-line">_________________</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Observação:</span>
                            <span class="field-line">_________________</span>
                        </div>
                    </div>
                </div>

                <!-- Seção de Análise Controle 1 -->
                <div class="section-block">
                    <div class="section-header">Análise Controle 1</div>
                    <div class="section-fields">
                        <div class="field-row">
                            <span class="field-label">Maior frequência alélica:</span>
                            <span class="field-line">_________________</span>
                            <span class="field-label">Maior frequência alélica:</span>
                            <span class="field-line">_________________</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Menor frequência genotípica:</span>
                            <span class="field-line">_________________</span>
                            <span class="field-label">Menor frequência genotípica:</span>
                            <span class="field-line">_________________</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Menor frequência genotípica:</span>
                            <span class="field-line">_________________</span>
                            <span class="field-label">Menor frequência genotípica:</span>
                            <span class="field-line">_________________</span>
                        </div>
                    </div>
                </div>

                <!-- Seção de Análise Controle 2 -->
                <div class="section-block">
                    <div class="section-header">Análise Controle 2</div>
                    <div class="section-fields">
                        <div class="field-row">
                            <span class="field-label">Maior frequência alélica:</span>
                            <span class="field-line">_________________</span>
                            <span class="field-label">Teste de qui-quadrado:</span>
                            <span class="field-line">_________________</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Menor frequência genotípica:</span>
                            <span class="field-line">_________________</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Menor frequência genotípica:</span>
                            <span class="field-line">_________________</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Menor frequência genotípica:</span>
                            <span class="field-line">_________________</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Observações:</span>
                            <span class="field-line">_________________</span>
                        </div>
                    </div>
                </div>

                <!-- Seção de Observações -->
                <div class="section-block">
                    <div class="section-header">Observações</div>
                    <div class="observations-area">
                        <div class="obs-lines">
                            <div class="obs-line">_________________________________________________________________</div>
                            <div class="obs-line">_________________________________________________________________</div>
                            <div class="obs-line">_________________________________________________________________</div>
                            <div class="obs-line">_________________________________________________________________</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rodapé da Página 2 -->
            <div class="page-footer">
                <div class="footer-left">
                    <span>Data: _______________</span>
                </div>
                <div class="footer-center">
                    <span>Responsável: _______________</span>
                </div>
                <div class="footer-right">
                    <span>Quantidade de amostras liberadas: _______________</span>
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
    return props.samples && props.samples.length > 0 ? '33183' : '00000';
});

const generatedAt = computed(() => {
    return new Date().toISOString();
});

const getSampleForPosition = (row, col) => {
    // Números específicos da imagem para cada posição da grade
    const sampleNumbers = [
        // Linha A
        ['1009055', '1009088', '1009162', '1009184', '1009202', '1009236', '1009380', '1009106', '1009114', '1009124', '1009132', '1009146'],
        // Linha B
        ['1009052', '1009089', '1009165', '1009188', '1009204', '1009239', '1009381', '1009107', '1009115', '1009125', '1009133', '1009145'],
        // Linha C
        ['1009059', '1009090', '1009168', '1009190', '1009205', '1009243', '1009390', '1009108', '1009117', '1009126', 'C11', '1009274'],
        // Linha D
        ['1009076', '1009091', '1009169', '1009192', '1009207', '1009247', '1009391', '1009109', '1009118', '1009127', '', '1009276'],
        // Linha E
        ['1009073', '1009092', '1009174', '1009194', 'E5', '1009253', '1009102', '1009110', '1009119', '1009128', '1009134', '1009277'],
        // Linha F
        ['1009080', '1009375', '1009176', '1009196', '1009224', '1009260', '1009103', '1009111', '1009121', '1009129', '1009140', '1009279'],
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

// Methods
const printForm = () => {
    window.print()
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
            <style>
                @page {
                    size: A4;
                    margin: 0;
                }
                
                body {
                    margin: 0;
                    padding: 0;
                    font-family: Arial, sans-serif;
                }
                
                .form-page {
                    width: 210mm;
                    min-height: 297mm;
                    padding: 10mm;
                    background: white;
                    font-family: Arial, sans-serif;
                    font-size: 10px;
                    line-height: 1.2;
                    position: relative;
                    page-break-after: always;
                }
                
                .page-2 {
                    page-break-before: always;
                }
                
                .form-header {
                    display: flex;
                    border: 2px solid black;
                    margin-bottom: 5mm;
                }
                
                .logo-section {
                    width: 25mm;
                    border-right: 1px solid black;
                    padding: 2mm;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                
                .logo-placeholder {
                    width: 20mm;
                    height: 15mm;
                    border: 1px solid black;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                }
                
                .logo-text {
                    font-size: 8px;
                    font-weight: bold;
                    line-height: 1.1;
                }
                
                .title-section {
                    flex: 1;
                    padding: 2mm;
                    text-align: center;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    border-right: 1px solid black;
                }
                
                .title-section h1 {
                    font-size: 12px;
                    font-weight: bold;
                    margin: 0 0 2px 0;
                }
                
                .title-section h2 {
                    font-size: 11px;
                    font-weight: bold;
                    margin: 0;
                }
                
                .info-section {
                    width: 40mm;
                    padding: 2mm;
                }
                
                .info-box {
                    border: 1px solid black;
                    padding: 1mm;
                    height: 100%;
                }
                
                .info-label {
                    font-size: 8px;
                    font-weight: bold;
                    text-align: center;
                    margin-bottom: 2mm;
                }
                
                .info-content {
                    font-size: 7px;
                    line-height: 1.3;
                }
                
                .main-table {
                    width: 100%;
                    border-collapse: collapse;
                    border: 1px solid black;
                }
                
                .main-table th,
                .main-table td {
                    border: 1px solid black;
                    padding: 1mm;
                    text-align: left;
                    vertical-align: top;
                    font-size: 8px;
                }
                
                .main-table th {
                    background-color: #f0f0f0;
                    font-weight: bold;
                    text-align: center;
                    font-size: 7px;
                }
                
                .info-sections {
                    border: 1px solid black;
                    margin-bottom: 5mm;
                }
                
                .section-row {
                    display: flex;
                    border-bottom: 1px solid black;
                }
                
                .section-title {
                    flex: 1;
                    padding: 2mm;
                    font-weight: bold;
                    font-size: 8px;
                    text-align: center;
                    border-right: 1px solid black;
                    background-color: #f0f0f0;
                }
                
                .section-title:last-child {
                    border-right: none;
                }
                
                .section-content {
                    display: flex;
                    min-height: 80mm;
                }
                
                .section-column {
                    flex: 1;
                    padding: 2mm;
                    border-right: 1px solid black;
                    font-size: 7px;
                }
                
                .section-column:last-child {
                    border-right: none;
                }
                
                .field-group {
                    margin-bottom: 2mm;
                    display: flex;
                    align-items: center;
                }
                
                .field-label {
                    font-weight: bold;
                    margin-right: 2mm;
                    min-width: fit-content;
                }
                
                .field-value {
                    flex: 1;
                }
                
                .page-footer {
                    position: absolute;
                    bottom: 10mm;
                    left: 10mm;
                    right: 10mm;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    font-size: 8px;
                    border-top: 1px solid black;
                    padding-top: 2mm;
                }
                
                .footer-center {
                    text-align: center;
                }
                
                .page2-sections {
                    margin-bottom: 10mm;
                }
                
                .section-block {
                    border: 1px solid black;
                    margin-bottom: 5mm;
                }
                
                .section-header {
                    background-color: #f0f0f0;
                    padding: 2mm;
                    font-weight: bold;
                    font-size: 9px;
                    text-align: center;
                    border-bottom: 1px solid black;
                }
                
                .section-fields {
                    padding: 2mm;
                }
                
                .field-row {
                    display: flex;
                    margin-bottom: 2mm;
                    align-items: center;
                    font-size: 7px;
                }
                
                .field-row .field-label {
                    font-weight: bold;
                    margin-right: 2mm;
                    white-space: nowrap;
                }
                
                .field-line {
                    border-bottom: 1px solid black;
                    margin: 0 2mm;
                    min-width: 30mm;
                    height: 3mm;
                    display: inline-block;
                }
                
                .observations-area {
                    padding: 2mm;
                }
                
                .obs-lines {
                    margin-top: 2mm;
                }
                
                .obs-line {
                    margin-bottom: 3mm;
                    font-size: 8px;
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
    if (!dateString) return ''
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
/* Estilos gerais para impressão */
@media print {
    body * {
        visibility: hidden;
    }
    
    #printable-form,
    #printable-form * {
        visibility: visible;
    }
    
    .no-print {
        display: none !important;
    }
    
    .form-page {
        position: absolute;
        left: 0;
        top: 0;
        width: 210mm;
        height: 297mm;
        margin: 0;
        padding: 10mm;
        box-sizing: border-box;
        page-break-after: always;
        background: white;
    }
    
    .page-2 {
        page-break-before: always;
    }
}

/* Estilos para visualização na tela */
.form-page {
    width: 210mm;
    min-height: 297mm;
    margin: 20px auto;
    padding: 10mm;
    background: white;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    font-family: Arial, sans-serif;
    font-size: 8pt;
    line-height: 1.2;
    position: relative;
}

/* Cabeçalho do formulário */
.form-header {
    display: flex;
    border: 2px solid black;
    margin-bottom: 5mm;
}

.logo-section {
    width: 25mm;
    border-right: 1px solid black;
    padding: 2mm;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-placeholder {
    width: 20mm;
    height: 15mm;
    border: 1px solid black;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.logo-text {
    font-size: 8px;
    font-weight: bold;
    line-height: 1.1;
}

.title-section {
    flex: 1;
    padding: 2mm;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    border-right: 1px solid black;
}

.title-section h1 {
    font-size: 12px;
    font-weight: bold;
    margin: 0 0 2px 0;
}

.title-section h2 {
    font-size: 11px;
    font-weight: bold;
    margin: 0;
}

.info-section {
    width: 40mm;
    padding: 2mm;
}

.info-box {
    border: 1px solid black;
    padding: 1mm;
    height: 100%;
}

.info-label {
    font-size: 8px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 2mm;
}

.info-content {
    font-size: 7px;
    line-height: 1.3;
}

/* Grade de Amostras 8x12 */
.samples-grid {
    margin-bottom: 15px;
}

.grid-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 6pt;
}

.grid-table th,
.grid-table td {
    border: 1px solid black;
    padding: 1px;
    text-align: center;
    vertical-align: top;
}

.grid-corner {
    width: 15px;
    height: 15px;
    background: #f0f0f0;
}

.grid-header {
    width: 60px;
    height: 15px;
    background: #f0f0f0;
    font-weight: bold;
    font-size: 8pt;
}

.grid-row-header {
    width: 15px;
    background: #f0f0f0;
    font-weight: bold;
    font-size: 8pt;
}

.grid-cell {
    width: 60px;
    height: 45px;
    padding: 1px;
}

.sample-cell {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.sample-number {
    font-weight: bold;
    font-size: 7pt;
    margin-bottom: 1px;
    text-align: center;
}

.sample-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.sample-line {
    font-size: 5pt;
    line-height: 1;
    margin-bottom: 1px;
    text-align: left;
}

/* Seções de Processo */
.process-sections {
    margin-bottom: 15px;
}

.section-row {
    display: flex;
    margin-bottom: 5px;
}

.section-title {
    flex: 1;
    font-weight: bold;
    font-size: 8pt;
    text-align: center;
    text-transform: uppercase;
    border: 1px solid black;
    padding: 3px;
    background: #f0f0f0;
}

.section-content {
    display: flex;
    border: 1px solid black;
    border-top: none;
}

.section-column {
    flex: 1;
    padding: 5px;
    border-right: 1px solid black;
    font-size: 6pt;
}

.section-column:last-child {
    border-right: none;
}

.field-row {
    margin-bottom: 3px;
    display: flex;
    align-items: baseline;
}

.field-label {
    font-weight: bold;
    margin-right: 5px;
    white-space: nowrap;
}

.field-line {
    border-bottom: 1px solid black;
    flex: 1;
    min-width: 50px;
    height: 12px;
    display: inline-block;
}

/* Rodapé da página */
.page-footer {
    position: absolute;
    bottom: 10mm;
    left: 10mm;
    right: 10mm;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 8pt;
    border-top: 1px solid black;
    padding-top: 2mm;
}

.footer-center {
    text-align: center;
}

/* Estilos específicos da página 2 */
.page2-sections {
    margin-bottom: 10mm;
}

.section-block {
    border: 1px solid black;
    margin-bottom: 5mm;
}

.section-header {
    background-color: #f0f0f0;
    padding: 2mm;
    font-weight: bold;
    font-size: 9px;
    text-align: center;
    border-bottom: 1px solid black;
}

.section-fields {
    padding: 2mm;
}

.field-row {
    display: flex;
    margin-bottom: 2mm;
    align-items: center;
    font-size: 7px;
}

.field-row .field-label {
    font-weight: bold;
    margin-right: 2mm;
    white-space: nowrap;
}

.field-line {
    border-bottom: 1px solid black;
    margin: 0 2mm;
    min-width: 30mm;
    height: 3mm;
    display: inline-block;
}

.observations-area {
    padding: 2mm;
}

.obs-lines {
    margin-top: 2mm;
}

.obs-line {
    margin-bottom: 3mm;
    font-size: 8px;
}

/* Ajustes para impressão */
@page {
    size: A4;
    margin: 0;
}

@media print {
    .form-page {
        box-shadow: none;
        margin: 0;
    }
    
    .page-1 {
        page-break-after: always;
    }
    
    .page-2 {
        page-break-before: always;
    }
    
    .grid-table {
        font-size: 6pt;
    }
    
    .sample-number {
        font-size: 7pt;
    }
    
    .sample-line {
        font-size: 5pt;
    }
    
    .field-row {
        font-size: 6pt;
    }
    
    .section-title {
        font-size: 8pt;
    }
}
</style>