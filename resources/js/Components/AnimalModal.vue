<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="closeModal"></div>

            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        Cadastrar Animal {{ animalTypeLabel }}
                    </h3>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="space-y-4">
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
                            readonly
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm"
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

                    <div class="flex justify-end space-x-3 pt-4">
                        <button 
                            type="button" 
                            @click="closeModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit" 
                            :disabled="processing"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50"
                        >
                            {{ processing ? 'Salvando...' : 'Cadastrar Animal' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    owner: {
        type: Object,
        default: null
    },
    animalType: {
        type: String,
        default: ''
    },
    rg: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['close', 'animal-created']);

const processing = ref(false);

const form = reactive({
    name: '',
    rg: '',
    birth_date: '',
    sex: '',
    owner_id: null
});

const animalTypeLabel = computed(() => {
    switch (props.animalType) {
        case 'child': return '(Filho)';
        case 'father': return '(Pai)';
        case 'mother': return '(Mãe)';
        default: return '';
    }
});

watch(() => props.show, (newValue) => {
    if (newValue) {
        form.rg = props.rg;
        form.owner_id = props.owner?.id;
    }
});

const closeModal = () => {
    // Reset form
    Object.keys(form).forEach(key => {
        form[key] = '';
    });
    emit('close');
};

const submitForm = async () => {
    processing.value = true;
    
    try {
        const response = await axios.post(route('admin.samples.animals.store'), form);
        emit('animal-created', response.data.animal);
    } catch (error) {
        console.error('Error creating animal:', error);
        alert('Erro ao cadastrar animal. Tente novamente.');
    } finally {
        processing.value = false;
    }
};
</script>