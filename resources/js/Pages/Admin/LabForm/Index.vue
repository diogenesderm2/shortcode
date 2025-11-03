<template>
    <AppLayout title="Formulários de Laboratório">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Formulários de Laboratório
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Filtros -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Buscar
                                </label>
                                <input
                                    v-model="filters.search"
                                    type="text"
                                    placeholder="Número do formulário ou criador..."
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    @input="search"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Data Inicial
                                </label>
                                <input
                                    v-model="filters.date_from"
                                    type="date"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    @change="search"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Data Final
                                </label>
                                <input
                                    v-model="filters.date_to"
                                    type="date"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    @change="search"
                                >
                            </div>
                            <div class="flex items-end">
                                <button
                                    @click="clearFilters"
                                    class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    Limpar Filtros
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Formulários -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Número do Formulário
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Criado por
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Data de Geração
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amostras
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="form in forms.data" :key="form.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ form.form_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ form.creator?.name || 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ formatDate(form.generated_at) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ form.form_data?.metadata?.total_samples || 0 }} amostras
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <Link
                                            :href="route('admin.lab-forms.show', form.id)"
                                            class="text-indigo-600 hover:text-indigo-900"
                                        >
                                            👁️ Visualizar
                                        </Link>
                                        <button
                                            @click="confirmDelete(form)"
                                            class="text-red-600 hover:text-red-900 ml-2"
                                        >
                                            🗑️ Excluir
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        <Pagination :links="forms.links" />
                    </div>

                    <!-- Mensagem quando não há formulários -->
                    <div v-if="forms.data.length === 0" class="text-center py-12">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum formulário encontrado</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Não há formulários salvos com os filtros aplicados.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmação de Exclusão -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Confirmar Exclusão
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    Tem certeza que deseja excluir o formulário <strong>{{ formToDelete?.form_number }}</strong>?
                    Esta ação não pode ser desfeita.
                </p>
                <div class="flex justify-end space-x-3">
                    <SecondaryButton @click="closeDeleteModal" :disabled="isDeleting">
                        Cancelar
                    </SecondaryButton>
                    <DangerButton @click="deleteForm" :disabled="isDeleting">
                        <span v-if="isDeleting">Excluindo...</span>
                        <span v-else">Sim, Excluir</span>
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import Modal from '@/Components/Modal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'
import { debounce } from 'lodash'

const props = defineProps({
    forms: Object,
    filters: Object
})

// Filtros
const filters = reactive({
    search: props.filters.search || '',
    date_from: props.filters.date_from || '',
    date_to: props.filters.date_to || ''
})

// Modal de exclusão
const showDeleteModal = ref(false)
const formToDelete = ref(null)
const isDeleting = ref(false)

// Busca com debounce
const search = debounce(() => {
    router.get(route('admin.lab-forms.index'), filters, {
        preserveState: true,
        replace: true
    })
}, 300)

// Limpar filtros
const clearFilters = () => {
    filters.search = ''
    filters.date_from = ''
    filters.date_to = ''
    search()
}

// Formatação de data
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString('pt-BR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    })
}

// Confirmação de exclusão
const confirmDelete = (form) => {
    formToDelete.value = form
    showDeleteModal.value = true
}

const closeDeleteModal = () => {
    showDeleteModal.value = false
    formToDelete.value = null
    isDeleting.value = false
}

// Excluir formulário
const deleteForm = async () => {
    if (!formToDelete.value) return

    isDeleting.value = true

    try {
        await router.delete(route('admin.lab-forms.destroy', formToDelete.value.id), {
            onSuccess: () => {
                closeDeleteModal()
                // A página será recarregada automaticamente
            },
            onError: () => {
                isDeleting.value = false
                alert('Erro ao excluir formulário. Tente novamente.')
            }
        })
    } catch (error) {
        isDeleting.value = false
        alert('Erro ao excluir formulário. Tente novamente.')
    }
}
</script>