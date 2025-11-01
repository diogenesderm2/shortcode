<template>
    <Modal :show="show" @close="closeModal" max-width="4xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ isEditing ? 'Editar proprietário' : 'Cadastrar novo proprietário' }}
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
                <!-- Nome Completo -->
                <div class="space-y-2">
                    <InputLabel for="name" value="Nome Completo" class="text-sm font-medium text-gray-700" />
                    <TextInput id="name" v-model="form.name" type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required autofocus placeholder="Digite o nome completo" />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <!-- RG -->
                <div class="space-y-2">
                    <InputLabel for="rg" value="RG" class="text-sm font-medium text-gray-700" />
                    <TextInput id="rg" v-model="form.rg" type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Digite o RG" />
                    <InputError :message="form.errors.rg" class="mt-2" />
                </div>

                <!-- CPF -->
                <div class="space-y-2">
                    <InputLabel for="cpf" value="CPF" class="text-sm font-medium text-gray-700" />
                    <TextInput id="cpf" v-model="form.cpf" type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Digite o CPF" />
                    <InputError :message="form.errors.cpf" class="mt-2" />
                </div>

                <!-- CNPJ -->
                <div class="space-y-2">
                    <InputLabel for="cnpj" value="CNPJ" class="text-sm font-medium text-gray-700" />
                    <TextInput id="cnpj" v-model="form.cnpj" type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Digite o CNPJ" />
                    <InputError :message="form.errors.cnpj" class="mt-2" />
                </div>

                <!-- Endereço -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <InputLabel for="address" value="Endereço" class="text-sm font-medium text-gray-700" />
                        <TextInput id="address" v-model="form.address" type="text"
                            class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Digite o endereço" />
                        <InputError :message="form.errors.address" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="adress_number" value="Número" class="text-sm font-medium text-gray-700" />
                        <TextInput id="adress_number" v-model="form.adress_number" type="text"
                            class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nº" />
                        <InputError :message="form.errors.adress_number" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="adress_complement" value="Complemento" class="text-sm font-medium text-gray-700" />
                        <TextInput id="adress_complement" v-model="form.adress_complement" type="text"
                            class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Complemento" />
                        <InputError :message="form.errors.adress_complement" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="district" value="Bairro" class="text-sm font-medium text-gray-700" />
                        <TextInput id="district" v-model="form.district" type="text"
                            class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Bairro" />
                        <InputError :message="form.errors.district" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <InputLabel for="city" value="Cidade" class="text-sm font-medium text-gray-700" />
                        <TextInput id="city" v-model="form.city" type="text"
                            class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Cidade" />
                        <InputError :message="form.errors.city" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="state" value="Estado" class="text-sm font-medium text-gray-700" />
                        <TextInput id="state" v-model="form.state" type="text"
                            class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Estado" />
                        <InputError :message="form.errors.state" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="zip_code" value="CEP" class="text-sm font-medium text-gray-700" />
                        <TextInput id="zip_code" v-model="form.zip_code" type="text"
                            class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="CEP" />
                        <InputError :message="form.errors.zip_code" class="mt-2" />
                    </div>
                </div>

                <!-- Propriedade -->
                <div class="space-y-2">
                    <InputLabel for="property_name" value="Propriedade" class="text-sm font-medium text-gray-700" />
                    <TextInput id="property_name" v-model="form.property_name" type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Nome da propriedade" />
                    <InputError :message="form.errors.property_name" class="mt-2" />
                </div>

                <!-- Responsável Financeiro -->
                <div class="space-y-2">
                    <InputLabel for="resp_financ" value="Responsável Financeiro" class="text-sm font-medium text-gray-700" />
                    <TextInput id="resp_financ" v-model="form.resp_financ" type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Nome do responsável financeiro" />
                    <InputError :message="form.errors.resp_financ" class="mt-2" />
                </div>

                <!-- Telefones -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <InputLabel value="Telefone(s)" class="text-sm font-medium text-gray-700" />
                        <button type="button" @click="addPhone" 
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            + Adicionar
                        </button>
                    </div>
                    <div v-for="(phone, index) in form.phones" :key="index" class="flex gap-2">
                        <TextInput v-model="phone.number" type="text" placeholder="Número do telefone"
                            class="flex-1 transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        <select v-model="phone.type" 
                            class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="WhatsApp">WhatsApp</option>
                            <option value="Residencial">Residencial</option>
                            <option value="Comercial">Comercial</option>
                            <option value="Celular">Celular</option>
                        </select>
                        <button type="button" @click="removePhone(index)"
                            class="px-3 py-2 text-red-600 hover:text-red-800">
                            Remover
                        </button>
                    </div>
                </div>

                <!-- E-mails -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <InputLabel value="E-mail(s)" class="text-sm font-medium text-gray-700" />
                        <button type="button" @click="addEmail" 
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            + Adicionar
                        </button>
                    </div>
                    <div v-for="(email, index) in form.emails" :key="index" class="flex gap-2">
                        <TextInput v-model="email.address" type="email" placeholder="Endereço de e-mail"
                            class="flex-1 transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        <select v-model="email.type" 
                            class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="Principal">Principal</option>
                            <option value="Secundário">Secundário</option>
                            <option value="Comercial">Comercial</option>
                        </select>
                        <button type="button" @click="removeEmail(index)"
                            class="px-3 py-2 text-red-600 hover:text-red-800">
                            Remover
                        </button>
                    </div>
                </div>

                <!-- Valores -->
                <div class="space-y-4">
                    <h4 class="text-lg font-medium text-gray-900 border-b pb-2">Valores</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="valor_identificacao_genetica" value="Valor Identificação genética (BOVINOS)" class="text-sm font-medium text-gray-700" />
                            <TextInput id="valor_identificacao_genetica" v-model="form.valor_identificacao_genetica" type="text"
                                class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="R$ 0,00" />
                        </div>
                        <div>
                            <InputLabel for="valor_beta_caseina" value="Valor Beta caseína (BOVINOS)" class="text-sm font-medium text-gray-700" />
                            <TextInput id="valor_beta_caseina" v-model="form.valor_beta_caseina" type="text"
                                class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="R$ 0,00" />
                        </div>
                        <div>
                            <InputLabel for="valor_kappa_caseina" value="Valor Kappa caseína (BOVINOS)" class="text-sm font-medium text-gray-700" />
                            <TextInput id="valor_kappa_caseina" v-model="form.valor_kappa_caseina" type="text"
                                class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="R$ 0,00" />
                        </div>
                        <div>
                            <InputLabel for="valor_identificacao_genetica_bovinos" value="Valor Identificação genética (BOVINOS)" class="text-sm font-medium text-gray-700" />
                            <TextInput id="valor_identificacao_genetica_bovinos" v-model="form.valor_identificacao_genetica_bovinos" type="text"
                                class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="R$ 0,00" />
                        </div>
                        <div>
                            <InputLabel for="valor_identificacao_genetica_caprinos" value="Valor Identificação genética (CAPRINOS)" class="text-sm font-medium text-gray-700" />
                            <TextInput id="valor_identificacao_genetica_caprinos" v-model="form.valor_identificacao_genetica_caprinos" type="text"
                                class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="R$ 0,00" />
                        </div>
                        <div>
                            <InputLabel for="valor_identificacao_genetica_ovinos" value="Valor Identificação genética (OVINOS)" class="text-sm font-medium text-gray-700" />
                            <TextInput id="valor_identificacao_genetica_ovinos" v-model="form.valor_identificacao_genetica_ovinos" type="text"
                                class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="R$ 0,00" />
                        </div>
                        <div>
                            <InputLabel for="valor_hvp" value="Valor HVP (BOVINOS)" class="text-sm font-medium text-gray-700" />
                            <TextInput id="valor_hvp" v-model="form.valor_hvp" type="text"
                                class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="R$ 0,00" />
                        </div>
                        <div>
                            <InputLabel for="valor_five_panel" value="Valor Five Panel - GBLV / HERDA / HY (BOVINOS)" class="text-sm font-medium text-gray-700" />
                            <TextInput id="valor_five_panel" v-model="form.valor_five_panel" type="text"
                                class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="R$ 0,00" />
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div class="space-y-2">
                    <InputLabel for="comments" value="Observações" class="text-sm font-medium text-gray-700" />
                    <textarea id="comments" v-model="form.comments" rows="3"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Digite observações adicionais"></textarea>
                    <InputError :message="form.errors.comments" class="mt-2" />
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <SecondaryButton @click="closeModal" type="button"
                        class="transition-all duration-200 hover:bg-gray-100">
                        Cancelar
                    </SecondaryButton>
                    <PrimaryButton :disabled="form.processing" type="submit"
                        class="transition-all duration-200 transform hover:scale-105">
                        <span v-if="form.processing" class="flex items-center">
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

