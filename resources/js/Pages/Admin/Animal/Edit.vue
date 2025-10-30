<template>
    <AppLayout title="Editar Animal">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Editar Animal
                </h2>
                <Link 
                    :href="route('admin.animals.index')" 
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Pesquisar Animais
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Mensagem de sucesso -->
                <div v-if="$page.props.flash.message" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ $page.props.flash.message }}
                </div>

                <div class="mb-4 flex justify-end">
                    <Link 
                        :href="route('admin.animals.index')" 
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Pesquisar Animais
                    </Link>
                </div>
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Formulário de edição do animal -->
                        <form @submit.prevent="updateAnimal" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nome do Animal</label>
                                    <input 
                                        v-model="form.name" 
                                        type="text" 
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Digite o nome do animal"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">RG</label>
                                    <input 
                                        v-model="form.rg" 
                                        type="text" 
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Digite o RG do animal"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                                    <input 
                                        v-model="form.birth_date" 
                                        type="date" 
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sexo</label>
                                    <select 
                                        v-model="form.sex" 
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">Selecione o sexo</option>
                                        <option value="macho">Macho</option>
                                        <option value="femea">Fêmea</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Proprietário</label>
                                    <select 
                                        v-model="form.owner_id" 
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">Selecione o proprietário</option>
                                        <option v-for="owner in owners" :key="owner.id" :value="owner.id">
                                            {{ owner.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button 
                                    type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
                                    :disabled="processing"
                                >
                                    <span v-if="processing">Processando...</span>
                                    <span v-else>Atualizar Animal</span>
                                </button>
                            </div>
                        </form>
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
import { ref, reactive } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    animal: Object,
    samples: Array,
    owners: Array,
});

const processing = ref(false);

// Formulário reativo com os dados do animal
const form = reactive({
    name: props.animal.name,
    rg: props.animal.rg,
    birth_date: props.animal.birth_date,
    sex: props.animal.sex,
    owner_id: props.animal.owner_id,
});

// Função para atualizar o animal
const updateAnimal = () => {
    processing.value = true;
    
    router.put(route('admin.animals.update', props.animal.id), form, {
        onSuccess: () => {
            processing.value = false;
        },
        onError: () => {
            processing.value = false;
        }
    });
};

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