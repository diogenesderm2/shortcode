<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SampleTable from '@/Components/SampleTable.vue';
import Modal from '@/Components/Modal.vue';
import GeneticComparisonModal from '@/Components/GeneticComparisonModal.vue';
import axios from 'axios';

// Props
const props = defineProps({
    animal: {
        type: Object,
        required: true
    },
    samples: {
        type: Array,
        default: () => []
    }
});

// Refs para controlar modais/estados
const recordsOpen = ref(false);
const updateOpen = ref(false);
const compareOpen = ref(false);
const newSampleOpen = ref(false);
const testsModalOpen = ref(false);
const activeTab = ref('samples');

// Refs para dados dos testes
const tests = ref([]);
const loadingTests = ref(false);
const testsError = ref('');

// Refs para qualificações genéticas
const calculatingQualifications = ref(false);
const qualificationResults = ref([]);

// Refs para modal de comparação genética
const geneticComparisonModalOpen = ref(false);



// Métodos
const handleCompare = () => {
    console.log('Comparar amostras');
    // Implementar lógica de comparação
};

const handleSampleReleased = (sampleId) => {
    console.log('Amostra liberada:', sampleId);
    // Aqui você pode adicionar lógica adicional se necessário
    // Por exemplo, recarregar dados ou mostrar notificação
};

const setRecordsOpen = (value) => {
    recordsOpen.value = value;
    if (value) activeTab.value = 'records';
};

const setUpdateOpen = (value) => {
    updateOpen.value = value;
    if (value) activeTab.value = 'update';
};

const setCompareOpen = (value) => {
    compareOpen.value = value;
    if (value) activeTab.value = 'compare';
};

const setNewSampleOpen = (value) => {
    newSampleOpen.value = value;
    if (value) activeTab.value = 'new-sample';
};

const setActiveTab = (tab) => {
    activeTab.value = tab;
};

// Método para abrir modal de comparação genética
const openGeneticComparison = () => {
    geneticComparisonModalOpen.value = true;
};

const closeGeneticComparison = () => {
    geneticComparisonModalOpen.value = false;
};


// Função para mostrar testes (chamada pelo SampleTable)
const handleShowTests = async (sample) => {
    console.log('Mostrando testes para amostra:', sample);
    testsModalOpen.value = true;
    await loadTests();
};

const closeTestsModal = () => {
    testsModalOpen.value = false;
    tests.value = [];
    testsError.value = '';
};

const loadTests = async () => {
    loadingTests.value = true;
    testsError.value = '';
    
    console.log('Carregando testes para animal ID:', props.animal.id);
    
    try {
        const response = await axios.get(`/admin/animals/${props.animal.id}/tests`);
        
        console.log('Resposta da API:', response.data);
        
        if (response.data.success) {
            tests.value = response.data.tests;
            console.log('Testes carregados:', tests.value);
        } else {
            testsError.value = response.data.message;
            console.log('Erro da API:', response.data.message);
        }
    } catch (error) {
        console.error('Erro ao carregar testes:', error);
        testsError.value = 'Erro ao carregar os testes do animal.';
    } finally {
        loadingTests.value = false;
    }
};

// Função para obter classes CSS das qualificações
const getQualificationClass = (status) => {
    switch(status) {
        case 'qualified':
            return 'bg-green-100 text-green-800';
        case 'not_qualified':
            return 'bg-red-100 text-red-800';
        case 'inconclusive':
            return 'bg-yellow-100 text-yellow-800';
        case 'pending':
        default:
            return 'bg-gray-100 text-gray-600';
    }
};

// Função para calcular qualificações
const calculateQualifications = async () => {
    calculatingQualifications.value = true;
    
    try {
        const response = await axios.post(`/admin/animals/${props.animal.id}/calculate-qualifications`);
        
        if (response.data.success) {
            qualificationResults.value = response.data.results;
            
            // Recarregar os testes para mostrar as qualificações atualizadas
            await loadTests();
            
            // Mostrar mensagem de sucesso
            alert('Qualificações calculadas com sucesso!');
        } else {
            alert('Erro ao calcular qualificações: ' + response.data.message);
        }
    } catch (error) {
        console.error('Erro ao calcular qualificações:', error);
        alert('Erro ao calcular qualificações. Verifique o console para mais detalhes.');
    } finally {
        calculatingQualifications.value = false;
    }
};
</script>