const user = computed(() => usePage().props.auth.user)

const form = useForm({
    registration: Math.floor(Math.random() * 9000000000) + 1000000000, // Gerar número aleatório de 10 dígitos
    user_id: user.value?.id || 1, // ID do usuário autenticado
    name: '',
    rg: '',
    cpf: '',
    cnpj: '',
    address: '',
    adress_number: '',
    adress_complement: '',
    district: '',
    city: '',
    state: '',
    zip_code: '',
    state_registration: '',
    property_name: '',
    resp_financ: '',
    comments: '',
    valor_identificacao_genetica: '',
    valor_beta_caseina: '',
    valor_kappa_caseina: '',
    valor_identificacao_genetica_bovinos: '',
    valor_identificacao_genetica_caprinos: '',
    valor_identificacao_genetica_ovinos: '',
    valor_hvp: '',
    valor_five_panel: '',
    phones: [],
    emails: [],
    image: 'https://placehold.co/80x80',
    is_active: true
});

const isEditing = computed(() => props.owner !== null);

const submitForm = () => {
    console.log('Submitting form:', form.data());
    console.log('Is editing:', isEditing.value);
    console.log('Owner ID:', props.owner?.id);
    
    if (isEditing.value) {
        // Editar proprietário existente
        form.put(route('admin.owners.update', props.owner.id), {
            onSuccess: () => {
                console.log('Update successful');
                closeModal();
                form.reset();
                // Resetar arrays de telefones e emails
                form.phones = [];
                form.emails = [];
            },
            onError: (errors) => {
                console.log('Update errors:', errors);
            }
        });
    } else {
        // Criar novo proprietário
        form.post(route('admin.owners.store'), {
            onSuccess: () => {
                closeModal();
                form.reset();
                // Resetar arrays de telefones e emails
                form.phones = [];
                form.emails = [];
            },
            onError: (errors) => {
                console.log('Errors:', errors);
            }
        });
    }
}

