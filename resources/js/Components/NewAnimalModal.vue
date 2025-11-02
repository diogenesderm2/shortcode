<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                Cadastrar Novo Animal
                            </h3>

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

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipo de Animal</label>
                                    <select 
                                        v-model="form.animal_type" 
                                        @change="loadBreeds"
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">Selecione o tipo</option>
                                        <option v-for="type in animalTypes" :key="type.id" :value="type.id">
                                            {{ type.name }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Raça</label>
                                    <select 
                                        v-model="form.breed_id" 
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">Selecione a raça</option>
                                        <option v-for="breed in availableBreeds" :key="breed.id" :value="breed.id">
                                            {{ breed.name }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Protocolo (Opcional)</label>
                                    <input 
                                        v-model="form.protocol" 
                                        type="text" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Digite o protocolo (opcional)"
                                    >
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        @click="submitForm"
                        type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        :disabled="processing"
                    >
                        <span v-if="processing">Processando...</span>
                        <span v-else>Cadastrar Animal</span>
                    </button>
                    <button 
                        @click="closeModal"
                        type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    show: Boolean,
    owners: Array,
});

const emit = defineEmits(['close', 'animal-created']);

const processing = ref(false);
const animalTypes = ref([]);
const availableBreeds = ref([]);

// Formulário reativo
const form = reactive({
    name: '',
    rg: '',
    birth_date: '',
    sex: '',
    owner_id: '',
    animal_type: '',
    breed_id: '',
    protocol: '',
});

// Carregar tipos de animais ao montar o componente
onMounted(async () => {
    try {
        const response = await axios.get(route('admin.animal-types.index'));
        animalTypes.value = response.data;
    } catch (error) {
        console.error('Error loading animal types:', error);
    }
});

// Carregar raças baseadas no tipo de animal selecionado
const loadBreeds = async () => {
    if (!form.animal_type) {
        availableBreeds.value = [];
        return;
    }

    try {
        const response = await axios.get(route('admin.breeds.by-type', form.animal_type));
        availableBreeds.value = response.data;
        form.breed_id = ''; // Reset breed selection
    } catch (error) {
        console.error('Error loading breeds:', error);
        availableBreeds.value = [];
    }
};

// Fechar modal
const closeModal = () => {
    // Reset form
    Object.keys(form).forEach(key => {
        form[key] = '';
    });
    availableBreeds.value = [];
    emit('close');
};

// Submeter formulário
const submitForm = () => {
    processing.value = true;
    
    // Converter sex para genre
    const data = {
        ...form,
        genre: form.sex === 'macho' ? 1 : 2,
        birth: form.birth_date,
        register: form.rg,
    };
    
    router.post(route('admin.animals.store'), data, {
        onSuccess: () => {
            processing.value = false;
            emit('animal-created');
        },
        onError: (errors) => {
            processing.value = false;
            console.error('Validation errors:', errors);
        }
    });
};
</script>