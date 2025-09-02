<template>
    <Modal :show="show" @close="closeModal" max-width="md">
        <h1>teste</h1>
    </Modal>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';


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