const closeModal = () => {
    emits('close');
};

const addPhone = () => {
    form.phones.push({ number: '', type: 'WhatsApp' });
};

const removePhone = (index) => {
    form.phones.splice(index, 1);
};

const addEmail = () => {
    form.emails.push({ address: '', type: 'Principal' });
};

const removeEmail = (index) => {
    form.emails.splice(index, 1);
};

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    owner: {
        type: Object,
        default: null
    }
})

const emits = defineEmits(['close'])

// Observa quando o modal abre para gerar novo registration
watch(() => props.show, (newValue) => {
    if (newValue) {
        if (props.owner) {
            // Preencher formulário com dados do proprietário para edição
            form.user_id = props.owner.user_id || user.value?.id || 1;
            form.name = props.owner.name || '';
            form.rg = props.owner.rg || '';
            form.cpf = props.owner.cpf || '';
            form.cnpj = props.owner.cnpj || '';
            form.registration = props.owner.registration || '';
            form.address = props.owner.address || '';
            form.adress_number = props.owner.adress_number || '';
            form.adress_complement = props.owner.adress_complement || '';
            form.district = props.owner.district || '';
            form.city = props.owner.city || '';
            form.state = props.owner.state || '';
            form.zip_code = props.owner.zip_code || '';
            form.state_registration = props.owner.state_registration || '';
            form.property_name = props.owner.property_name || '';
            form.resp_financ = props.owner.resp_financ || '';
            form.comments = props.owner.comments || '';
            form.valor_identificacao_genetica = props.owner.valor_identificacao_genetica || '';
            form.valor_beta_caseina = props.owner.valor_beta_caseina || '';
            form.valor_kappa_caseina = props.owner.valor_kappa_caseina || '';
            form.valor_identificacao_genetica_bovinos = props.owner.valor_identificacao_genetica_bovinos || '';
            form.valor_identificacao_genetica_caprinos = props.owner.valor_identificacao_genetica_caprinos || '';
            form.valor_identificacao_genetica_ovinos = props.owner.valor_identificacao_genetica_ovinos || '';
            form.valor_hvp = props.owner.valor_hvp || '';
            form.valor_five_panel = props.owner.valor_five_panel || '';
            form.image = props.owner.image || 'https://placehold.co/80x80';
            form.is_active = props.owner.is_active !== undefined ? props.owner.is_active : true;
            
            // Preencher telefones e emails
            form.phones = props.owner.phones ? [...props.owner.phones] : [];
            form.emails = props.owner.emails ? [...props.owner.emails] : [];
        } else {
            // Novo proprietário - gerar registration aleatório
            form.registration = Math.floor(Math.random() * 9000000000) + 1000000000;
            form.user_id = user.value?.id || 1;
            // Resetar formulário
            form.reset();
            form.phones = [];
            form.emails = [];
            form.image = 'https://placehold.co/80x80';
            form.is_active = true;
        }
    }
});
</script>