<template>
    <Head title="Detalhes do Animal" />
    
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalhes do Animal - {{ animal.name }}
            </h2>
        </template>

        <div class="min-h-screen bg-gray-50">
            <header class="bg-white/50 backdrop-blur-sm border-b border-gray-200 px-8 py-5 sticky top-0 z-10">
                <div class="flex items-center justify-between max-w-7xl mx-auto">
                    <PrimaryButton class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl shadow-md hover:shadow-lg transition-all">
                        Ensaios Emitidos
                    </PrimaryButton>
                    <SecondaryButton class="rounded-xl hover:border-blue-500 hover:text-blue-600 transition-all">
                        Escolher Outro Animal
                    </SecondaryButton>
                </div>
            </header>

            <main class="container mx-auto px-8 py-8 max-w-7xl">
                <!-- Navegação de Tabs -->
                <div class="bg-white/50 backdrop-blur-sm border border-gray-200 p-1.5 rounded-2xl shadow-sm mb-6">
                    <div class="flex flex-wrap gap-2">
                        <button 
                            @click="setRecordsOpen(true)"
                            :class="[
                                'px-4 py-2 rounded-xl transition-all',
                                activeTab === 'records' 
                                    ? 'bg-blue-600 text-white shadow-md' 
                                    : 'hover:bg-gray-100'
                            ]"
                        >
                            Registros
                        </button>
                        <button 
                            @click="setUpdateOpen(true)"
                            :class="[
                                'px-4 py-2 rounded-xl transition-all',
                                activeTab === 'update' 
                                    ? 'bg-blue-600 text-white shadow-md' 
                                    : 'hover:bg-gray-100'
                            ]"
                        >
                            Alterar
                        </button>
                        <button 
                            @click="setCompareOpen(true)"
                            :class="[
                                'px-4 py-2 rounded-xl transition-all',
                                activeTab === 'compare' 
                                    ? 'bg-blue-600 text-white shadow-md' 
                                    : 'hover:bg-gray-100'
                            ]"
                        >
                            Comparar
                        </button>
                        <button 
                            @click="setActiveTab('children')"
                            :class="[
                                'px-4 py-2 rounded-xl transition-all',
                                activeTab === 'children' 
                                    ? 'bg-blue-600 text-white shadow-md' 
                                    : 'hover:bg-gray-100'
                            ]"
                        >
                            Filhos (0)
                        </button>
                        <button 
                            @click="setNewSampleOpen(true)"
                            :class="[
                                'px-4 py-2 rounded-xl transition-all',
                                activeTab === 'new-sample' 
                                    ? 'bg-blue-600 text-white shadow-md' 
                                    : 'hover:bg-gray-100'
                            ]"
                        >
                            Nova Amostra
                        </button>
                        <button 
                            @click="setActiveTab('samples')"
                            :class="[
                                'px-4 py-2 rounded-xl transition-all',
                                activeTab === 'samples' 
                                    ? 'bg-blue-600 text-white shadow-md' 
                                    : 'hover:bg-gray-100'
                            ]"
                        >
                            Amostras
                        </button>

                    </div>
                </div>
                
                <div v-show="activeTab === 'samples'" class="space-y-6">
                    <!-- Grid com Informações do Animal e Observações lado a lado -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Card de Informações do Animal -->
                        <div class="bg-white rounded-lg shadow-sm p-6 h-fit">
                            <h3 class="text-lg font-semibold mb-4">Informações do Animal</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nome</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ animal.name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Registro</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ animal.register || 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sexo</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ animal.genre === 1 ? 'Macho' : animal.genre === 2 ? 'Fêmea' : 'Indefinido' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ animal.birth || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card de Observações com mesma altura -->
                        <div class="bg-white rounded-lg shadow-sm p-6 h-fit">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-lg font-bold text-gray-900">Observações</h3>
                                <PrimaryButton class="text-sm bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl shadow-sm hover:shadow-md transition-all">
                                    Adicionar
                                </PrimaryButton>
                            </div>
                            <div class="flex items-center justify-center py-8">
                                <p class="text-gray-500 text-sm text-center">
                                    Nenhuma observação foi encontrada.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card de Amostras ocupando 100% da largura -->
                    <div class="bg-white rounded-lg shadow-sm p-6 w-full">
                        <h3 class="text-lg font-semibold mb-4">Amostras do Animal</h3>
                        <SampleTable 
                            :samples="samples" 
                            @compare="handleCompare"
                            @sampleReleased="handleSampleReleased"
                            @showTests="handleShowTests"
                        />
                    </div>
                </div>
            </main>
        </div>

        <!-- Modal de Testes -->
        <Modal :show="testsModalOpen" @close="closeTestsModal" max-width="6xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Testes da amostra #{{ animal.id }}
                    </h2>
                    <button 
                        @click="closeTestsModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Botões de ação -->
                <div class="flex gap-3 mb-6">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Exportar Como PDF
                    </button>
                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Excluir
                    </button>
                    <button class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                        Seleções e Resultado
                    </button>
                    <button 
                        @click="calculateQualifications"
                        :disabled="calculatingQualifications"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="calculatingQualifications">Calculando...</span>
                        <span v-else>Resultado</span>
                    </button>
                </div>

                <!-- Conteúdo do modal -->
                <div v-if="loadingTests" class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-2 text-gray-600">Carregando testes...</span>
                </div>

                <div v-else-if="testsError" class="text-center py-8">
                    <p class="text-red-600">{{ testsError }}</p>
                </div>

                <div v-else-if="tests.length === 0" class="text-center py-8">
                    <p class="text-gray-500">Nenhum teste encontrado para este animal.</p>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ord</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resultado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Teste</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Padrão</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mãe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qualificação</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(test, index) in tests" :key="test.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-10 h-10 bg-orange-400 rounded flex items-center justify-center text-white font-semibold">
                                        {{ index + 1 }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ test.sample.code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ test.created_at }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    LUDMILA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ test.test_type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="test.is_default" class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                        SIM
                                    </span>
                                    <span v-else class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                        NÃO
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ test.father || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ test.mother || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div v-if="test.qualification" class="space-y-1">
                                        <span :class="[
                                            'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                            getQualificationClass(test.qualification.status)
                                        ]">
                                            {{ test.qualification.status_label }}
                                        </span>
                                        <div v-if="test.qualification.confidence_score" class="text-xs text-gray-500">
                                            {{ test.qualification.confidence_score }}% confiança
                                        </div>
                                        <div v-if="test.qualification.calculated_at" class="text-xs text-gray-400">
                                            {{ test.qualification.calculated_at }}
                                        </div>
                                    </div>
                                    <span v-else class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Não calculado
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-2">
                                        <button class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors">
                                            Resultado
                                        </button>
                                        <button class="px-3 py-1 bg-yellow-600 text-white text-xs rounded hover:bg-yellow-700 transition-colors">
                                            Editar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Informações adicionais -->
                <div v-if="tests.length > 0" class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>Pai:</strong> {{ tests.find(t => t.is_default)?.father || 'Não qualifica' }}
                    </p>
                    <p class="text-sm text-blue-800 mt-1">
                        <strong>Mãe:</strong> {{ tests.find(t => t.is_default)?.mother || 'Sem exame de DNA' }}
                    </p>
                </div>
            </div>
        </Modal>

        <!-- Modal de Comparação Genética -->
        <GeneticComparisonModal 
            :show="geneticComparisonModalOpen"
            :animal-id="animal.id"
            @close="closeGeneticComparison"
        />
    </AppLayout>
</template>
