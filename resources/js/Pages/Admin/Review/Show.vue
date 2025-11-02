<template>
    <AppLayout title="Revisar Resultado">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Revisar Resultado Genético
                </h2>
                <Link :href="route('admin.review.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                ← Voltar
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Result Card -->
                    <div class="lg:col-span-2">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-medium text-gray-900">Detalhes do Resultado</h3>
                                    <span :class="getStatusClass(result.status)"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                                        {{ getStatusLabel(result.status) }}
                                    </span>
                                </div>

                                <!-- Animal and Owner Info -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">
                                            Proprietário
                                        </h4>
                                        <p class="text-lg font-medium text-gray-900">{{ result.sample.animal.owner?.name || 'Proprietário não informado' }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">
                                            Animal</h4>
                                        <p class="text-lg font-medium text-gray-900">
                                            <Link 
                                                v-if="result.sample.animal"
                                                :href="route('admin.animals.show', result.sample.animal.id)"
                                                class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200"
                                            >
                                                {{ result.sample.animal.name || 'Animal não informado' }}
                                            </Link>
                                            <span v-else class="text-gray-500">Animal não informado</span>
                                        </p>
                                        <p class="text-sm text-gray-500">Registro: {{ result.sample.animal?.register || 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">
                                            Amostra</h4>
                                        <p class="text-lg font-medium text-gray-900 flex items-center">
                                            <span class="mr-2">🧬</span>
                                            {{ result.sample.id }}
                                        </p>
                                        <p class="text-sm text-gray-500">Código: {{ result.sample.code || 'N/A' }}</p>
                                    </div>
                                </div>

                                <!-- Genetic Data -->
                                <div class="border-t border-gray-200 pt-6">
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Dados
                                        Genéticos
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Marcador</label>
                                            <p class="text-lg font-medium text-gray-900">{{ result.marker.name }}</p>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1">Cromossomo</label>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                {{ result.marker.chromosome }}
                                            </span>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Posição</label>
                                            <p class="text-sm text-gray-900">{{ result.marker.position?.toLocaleString()
                                            }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Alelos de
                                                Referência</label>
                                            <div class="flex space-x-2">
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    REF: {{ result.marker.ref_allele }}
                                                </span>
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    ALT: {{ result.marker.alt_allele }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Genótipo
                                            Observado</label>
                                        <div class="flex space-x-1">
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded text-lg font-medium bg-green-100 text-green-800">
                                                {{ result.allele_1 }}
                                            </span>
                                            <span class="text-gray-400 text-lg">/</span>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded text-lg font-medium bg-green-100 text-green-800">
                                                {{ result.allele_2 }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Genótipo completo: {{ result.genotype }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Review History -->
                                <div v-if="result.reviewed_by || result.review_notes"
                                    class="border-t border-gray-200 pt-6 mt-6">
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">
                                        Histórico de
                                        Revisão</h4>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div v-if="result.reviewer" class="flex items-center mb-2">
                                            <span class="text-sm font-medium text-gray-900">Revisado por:</span>
                                            <span class="ml-2 text-sm text-gray-600">{{ result.reviewer.name }}</span>
                                        </div>
                                        <div v-if="result.reviewed_at" class="flex items-center mb-2">
                                            <span class="text-sm font-medium text-gray-900">Data da revisão:</span>
                                            <span class="ml-2 text-sm text-gray-600">{{ formatDate(result.reviewed_at)
                                            }}</span>
                                        </div>
                                        <div v-if="result.review_notes">
                                            <span class="text-sm font-medium text-gray-900">Observações:</span>
                                            <p class="mt-1 text-sm text-gray-600">{{ result.review_notes }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Panel -->
                    <div class="lg:col-span-1">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Ações de Revisão</h3>

                                <form @submit.prevent="submitReview">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nova Ação</label>
                                        <select v-model="reviewForm.status"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Selecione uma ação</option>
                                            <option v-if="result.status === 'pending'" value="under_review">Colocar em
                                                Revisão
                                            </option>
                                            <option v-if="['pending', 'under_review'].includes(result.status)"
                                                value="approved">
                                                Aprovar</option>
                                            <option v-if="['pending', 'under_review'].includes(result.status)"
                                                value="rejected">
                                                Rejeitar</option>
                                            <!-- Debug: Mostrar sempre as opções se não houver nenhuma disponível -->
                                            <template v-if="!hasAvailableActions">
                                                <option value="under_review">Colocar em Revisão</option>
                                                <option value="approved">Aprovar</option>
                                                <option value="rejected">Rejeitar</option>
                                            </template>
                                        </select>
                                        <!-- Debug info -->
                                        <div class="mt-2 text-xs text-gray-500">
                                            Status atual: {{ result.status }}
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                                        <textarea v-model="reviewForm.review_notes" rows="4"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Adicione observações sobre esta revisão..."></textarea>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Métricas de
                                            Qualidade
                                            (opcional)</label>
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-600">Confiabilidade</span>
                                                <select v-model="qualityMetrics.confidence"
                                                    class="text-sm border-gray-300 rounded">
                                                    <option value="">-</option>
                                                    <option value="high">Alta</option>
                                                    <option value="medium">Média</option>
                                                    <option value="low">Baixa</option>
                                                </select>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-600">Qualidade da Amostra</span>
                                                <select v-model="qualityMetrics.sample_quality"
                                                    class="text-sm border-gray-300 rounded">
                                                    <option value="">-</option>
                                                    <option value="excellent">Excelente</option>
                                                    <option value="good">Boa</option>
                                                    <option value="fair">Regular</option>
                                                    <option value="poor">Ruim</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" :disabled="!reviewForm.status"
                                        :class="getActionButtonClass(reviewForm.status)"
                                        class="w-full px-4 py-2 text-white rounded-md font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                                        {{ getActionButtonText(reviewForm.status) }}
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Related Results - Full Width -->
                <div v-if="relatedResults.length > 0" class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-6">
                    <div class="p-6">

                        <h3 class="text-lg font-medium text-gray-900 mb-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="mr-2">🧬</span>
                                Outros Resultados da Mesma Amostra
                                <span class="ml-2 text-sm text-gray-500">({{ relatedResults.length }} resultado{{
                                    relatedResults.length !== 1 ? 's' : '' }})</span>
                            </div>
                        </h3>

                        <!-- Bulk Actions -->
                        <div v-if="selectedResults.length > 0" class="flex items-center space-x-3">
                            <span class="text-sm text-gray-600">{{ selectedResults.length }} selecionado{{
                                selectedResults.length !== 1 ? 's' : '' }}</span>
                            <button @click="bulkApprove"
                                class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition">
                                ✓ Aprovar Selecionados
                            </button>
                            <button @click="bulkReject"
                                class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition">
                                ✗ Rejeitar Selecionados
                            </button>
                        </div>


                        <!-- Desktop Table View -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left">
                                            <input type="checkbox" :checked="selectAll" @change="toggleSelectAll"
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Marcador</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Genótipo</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Alelos</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Revisado por</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Data</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="related in relatedResults" :key="related.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" :value="related.id" v-model="selectedResults"
                                                :disabled="related.status === 'approved' || related.status === 'rejected'"
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 disabled:opacity-50">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ related.marker.name }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ related.marker.chromosome }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ related.genotype }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-1">
                                                <span v-for="(allele, index) in related.alleles?.split('') || []"
                                                    :key="index" :class="getAlleleClass(allele)"
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium">
                                                    {{ allele }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getStatusClass(related.status)"
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                                                {{ getStatusLabel(related.status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ related.reviewed_by?.name || '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ related.reviewed_at ? new
                                                Date(related.reviewed_at).toLocaleDateString('pt-BR') :
                                                new Date(related.created_at).toLocaleDateString('pt-BR') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <Link :href="route('admin.review.show', related.id)"
                                                class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                            👁️ Ver
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="md:hidden space-y-4">
                            <div v-for="related in relatedResults" :key="related.id"
                                class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" :value="related.id" v-model="selectedResults"
                                            :disabled="related.status === 'approved' || related.status === 'rejected'"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 disabled:opacity-50">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ related.marker.name }}</h4>
                                            <p class="text-xs text-gray-500">{{ related.marker.chromosome }}</p>
                                        </div>
                                    </div>
                                    <span :class="getStatusClass(related.status)"
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                                        {{ getStatusLabel(related.status) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <span class="text-xs text-gray-500">Genótipo:</span>
                                        <p class="text-sm font-medium text-gray-900">{{ related.genotype }}</p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500">Alelos:</span>
                                        <div class="flex space-x-1 mt-1">
                                            <span v-for="(allele, index) in related.alleles?.split('') || []"
                                                :key="index" :class="getAlleleClass(allele)"
                                                class="inline-flex items-center px-1 py-0.5 rounded text-xs font-medium">
                                                {{ allele }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="text-xs text-gray-500">
                                        <div>{{ related.reviewed_by?.name || 'Não revisado' }}</div>
                                        <div>{{ related.reviewed_at ? new
                                            Date(related.reviewed_at).toLocaleDateString('pt-BR')
                                            :
                                            new Date(related.created_at).toLocaleDateString('pt-BR') }}</div>
                                    </div>
                                    <Link :href="route('admin.review.show', related.id)"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium inline-flex items-center">
                                    👁️ Ver
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    result: Object,
    relatedResults: Array,
})

// Form data
const reviewForm = ref({
    status: '',
    review_notes: '',
})

const qualityMetrics = ref({
    confidence: '',
    sample_quality: '',
})

// Bulk selection data
const selectedResults = ref([])

// Computed properties
const selectAll = computed(() => {
    const selectableResults = props.relatedResults.filter(r => r.status !== 'approved' && r.status !== 'rejected')
    return selectableResults.length > 0 && selectedResults.value.length === selectableResults.length
})

// Methods
const submitReview = () => {
    const data = {
        ...reviewForm.value,
        quality_metrics: Object.keys(qualityMetrics.value).some(key => qualityMetrics.value[key])
            ? qualityMetrics.value
            : null
    }

    router.post(route('admin.review.update-status', props.result.id), data, {
        onSuccess: () => {
            reviewForm.value = { status: '', review_notes: '' }
            qualityMetrics.value = { confidence: '', sample_quality: '' }
        }
    })
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

const getActionButtonClass = (status) => {
    const classes = {
        under_review: 'bg-blue-600 hover:bg-blue-700',
        approved: 'bg-green-600 hover:bg-green-700',
        rejected: 'bg-red-600 hover:bg-red-700',
    }
    return classes[status] || 'bg-gray-600 hover:bg-gray-700'
}

const getActionButtonText = (status) => {
    const texts = {
        under_review: 'Colocar em Revisão',
        approved: 'Aprovar Resultado',
        rejected: 'Rejeitar Resultado',
    }
    return texts[status] || 'Selecione uma ação'
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

const getAlleleClass = (allele) => {
    const classes = {
        'A': 'bg-blue-100 text-blue-800',
        'T': 'bg-green-100 text-green-800',
        'C': 'bg-yellow-100 text-yellow-800',
        'G': 'bg-red-100 text-red-800',
        'I': 'bg-purple-100 text-purple-800',
        'D': 'bg-gray-100 text-gray-800',
    }
    return classes[allele?.toUpperCase()] || 'bg-gray-100 text-gray-800'
}

// Bulk selection functions
const toggleSelectAll = () => {
    const selectableResults = props.relatedResults.filter(r => r.status !== 'approved' && r.status !== 'rejected')

    if (selectAll.value) {
        selectedResults.value = []
    } else {
        selectedResults.value = selectableResults.map(r => r.id)
    }
}

const bulkApprove = () => {
    if (selectedResults.value.length === 0) return

    if (confirm(`Tem certeza que deseja aprovar ${selectedResults.value.length} resultado${selectedResults.value.length !== 1 ? 's' : ''}?`)) {
        router.post(route('admin.review.bulk-update'), {
            result_ids: selectedResults.value,
            status: 'approved',
            review_notes: 'Aprovação em lote'
        }, {
            onSuccess: () => {
                selectedResults.value = []
            }
        })
    }
}

const bulkReject = () => {
    if (selectedResults.value.length === 0) return

    if (confirm(`Tem certeza que deseja rejeitar ${selectedResults.value.length} resultado${selectedResults.value.length !== 1 ? 's' : ''}?`)) {
        router.post(route('admin.review.bulk-update'), {
            result_ids: selectedResults.value,
            status: 'rejected',
            review_notes: 'Rejeição em lote'
        }, {
            onSuccess: () => {
                selectedResults.value = []
            }
        })
    }
}
</script>