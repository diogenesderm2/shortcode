<script setup>
import { computed, ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import GeneticComparisonModal from '@/Components/GeneticComparisonModal.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { router } from '@inertiajs/vue3';

// Props
const props = defineProps({
    samples: {
        type: Array,
        default: () => []
    }
});

// Refs para controlar o modal
const showGeneticModal = ref(false);
const selectedAnimal = ref(null);

// Refs para controlar o modal de liberação
const showReleaseModal = ref(false);
const selectedSample = ref(null);
const isReleasing = ref(false);

// Emits
const emit = defineEmits(['compare', 'sampleReleased', 'showTests']);

// Métodos
const onCompare = () => {
    emit('compare');
};

const getBadgeClass = (condition, successClass = 'bg-green-100 text-green-800', failClass = 'bg-gray-100 text-gray-600') => {
    return condition ? successClass : failClass;
};

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('pt-BR');
};

const formatCurrency = (value) => {
    if (!value) return 'R$ 0,00';
    return new Intl.NumberFormat('pt-BR', { 
        style: 'currency', 
        currency: 'BRL' 
    }).format(value);
};

const getQualificationLabel = (status) => {
    switch(status) {
        case 'qualified':
            return 'Qualificado';
        case 'not_qualified':
            return 'Não Qualificado';
        case 'inconclusive':
            return 'Inconclusivo';
        case 'pending':
            return 'Pendente';
        default:
            return 'Sem Análise';
    }
};

// Funções para o modal de genética
const showGeneticResults = (sample) => {
    if (sample.animal) {
        selectedAnimal.value = sample.animal;
        showGeneticModal.value = true;
    }
};

const closeGeneticModal = () => {
    showGeneticModal.value = false;
    selectedAnimal.value = null;
};

// Função para mostrar testes
const showTests = (sample) => {
    emit('showTests', sample);
};

// Funções para liberação de amostra
const openReleaseModal = (sample) => {
    selectedSample.value = sample;
    showReleaseModal.value = true;
};

const closeReleaseModal = () => {
    showReleaseModal.value = false;
    selectedSample.value = null;
    isReleasing.value = false;
};

// Funções para qualificação
const getQualificationClass = (status) => {
    switch (status) {
        case 'approved':
            return 'bg-green-100 text-green-800';
        case 'rejected':
            return 'bg-red-100 text-red-800';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'under_review':
            return 'bg-blue-100 text-blue-800';
        default:
            return 'bg-gray-100 text-gray-600';
    }
};



