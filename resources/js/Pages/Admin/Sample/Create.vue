<template>
    <AppLayout title="Cadastrar Amostras">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cadastrar Amostras para Exame de DNA
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Progress Steps -->
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div :class="['flex items-center justify-center w-8 h-8 rounded-full text-sm font-medium', 
                                    currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600']">
                                    1
                                </div>
                                <span :class="['text-sm font-medium', currentStep >= 1 ? 'text-blue-600' : 'text-gray-500']">
                                    Selecionar Proprietário
                                </span>
                            </div>
                            <div class="flex-1 h-px bg-gray-300 mx-4"></div>
                            <div class="flex items-center space-x-4">
                                <div :class="['flex items-center justify-center w-8 h-8 rounded-full text-sm font-medium', 
                                    currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600']">
                                    2
                                </div>
                                <span :class="['text-sm font-medium', currentStep >= 2 ? 'text-blue-600' : 'text-gray-500']">
                                    Dados dos Animais
                                </span>
                            </div>
                            <div class="flex-1 h-px bg-gray-300 mx-4"></div>
                            <div class="flex items-center space-x-4">
                                <div :class="['flex items-center justify-center w-8 h-8 rounded-full text-sm font-medium', 
                                    currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600']">
                                    3
                                </div>
                                <span :class="['text-sm font-medium', currentStep >= 3 ? 'text-blue-600' : 'text-gray-500']">
                                    Dados da Amostra
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Step 1: Select Owner -->
                        <div v-if="currentStep === 1" class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900">Selecione o Proprietário</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div 
                                    v-for="owner in owners" 
                                    :key="owner.id"
                                    @click="selectOwner(owner)"
                                    :class="['border-2 rounded-lg p-4 cursor-pointer transition-all hover:shadow-md', 
                                        selectedOwner?.id === owner.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300']"
                                >
                                    <h4 class="font-medium text-gray-900">{{ owner.name }}</h4>
                                    <p class="text-sm text-gray-600">{{ owner.property_name }}</p>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button 
                                    @click="nextStep" 
                                    :disabled="!selectedOwner"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Próximo
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Animal Data -->
                        <div v-if="currentStep === 2" class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900">Dados dos Animais</h3>
                            
                            <!-- Child Animal -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-4">
                                    Animal Filho (Obrigatório)
                                    <span v-if="animals.child" class="ml-2 text-green-600 text-sm">✓ Cadastrado</span>
                                    <span v-else-if="childRg" class="ml-2 text-orange-600 text-sm">⚠ Verificando...</span>
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">RG do Animal</label>
                                        <input 
                                            v-model="childRg" 
                                            @blur="checkAnimal('child')"
                                            type="text" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Digite o RG do animal"
                                        >
                                    </div>
                                    <div v-if="animals.child">
                                        <label class="block text-sm font-medium text-gray-700">Animal Encontrado</label>
                                        <div class="mt-1 p-2 bg-green-100 rounded-md">
                                            <p class="text-sm text-green-800">{{ animals.child.name }} - {{ animals.child.sex }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Father Animal -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-4">Animal Pai (Opcional)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">RG do Animal</label>
                                        <input 
                                            v-model="fatherRg" 
                                            @blur="checkAnimal('father')"
                                            type="text" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Digite o RG do animal (opcional)"
                                        >
                                    </div>
                                    <div v-if="animals.father">
                                        <label class="block text-sm font-medium text-gray-700">Animal Encontrado</label>
                                        <div class="mt-1 p-2 bg-green-100 rounded-md">
                                            <p class="text-sm text-green-800">{{ animals.father.name }} - {{ animals.father.sex }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mother Animal -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-4">Animal Mãe (Opcional)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">RG do Animal</label>
                                        <input 
                                            v-model="motherRg" 
                                            @blur="checkAnimal('mother')"
                                            type="text" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Digite o RG do animal (opcional)"
                                        >
                                    </div>
                                    <div v-if="animals.mother">
                                        <label class="block text-sm font-medium text-gray-700">Animal Encontrado</label>
                                        <div class="mt-1 p-2 bg-green-100 rounded-md">
                                            <p class="text-sm text-green-800">{{ animals.mother.name }} - {{ animals.mother.sex }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <button 
                                    @click="prevStep" 
                                    class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                                >
                                    Anterior
                                </button>
                                <button 
                                    @click="nextStep" 
                                    :disabled="!animals.child"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Próximo
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Sample Data -->
                        <div v-if="currentStep === 3" class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900">Dados da Amostra</h3>
                            
                            <form @submit.prevent="submitSample" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Código da Amostra</label>
                                        <input 
                                            v-model="sampleForm.sample_code" 
                                            type="text" 
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Ex: DNA001"
                                        >
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tipo de Amostra</label>
                                        <select 
                                            v-model="sampleForm.sample_type" 
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione o tipo</option>
                                            <option value="sangue">Sangue</option>
                                            <option value="pelo">Pelo</option>
                                            <option value="saliva">Saliva</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Data de Coleta</label>
                                        <input 
                                            v-model="sampleForm.collection_date" 
                                            type="date" 
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Observações</label>
                                    <textarea 
                                        v-model="sampleForm.observations" 
                                        rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Observações sobre a amostra (opcional)"
                                    ></textarea>
                                </div>

                                <div class="flex justify-between">
                                    <button 
                                        type="button"
                                        @click="prevStep" 
                                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                                    >
                                        Anterior
                                    </button>
                                    <button 
                                        type="submit" 
                                        :disabled="processing"
                                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50"
                                    >
                                        {{ processing ? 'Salvando...' : 'Cadastrar Amostra' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Animal Registration Modal -->
        <AnimalModal 
            :show="showAnimalModal" 
            :owner="selectedOwner"
            :animal-type="currentAnimalType"
            :rg="currentRg"
            @close="closeAnimalModal"
            @animal-created="handleAnimalCreated"
        />
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AnimalModal from '@/Components/AnimalModal.vue';
import axios from 'axios';

const props = defineProps({
    owners: Array,
});

const currentStep = ref(1);
const selectedOwner = ref(null);
const showAnimalModal = ref(false);
const currentAnimalType = ref('');
const currentRg = ref('');
const processing = ref(false);

// Animal RGs
const childRg = ref('');
const fatherRg = ref('');
const motherRg = ref('');

// Animals data
const animals = reactive({
    child: null,
    father: null,
    mother: null,
});

// Sample form
const sampleForm = reactive({
    sample_code: '',
    owner_id: null,
    father_id: null,
    mother_id: null,
    child_id: null,
    sample_type: '',
    collection_date: '',
    observations: '',
});

// Computed property to check if we can proceed to step 3
const canProceedToStep3 = computed(() => {
    return animals.child !== null && sampleForm.child_id !== null;
});

const selectOwner = (owner) => {
    selectedOwner.value = owner;
    sampleForm.owner_id = owner.id;
};

const nextStep = () => {
    if (currentStep.value < 3) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const checkAnimal = async (type) => {
    const rg = type === 'child' ? childRg.value : type === 'father' ? fatherRg.value : motherRg.value;
    
    if (!rg || !selectedOwner.value) return;

    try {
        const response = await axios.post(route('admin.samples.check-animal'), {
            rg: rg,
            owner_id: selectedOwner.value.id,
            type: type
        });

        if (response.data.exists && !response.data.sex_mismatch && response.data.animal) {
            animals[type] = response.data.animal;
            sampleForm[`${type}_id`] = response.data.animal.id;
            console.log(`Animal ${type} encontrado:`, response.data.animal);
        } else if (response.data.exists && response.data.sex_mismatch) {
            animals[type] = null;
            sampleForm[`${type}_id`] = null;
            alert(type === 'father' 
                ? 'O RG informado pertence a um animal que não é Macho. Selecione um Macho para o Pai.'
                : type === 'mother'
                    ? 'O RG informado pertence a um animal que não é Fêmea. Selecione uma Fêmea para a Mãe.'
                    : 'Sexo incompatível para o tipo selecionado.');
        } else {
            // Animal doesn't exist, show modal to create
            currentAnimalType.value = type;
            currentRg.value = rg;
            showAnimalModal.value = true;
        }
    } catch (error) {
        console.error('Error checking animal:', error);
    }
};

const closeAnimalModal = () => {
    showAnimalModal.value = false;
    currentAnimalType.value = '';
    currentRg.value = '';
};

const handleAnimalCreated = (animal) => {
    animals[currentAnimalType.value] = animal;
    sampleForm[`${currentAnimalType.value}_id`] = animal.id;
    console.log(`Animal ${currentAnimalType.value} criado:`, animal); // Debug
    closeAnimalModal();
};

const submitSample = async () => {
    processing.value = true;
    
    try {
        await axios.post(route('admin.samples.store'), sampleForm);
        
        // Reset form
        currentStep.value = 1;
        selectedOwner.value = null;
        childRg.value = '';
        fatherRg.value = '';
        motherRg.value = '';
        Object.keys(animals).forEach(key => animals[key] = null);
        Object.keys(sampleForm).forEach(key => {
            if (key.includes('_id')) {
                sampleForm[key] = null;
            } else {
                sampleForm[key] = '';
            }
        });
        
        alert('Amostra cadastrada com sucesso!');
    } catch (error) {
        console.error('Error creating sample:', error);
        alert('Erro ao cadastrar amostra. Tente novamente.');
    } finally {
        processing.value = false;
    }
};
</script>