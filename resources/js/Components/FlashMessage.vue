<template>
    <div v-if="message" class="mb-4 p-4 rounded-md" :class="containerClasses">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg v-if="type === 'success'" class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <svg v-else-if="type === 'error'" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p :class="messageClasses">{{ message }}</p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button
                        @click="closeMessage"
                        :class="buttonClasses"
                        type="button"
                    >
                        <span class="sr-only">Fechar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    message: String,
    type: {
        type: String,
        default: 'success',
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    }
})

const emit = defineEmits(['close'])

const containerClasses = computed(() => {
    switch (props.type) {
        case 'success':
            return 'bg-green-50 border border-green-200'
        case 'error':
            return 'bg-red-50 border border-red-200'
        case 'warning':
            return 'bg-yellow-50 border border-yellow-200'
        case 'info':
            return 'bg-blue-50 border border-blue-200'
        default:
            return 'bg-green-50 border border-green-200'
    }
})

const messageClasses = computed(() => {
    const baseClasses = 'text-sm font-medium'
    switch (props.type) {
        case 'success':
            return `${baseClasses} text-green-800`
        case 'error':
            return `${baseClasses} text-red-800`
        case 'warning':
            return `${baseClasses} text-yellow-800`
        case 'info':
            return `${baseClasses} text-blue-800`
        default:
            return `${baseClasses} text-green-800`
    }
})

const buttonClasses = computed(() => {
    const baseClasses = 'inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2'
    switch (props.type) {
        case 'success':
            return `${baseClasses} text-green-500 hover:bg-green-100 focus:ring-green-600`
        case 'error':
            return `${baseClasses} text-red-500 hover:bg-red-100 focus:ring- d-600`
        case 'warning':
            return `${baseClasses} text-yellow-500 hover:bg-yellow-100 focus:ring-yellow-600`
        case 'info':
            return `${baseClasses} text-blue-500 hover:bg-blue-100 focus:ring-blue-600`
        default:
            return `${baseClasses} text-green-500 hover:bg-green-100 focus:ring-green-600`
    }
})

const closeMessage = () => {
    emit('close')
}
</script>
