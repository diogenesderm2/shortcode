<template>
    <Modal :show="show" @close="closeModal" max-width="xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ isEditing ? 'Editar Proprietário' : 'Novo Proprietário' }}
                </h3>
                <button @click="closeModal"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200 p-1 rounded-full hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="submitForm" class="space-y-6">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <InputLabel for="registration" value="Registro" class="text-sm font-medium text-gray-700" />
                        <button type="button" @click="generate"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Gerar Registro
                        </button>
                    </div>
                    <TextInput id="registration" v-model="formOwner.registration" type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required autofocus />
                    <InputError :message="formOwner.errors.registration" class="mt-2" />
                </div>
                <div class="space-y-2">
                    <InputLabel for="name" value="Nome do Proprietário" class="text-sm font-medium text-gray-700" />
                    <TextInput id="name" v-model="formOwner.name" type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required autofocus placeholder="Digite o nome do proprietário" />
                    <InputError :message="formOwner.errors.name" class="mt-2" />
                </div>
                <div class="space-y-2">
                    <InputLabel for="rg" value="RG do Proprietário" class="text-sm font-medium text-gray-700" />
                    <TextInput id="rg" v-model="formOwner.rg" type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required autofocus placeholder="Digite o RG do proprietário" />
                    <InputError :message="formOwner.errors.rg" class="mt-2" />
                </div>
                 <div class="space-y-2">
                    <InputLabel for="cpf" value="CPF do Proprietário" class="text-sm font-medium text-gray-700" />
                    <TextInput id="cpf" v-model="formOwner.cpf" type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required autofocus placeholder="Digite o CPF do proprietário" />
                    <InputError :message="formOwner.errors.cpf" class="mt-2" />
                </div>
                 <div class="space-y-2">
                    <InputLabel for="cnpj" value="CNPJ do Proprietário" class="text-sm font-medium text-gray-700" />
                    <TextInput id="cnpj" v-model="formOwner.cnpj" type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required autofocus placeholder="Digite o CNPJ do proprietário" />
                    <InputError :message="formOwner.errors.cnpj" class="mt-2" />
                </div>
                 <div class="space-y-2">
                    <InputLabel for="property_name" value="Propriedade do Proprietário" class="text-sm font-medium text-gray-700" />
                    <TextInput id="property_name" v-model="formOwner.property_name" type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required autofocus placeholder="Digite o nome da propriedade do proprietário" />
                    <InputError :message="formOwner.errors.property_name" class="mt-2" />
                </div>

                <div class="flex items-center space-x-3">
                    <input id="is_active" v-model="formOwner.is_active" type="checkbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-all duration-200" />
                    <InputLabel for="is_active" value="Proprietário Ativo" class="text-sm font-medium text-gray-700" />
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <SecondaryButton @click="closeModal" type="button"
                        class="transition-all duration-200 hover:bg-gray-100">
                        Cancelar
                    </SecondaryButton>
                    <PrimaryButton :disabled="formOwner.processing" type="submit"
                        class="transition-all duration-200 transform hover:scale-105">
                        <span v-if="formOwner.processing" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Salvando...
                        </span>
                        <span v-else>{{ isEditing ? 'Atualizar' : 'Criar' }}</span>
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>



<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import Modal from './Modal.vue';
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { generateRegistrationNumber } from '@/Utils/registration_number/RegistrationNumber.js';

const user = computed(() => usePage().props.auth.user)
const registration = ref(generateRegistrationNumber()); // Gera automaticamente na inicialização

const formOwner = useForm({
    registration: registration.value,
    user_id: user.value.id,
    name: 'Diogenes',
    rg: '4561157',
    cpf: '12765471290',
    cnpj: '12346598732165',
    property_name: 'Minha fazenda',
    image: 'https://placehold.co/80x80',
    is_active: true,
})


const submitForm = () => {
    formOwner.post(route('admin.owner.store'), {
        onSuccess: () => {
            closeModal();
            formOwner.reset();
        },
        onError: (errors) => {

        }
    })
}
const closeModal = () => {
    emits('close');

};

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
     user: {
        type: Object,
        default: () => ({})
    }
})
const emits = defineEmits(['close'])

// Observa quando o modal abre para gerar novo registration
watch(() => props.show, (newValue) => {
    if (newValue) {
        const newRegistration = generateRegistrationNumber();
        registration.value = newRegistration;
        formOwner.registration = newRegistration;
    }
});

function generate() {
    const newRegistration = generateRegistrationNumber();
    registration.value = newRegistration;
    formOwner.registration = newRegistration;
}
</script>
