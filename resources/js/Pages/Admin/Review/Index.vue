<template>
    <AppLayout title="Revisão de Resultados">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Revisão de Resultados Genéticos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Flash Messages -->
                <div v-if="$page.props.flash?.success" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.errors?.error" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ $page.props.errors.error }}
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Pendentes</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ stats.pending }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Aprovados</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ stats.approved }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Filtros</h3>
                        <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select v-model="form.status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos</option>
                                    <option value="pending">Pendente</option>
                                    <option value="approved">Aprovado</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Proprietário</label>
                                <select v-model="form.owner_id" @change="filterAnimals" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos</option>
                                    <option v-for="owner in owners" :key="owner.id" :value="owner.id">
                                        {{ owner.name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Animal</label>
                                <select v-model="form.animal_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos</option>
                                    <option v-for="animal in filteredAnimals" :key="animal.id" :value="animal.id">
                                        {{ animal.name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Marcador</label>
                                <input 
                                    v-model="form.search" 
                                    type="text" 
                                    placeholder="Nome do marcador..."
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </div>

                            <div class="flex items-end space-x-2">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    Filtrar
                                </button>
                                <button type="button" @click="clearFilters" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                    Limpar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div v-if="selectedResults.length > 0" class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">{{ selectedResults.length }} resultado(s) selecionado(s)</span>
                                <button @click="clearSelection" class="text-sm text-indigo-600 hover:text-indigo-800">
                                    Limpar seleção
                                </button>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button @click="bulkAction('pending')" class="px-3 py-1 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700">
                                    Marcar como Pendente
                                </button>
                                <button @click="bulkAction('approved')" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                    Aprovar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results Table -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input 
                                    type="checkbox" 
                                    @change="toggleSelectAll"
                                    :checked="allSelected"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amostra
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Proprietário/Animal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Resultados
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Revisado por
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="sample in results.data" :key="sample.sample_id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input 
                                            type="checkbox" 
                                            :value="sample.id"
                                            v-model="selectedResults"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            #{{ sample.sample.id }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ sample.sample.code || 'Sem código' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="getStatusClass(sample.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            {{ getStatusLabel(sample.status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ sample.sample.animal.owner.name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <Link 
                                                :href="route('admin.animals.show', sample.sample.animal.id)"
                                                class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200"
                                            >
                                                {{ sample.sample.animal.name }}
                                            </Link>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ sample.results_count }} resultado{{ sample.results_count !== 1 ? 's' : '' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div v-if="sample.reviewed_by">
                                            {{ sample.reviewed_by.name }}
                                        </div>
                                        <div v-else class="text-gray-400">-</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ sample.reviewed_at ? formatDate(sample.reviewed_at) : formatDate(sample.created_at) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <Link :href="route('admin.review.show-sample', sample.id)" class="text-indigo-600 hover:text-indigo-900">
                                                Ver
                                            </Link>
                                            <button v-if="sample.status === 'approved'" @click="quickAction(sample.id, 'pending')" class="text-yellow-600 hover:text-yellow-900">
                                                Marcar Pendente
                                            </button>
                                            <button v-if="sample.status === 'pending'" @click="quickAction(sample.id, 'approved')" class="text-green-600 hover:text-green-900">
                                                Aprovar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <Pagination :meta="results" />
                </div>
            </div>
        </div>

        <!-- Bulk Action Modal -->
        <div v-if="showBulkModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ getBulkActionTitle(bulkActionType) }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Você está prestes a {{ getBulkActionDescription(bulkActionType) }} {{ selectedResults.length }} resultado(s).
                    </p>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Observações (opcional)</label>
                        <textarea 
                            v-model="bulkNotes" 
                            rows="3" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Adicione observações sobre esta ação..."
                        ></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button @click="closeBulkModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button @click="confirmBulkAction" :class="getBulkActionButtonClass(bulkActionType)" class="px-4 py-2 text-white rounded-md">
                            Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Details Modal -->
        <div v-if="showSampleModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeSampleModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                        <span class="text-2xl">🧬</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-white" id="modal-title">
                                        Detalhes da Amostra #{{ selectedSample?.sample?.id }}
                                    </h3>
                                    <p class="text-indigo-100 text-sm">
                                        {{ selectedSample?.sample?.code }} - {{ selectedSample?.sample?.animal?.name }}
                                    </p>
                                </div>
                            </div>
                            <button @click="closeSampleModal" class="text-white hover:text-gray-200">
                                <XMarkIcon class="h-6 w-6" />
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-4 max-h-96 overflow-y-auto">
                        <!-- Sample Info -->
                        <div class="mb-6 bg-gray-50 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Informações da Amostra</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Proprietário:</span>
                                    <p class="text-sm text-gray-900">{{ selectedSample?.sample?.animal?.owner?.name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Animal:</span>
                                    <p class="text-sm text-gray-900">
                                        <Link 
                                            :href="route('admin.animals.show', selectedSample?.sample?.animal?.id)"
                                            class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200"
                                        >
                                            {{ selectedSample?.sample?.animal?.name }}
                                        </Link>
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Status da Amostra:</span>
                                    <span :class="{
                                        'bg-yellow-100 text-yellow-800': selectedSample?.status === 'pending',
                                        'bg-blue-100 text-blue-800': selectedSample?.status === 'under_review',
                                        'bg-green-100 text-green-800': selectedSample?.status === 'approved',
                                        'bg-red-100 text-red-800': selectedSample?.status === 'rejected'
                                    }" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ getStatusLabel(selectedSample?.status) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Total de Resultados:</span>
                                    <p class="text-sm text-gray-900">{{ selectedSample?.results_count }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Results Table -->
                        <div v-if="sampleResults.length > 0">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Todos os Resultados Genéticos</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Marcador
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Genótipo
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Alelos
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Revisado por
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Data
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Observações
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="result in sampleResults" :key="result.id" class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ result.marker.name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ result.genotype }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="flex space-x-1">
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ result.allele_1 }}
                                                    </span>
                                                    <span class="text-gray-400">/</span>
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                        {{ result.allele_2 }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span :class="{
                                                    'bg-yellow-100 text-yellow-800': result.status === 'pending',
                                                    'bg-blue-100 text-blue-800': result.status === 'under_review',
                                                    'bg-green-100 text-green-800': result.status === 'approved',
                                                    'bg-red-100 text-red-800': result.status === 'rejected'
                                                }" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                                    {{ getStatusLabel(result.status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ result.reviewer ? result.reviewer.name : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ result.reviewed_at ? formatDate(result.reviewed_at) : formatDate(result.created_at) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                                                <div class="truncate" :title="result.review_notes">
                                                    {{ result.review_notes || '-' }}
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Loading state -->
                        <div v-else-if="loadingSampleResults" class="text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                            <p class="mt-2 text-sm text-gray-500">Carregando resultados...</p>
                        </div>

                        <!-- No results -->
                        <div v-else class="text-center py-8">
                            <p class="text-gray-500">Nenhum resultado encontrado para esta amostra.</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-3 flex justify-end">
                        <button @click="closeSampleModal" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import { ClockIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    results: Object,
    filters: Object,
    owners: Array,
    animals: Array,
    stats: Object,
})

// Form data
const form = ref({
    status: props.filters.status || '',
    owner_id: props.filters.owner_id || '',
    animal_id: props.filters.animal_id || '',
    search: props.filters.search || '',
})

// Selection
const selectedResults = ref([])
const showBulkModal = ref(false)
const bulkActionType = ref('')
const bulkNotes = ref('')

// Sample details modal
const showSampleModal = ref(false)
const selectedSample = ref(null)
const sampleResults = ref([])
const loadingSampleResults = ref(false)

// Computed
const allSelected = computed(() => {
    return props.results.data.length > 0 && selectedResults.value.length === props.results.data.length
})

const filteredAnimals = computed(() => {
    if (!form.value.owner_id) return props.animals
    return props.animals.filter(animal => animal.owner_id == form.value.owner_id)
})

// Methods
const applyFilters = () => {
    router.get(route('admin.review.index'), form.value, {
        preserveState: true,
        preserveScroll: true,
    })
}

const clearFilters = () => {
    form.value = {
        status: '',
        owner_id: '',
        animal_id: '',
        search: '',
    }
    applyFilters()
}

const filterAnimals = () => {
    form.value.animal_id = ''
}

const toggleSelectAll = () => {
    if (allSelected.value) {
        selectedResults.value = []
    } else {
        selectedResults.value = props.results.data.map(result => result.id)
    }
}

const clearSelection = () => {
    selectedResults.value = []
}

const bulkAction = (action) => {
    bulkActionType.value = action
    showBulkModal.value = true
}

const closeBulkModal = () => {
    showBulkModal.value = false
    bulkActionType.value = ''
    bulkNotes.value = ''
}

const confirmBulkAction = () => {
    router.post(route('admin.review.bulk-update'), {
        sample_ids: selectedResults.value,
        status: bulkActionType.value,
        review_notes: bulkNotes.value,
    }, {
        onSuccess: () => {
            selectedResults.value = []
            closeBulkModal()
        }
    })
}

const quickAction = (sampleId, action) => {
    const actionLabel = action === 'approved' ? 'aprovar' : 'marcar como pendente';
    const confirmMessage = `Tem certeza que deseja ${actionLabel} esta amostra?`;
    
    if (confirm(confirmMessage)) {
        router.post(route('admin.review.update-status', sampleId), {
            status: action,
        });
    }
}

const getStatusLabel = (status) => {
    const labels = {
        pending: 'Pendente',
        under_review: 'Em Revisão',
        approved: 'Aprovado',
        rejected: 'Rejeitado',
    }
    return labels[status] || status
}

const getStatusClass = (status) => {
    const classes = {
        pending: 'bg-yellow-100 text-yellow-800',
        under_review: 'bg-blue-100 text-blue-800',
        approved: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800',
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const getBulkActionTitle = (action) => {
    const titles = {
        pending: 'Marcar como Pendente',
        approved: 'Aprovar Amostras',
    }
    return titles[action] || action
}

const getBulkActionDescription = (action) => {
    const descriptions = {
        pending: 'marcar como pendente',
        approved: 'aprovar',
    }
    return descriptions[action] || action
}

const getBulkActionButtonClass = (action) => {
    const classes = {
        under_review: 'bg-blue-600 hover:bg-blue-700',
        approved: 'bg-green-600 hover:bg-green-700',
        rejected: 'bg-red-600 hover:bg-red-700',
    }
    return classes[action] || 'bg-gray-600 hover:bg-gray-700'
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

// Sample details modal functions
const viewSampleDetails = async (sample) => {
    selectedSample.value = sample
    showSampleModal.value = true
    await fetchSampleResults(sample.sample_id)
}

const closeSampleModal = () => {
    showSampleModal.value = false
    selectedSample.value = null
    sampleResults.value = []
}

const fetchSampleResults = async (sampleId) => {
    loadingSampleResults.value = true
    try {
        const response = await fetch(`/admin/review/sample/${sampleId}/results`)
        if (response.ok) {
            const data = await response.json()
            sampleResults.value = data.results || []
        } else {
            console.error('Erro ao buscar resultados da amostra')
            sampleResults.value = []
        }
    } catch (error) {
        console.error('Erro ao buscar resultados da amostra:', error)
        sampleResults.value = []
    } finally {
        loadingSampleResults.value = false
    }
}
</script>