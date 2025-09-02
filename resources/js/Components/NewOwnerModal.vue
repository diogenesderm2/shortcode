<script setup>
import Modal from './Modal.vue';


const props = defineProps({
    show: {
        type: Boolean,
        default: false
    }
})

const emits = defineEmits(['close'])

const closeModal = () => {
    emits('close');

};
</script>


<template>
    <Modal :show="show" @close="closeModal" max-width="md">
        <h1>teste</h1>
    </Modal>
</template>
