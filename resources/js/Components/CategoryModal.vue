<template>
    <Modal :show="show" @close="closeModal" max-width="md">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ isEditing ? 'Editar Categoria' : 'Nova Categoria' }}
                </h3>
                <button
                    @click="closeModal"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200 p-1 rounded-full hover:bg-gray-100"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="submitForm" class="space-y-6">
                <div class="space-y-2">
                    <InputLabel for="name" value="Nome da Categoria" class="text-sm font-medium text-gray-700" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                        autofocus
                        placeholder="Digite o nome da categoria"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <div class="space-y-2">
                    <InputLabel for="description" value="Descrição" class="text-sm font-medium text-gray-700" />
                    <textarea
                        id="description"
                        v-model="form.description"
                        class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200 resize-none"
                        rows="3"
                        placeholder="Digite uma descrição para a categoria"
                    ></textarea>
                    <InputError :message="form.errors.description" class="mt-2" />
                </div>

                <div class="space-y-2">
                    <InputLabel for="slug" value="Slug" class="text-sm font-medium text-gray-700" />
                    <TextInput
                        id="slug"
                        v-model="form.slug"
                        type="text"
                        class="mt-1 block w-full transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="slug-da-categoria"
                    />
                    <InputError :message="form.errors.slug" class="mt-2" />
                </div>

                <div class="flex items-center space-x-3">
                    <input
                        id="is_active"
                        v-model="form.is_active"
                        type="checkbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-all duration-200"
                    />
                    <InputLabel for="is_active" value="Categoria Ativa" class="text-sm font-medium text-gray-700" />
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <SecondaryButton
                        @click="closeModal"
                        type="button"
                        class="transition-all duration-200 hover:bg-gray-100"
                    >
                        Cancelar
                    </SecondaryButton>
                    <PrimaryButton
                        :disabled="form.processing"
                        type="submit"
                        class="transition-all duration-200 transform hover:scale-105"
                    >
                        <span v-if="form.processing" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    category: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close', 'saved']);

const isEditing = ref(false);

const form = useForm({
    name: '',
    description: '',
    slug: '',
    is_active: true
});

// Função para resetar o formulário
const resetForm = () => {
    form.reset();
    form.name = '';
    form.description = '';
    form.slug = '';
    form.is_active = true;
    isEditing.value = false;
};

// Observar mudanças na categoria para modo de edição
watch(() => props.category, (newCategory) => {
    if (newCategory) {
        isEditing.value = true;
        form.name = newCategory.name;
        form.description = newCategory.description || '';
        form.slug = newCategory.slug || '';
        form.is_active = newCategory.is_active !== false;
    } else {
        isEditing.value = false;
        resetForm();
    }
}, { immediate: true });

// Observar mudanças no show para resetar formulário
watch(() => props.show, (show) => {
    if (show && !props.category) {
        resetForm();
    }
});

const closeModal = () => {
    emit('close');
    resetForm();
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(route('admin.categorias.update', props.category.id), {
            onSuccess: () => {
                emit('saved');
                closeModal();
            }
        });
    } else {
        form.post(route('admin.categorias.store'), {
            onSuccess: () => {
                emit('saved');
                closeModal();
            }
        });
    }
};

// Auto-gerar slug baseado no nome
watch(() => form.name, (newName) => {
    if (newName && !isEditing.value) {
        form.slug = newName
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
    }
});
</script>
