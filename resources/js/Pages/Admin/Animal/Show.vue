<template>
    <AppLayout title="Detalhes do Animal">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalhes do Animal
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Detalhes do animal -->
                        <div class="mb-8">
                            <div class="flex justify-between items-start mb-6">
                                <h3 class="text-2xl font-bold text-gray-900">{{ animal.name }}</h3>
                                <Link 
                                    :href="route('admin.animals.edit', animal.id)" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300"
                                >
                                    Editar Animal
                                </Link>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900 mb-3">Informações Básicas</h4>
                                    <div class="space-y-2">
                                        <div class="flex">
                                            <span class="text-gray-600 w-32">RG:</span>
                                            <span class="text-gray-900 font-medium">{{ animal.rg }}</span>
                                        </div>
                                        <div class="flex">
                                            <span class="text-gray-600 w-32">Data de Nasc.:</span>
                                            <span class="text-gray-900 font-medium">{{ formatDate(animal.birth_date) }}</span>
                                        </div>
                                        <div class="flex">
                                            <span class="text-gray-600 w-32">Sexo:</span>
                                            <span class="text-gray-900 font-medium">{{ animal.sex === 'macho' ? 'Macho' : 'Fêmea' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900 mb-3">Proprietário</h4>
                                    <div class="space-y-2">
                                        <div class="flex">
                                            <span class="text-gray-600 w-32">Nome:</span>
                                            <span class="text-gray-900 font-medium">{{ animal.owner?.name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seção de Amostras -->
                <div class="mt-8 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Amostras Relacionadas</h3>
                        
                        <div v-if="samples.length === 0" class="text-gray-500 text-center py-4">
                            Nenhuma amostra encontrada para este animal.
                        </div>
                        
                        <div v-else class="space-y-6">
                            <div v-for="sample in samples" :key="sample.id" class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-900">
                                            Amostra: {{ sample.sample_code }}
                                            <span class="ml-2 px-2 py-1 text-xs rounded-full" 
                                                :class="{
                                                    'bg-yellow-100 text-yellow-800': sample.status === 'pendente',
                                                    'bg-blue-100 text-blue-800': sample.status === 'processando',
                                                    'bg-green-100 text-green-800': sample.status === 'concluido'
                                                }">
                                                {{ formatStatus(sample.status) }}
                                            </span>
                                        </h4>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Tipo: {{ formatSampleType(sample.sample_type) }} | 
                                            Data de Coleta: {{ formatDate(sample.collection_date) }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div v-if="sample.child" class="bg-white p-3 rounded-md shadow-sm">
                                        <h5 class="font-medium text-gray-800">Animal Filho</h5>
                                        <p class="text-sm text-gray-600">{{ sample.child.name }} ({{ sample.child.rg }})</p>
                                        <p class="text-sm text-gray-600">{{ sample.child.sex === 'macho' ? 'Macho' : 'Fêmea' }}</p>
                                    </div>
                                    
                                    <div v-if="sample.father" class="bg-white p-3 rounded-md shadow-sm">
                                        <h5 class="font-medium text-gray-800">Animal Pai</h5>
                                        <p class="text-sm text-gray-600">{{ sample.father.name }} ({{ sample.father.rg }})</p>
                                        <p class="text-sm text-gray-600">{{ sample.father.sex === 'macho' ? 'Macho' : 'Fêmea' }}</p>
                                    </div>
                                    
                                    <div v-if="sample.mother" class="bg-white p-3 rounded-md shadow-sm">
                                        <h5 class="font-medium text-gray-800">Animal Mãe</h5>
                                        <p class="text-sm text-gray-600">{{ sample.mother.name }} ({{ sample.mother.rg }})</p>
                                        <p class="text-sm text-gray-600">{{ sample.mother.sex === 'macho' ? 'Macho' : 'Fêmea' }}</p>
                                    </div>
                                </div>
                                
                                <div v-if="sample.observations" class="mt-4 bg-white p-3 rounded-md shadow-sm">
                                    <h5 class="font-medium text-gray-800">Observações</h5>
                                    <p class="text-sm text-gray-600">{{ sample.observations }}</p>
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
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    animal: Object,
    samples: Array,
});

// Formatação de data
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
};

// Formatação do tipo de amostra
const formatSampleType = (type) => {
    const types = {
        'sangue': 'Sangue',
        'pelo': 'Pelo',
        'saliva': 'Saliva'
    };
    return types[type] || type;
};

// Formatação do status
const formatStatus = (status) => {
    const statuses = {
        'pendente': 'Pendente',
        'processando': 'Em Processamento',
        'concluido': 'Concluído'
    };
    return statuses[status] || status;
};
</script>