const generateReport = (sample) => {
    // Abrir o relatório em uma nova janela
    const reportUrl = route('admin.samples.report', { sample: sample.id });
    window.open(reportUrl, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
};

const confirmRelease = async () => {
    if (!selectedSample.value) return;
    
    isReleasing.value = true;
    
    try {
        const response = await fetch(route('admin.samples.release', selectedSample.value.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Atualizar a amostra localmente
            const sampleIndex = props.samples.findIndex(s => s.id === selectedSample.value.id);
            if (sampleIndex !== -1) {
                props.samples[sampleIndex].is_released = true;
                props.samples[sampleIndex].released_at = data.sample?.released_at || new Date().toISOString();
            }
            
            // Emitir evento para o componente pai
            emit('sampleReleased', selectedSample.value.id);
            
            // Mostrar mensagem de sucesso
            alert(data.message);
        } else {
            alert(data.message || 'Erro ao liberar amostra');
        }
    } catch (error) {
        console.error('Erro ao liberar amostra:', error);
        alert('Erro ao liberar amostra. Tente novamente.');
    } finally {
        closeReleaseModal();
    }
};


</script>

<template>
    <div class="space-y-6">
        <div v-for="(sample, index) in samples" :key="index" class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
            <!-- Header com gradiente -->
            <div class="relative px-8 py-6 bg-gradient-to-br from-blue-50 via-blue-25 to-transparent border-b border-gray-200">
                <div class="flex items-start justify-between">
                    <div class="flex gap-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-100 text-blue-600">
                            <!-- Building2 Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Proprietário</p>
                            <p class="font-bold text-lg text-gray-900">{{ sample.owner?.name || 'N/A' }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">
                                Código: <span class="font-medium">{{ sample.owner?.id || 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-green-100 text-green-600">
                            <!-- CreditCard Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 mb-1">Faturamento</p>
                            <p class="font-bold text-gray-900">{{ sample.billing_type?.type || 'N/A' }}</p>
                            <p class="text-lg font-bold text-green-600 mt-1">{{ formatCurrency(sample.value) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <!-- Informações principais -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="space-y-1">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nº Amostra</p>
                        <p class="text-lg font-bold text-blue-600">{{ sample.id }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Código Interno</p>
                        <p class="text-lg font-semibold text-gray-900">{{ sample.external_registry || 'N/A' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo Exame</p>
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                {{ sample.exam_type?.name || 'N/A' }}
                            </span>
                            <!-- Tipo de Teste -->
                            <div v-if="sample.tests && sample.tests.length > 0" class="mt-1">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tipo de Teste</p>
                                <div class="flex flex-wrap gap-1">
                                    <span 
                                        v-for="test in sample.tests" 
                                        :key="test.id"
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                    >
                                        {{ test.test_type?.name || test.testType?.name || 'N/A' }}
                                    </span>
                                </div>
                                
                                <!-- Qualificações -->
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1 mt-2">Qualificação</p>
                                <div class="flex flex-wrap gap-1">
                                    <span 
                                        v-for="test in sample.tests" 
                                        :key="`qual-${test.id}`"
                                        :class="[
                                            'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                            getQualificationClass(test.qualification?.status)
                                        ]"
                                        :title="test.qualification?.notes"
                                    >
                                        {{ getQualificationLabel(test.qualification?.status) }}
                                        <span v-if="test.qualification?.confidence_score" class="ml-1">
                                            ({{ test.qualification.confidence_score }}%)
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Entrada</p>
                        <p class="text-lg font-semibold text-gray-900">{{ formatDate(sample.created_at) }}</p>
                    </div>
                </div>

                <!-- Tipo e status -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-25 rounded-2xl p-6 mb-8 border border-gray-200">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-1 h-6 bg-blue-600 rounded-full"></div>
                        <p class="text-sm font-bold text-gray-900 uppercase tracking-wide">{{ sample.exam_type?.name || 'N/A' }}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                        <div class="space-y-2">
                            <p class="text-xs font-medium text-gray-500">Padrão</p>
                            <span :class="[
                                'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                getBadgeClass(sample.is_default, 'bg-green-100 text-green-800', 'bg-gray-100 text-gray-600')
                            ]">
                                {{ sample.is_default ? "Sim" : "Não" }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-medium text-gray-500">Técnica</p>
                            <span :class="[
                                'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                getBadgeClass(sample.is_technique, 'bg-blue-100 text-blue-800', 'bg-gray-100 text-gray-600')
                            ]">
                                {{ sample.is_technique ? "Sim" : "Não" }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-medium text-gray-500">Liberado</p>
                            <div v-if="sample.is_released">
                                <div class="space-y-1">
                                    <span :class="[
                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                        'bg-green-100 text-green-800'
                                    ]">
                                        Sim
                                    </span>
                                    <p v-if="sample.released_at" class="text-xs text-gray-600">
                                        {{ formatDate(sample.released_at) }}
                                    </p>
                                </div>
                            </div>
                            <div v-else>
                                <button
                                    @click="openReleaseModal(sample)"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200 transition-colors cursor-pointer border border-red-200 hover:border-red-300"
                                >
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Não - Clique para liberar
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-medium text-gray-500">Prioridade</p>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ sample.priority || 'Normal' }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-medium text-gray-500">Mostrar Cliente</p>
                            <span :class="[
                                'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                getBadgeClass(sample.show_client, 'bg-green-100 text-green-800', 'bg-red-100 text-red-800')
                            ]">
                                {{ sample.show_client ? "Sim" : "Não" }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-medium text-gray-500">Enviado</p>
                            <p class="text-sm font-semibold text-gray-900">{{ sample.is_sent ? 'Sim' : 'Não' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-medium text-gray-500">Arquivo</p>
                            <p class="text-sm font-semibold text-gray-900">{{ sample.file_name || 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="flex flex-wrap gap-3">
                    <SecondaryButton 
                        class="text-sm rounded-xl hover:border-blue-500 hover:text-blue-600 transition-all"
                        @click="showGeneticResults(sample)"
                    >
                        <!-- DNA Icon -->
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        🧬 Genética
                    </SecondaryButton>
                    <SecondaryButton 
                        @click="showTests(sample)"
                        class="text-sm rounded-xl hover:border-blue-500 hover:text-blue-600 transition-all"
                    >
                        <!-- Beaker Icon -->
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        Testes
                    </SecondaryButton>
                    <SecondaryButton class="text-sm rounded-xl hover:border-blue-500 hover:text-blue-600 transition-all">
                        <!-- FileText Icon -->
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Preview
                    </SecondaryButton>
                    <SecondaryButton class="text-sm rounded-xl hover:border-blue-500 hover:text-blue-600 transition-all">
                        <!-- History Icon -->
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Logs
                    </SecondaryButton>
                    <SecondaryButton class="text-sm rounded-xl hover:border-blue-500 hover:text-blue-600 transition-all">
                        <!-- MessageSquare Icon -->
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Observações
                    </SecondaryButton>
                    <SecondaryButton class="text-sm rounded-xl hover:border-blue-500 hover:text-blue-600 transition-all">
                        <!-- Send Icon -->
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Configurar Envio
                    </SecondaryButton>
                    <SecondaryButton 
                        @click="generateReport(sample)"
                        class="text-sm rounded-xl hover:border-green-500 hover:text-green-600 transition-all"
                    >
                        <!-- Document Report Icon -->
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Resultado
                    </SecondaryButton>
                    <PrimaryButton 
                        class="text-sm bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl shadow-md hover:shadow-lg transition-all ml-auto"
                        @click="onCompare"
                    >
                        <!-- GitCompare Icon -->
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Comparar
                    </PrimaryButton>
                </div>
            </div>
        </div>

        <!-- Modal de Comparação Genética -->
        <GeneticComparisonModal 
            :show="showGeneticModal"
            :animalId="selectedAnimal?.id"
            @close="closeGeneticModal"
        />

        <!-- Modal de Confirmação de Liberação -->
        <Modal :show="showReleaseModal" @close="closeReleaseModal">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Confirmar Liberação</h3>
                        <p class="text-sm text-gray-500">Esta ação não pode ser desfeita</p>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-gray-700">
                        Deseja realmente liberar a amostra <strong>{{ selectedSample?.id }}</strong>?
                    </p>
                    <div v-if="selectedSample" class="mt-3 p-3 bg-gray-50 rounded-lg">
                        <div class="text-sm">
                            <p><strong>Proprietário:</strong> {{ selectedSample.owner?.name || 'N/A' }}</p>
                            <p><strong>Animal:</strong> {{ selectedSample.animal?.name || 'N/A' }}</p>
                            <p><strong>Tipo de Exame:</strong> {{ selectedSample.exam_type?.name || 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <SecondaryButton @click="closeReleaseModal" :disabled="isReleasing">
                        Cancelar
                    </SecondaryButton>
                    <DangerButton @click="confirmRelease" :disabled="isReleasing">
                        <span v-if="isReleasing">Liberando...</span>
                        <span v-else>Sim, Liberar Amostra</span>
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </div>
</template>