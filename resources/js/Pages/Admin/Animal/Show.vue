<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SampleTable from '@/Components/SampleTable.vue';

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
const activeTab = ref('samples');

// Métodos
const handleCompare = () => {
    console.log('Comparar amostras');
    // Implementar lógica de comparação
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
                        />
                    </div>
                </div>
            </main>
        </div>
    </AppLayout>
</template>
