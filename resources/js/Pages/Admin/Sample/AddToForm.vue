<template>
    <AppLayout title="Adicionar Amostras ao Formulário">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Adicionar Amostras ao Formulário
                </h2>
                <div class="text-sm text-gray-600">
                    Formulário Nº {{ formNumber }}
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Search Section -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Buscar Amostra</h3>
                                <p class="text-sm text-gray-600">Digite o ID da amostra para adicioná-la ao formulário</p>
                            </div>
                        </div>

                        <form @submit.prevent="searchSample" class="space-y-4">
                            <div class="flex space-x-4">
                                <div class="flex-1">
                                    <label for="sample_code" class="block text-sm font-medium text-gray-700 mb-2">
                                        ID da Amostra
                                    </label>
                                    <div class="relative">
                                        <input
                                            id="sample_code"
                                            v-model="searchForm.sample_code"
                                            type="number"
                                            placeholder="Ex: 1, 2, 3..."
                                            class="block w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                                            :disabled="loading"
                                        >
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg v-if="loading" class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                                    <button
                                        type="submit"
                                        :disabled="!searchForm.sample_code || loading"
                                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        <span v-if="loading">Buscando...</span>
                                        <span v-else>Buscar</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Error Message -->
                        <div v-if="errorMessage" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-red-800">{{ errorMessage }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sample Details -->
                <div v-if="foundSample" class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Amostra Encontrada</h3>
                                    <p class="text-sm text-gray-600">Código: {{ foundSample.sample_code }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <button
                                    @click="addToFormList"
                                    :disabled="isAlreadyAdded"
                                    class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                >
                                    <span v-if="isAlreadyAdded">Já Adicionada</span>
                                    <span v-else>Adicionar ao Formulário</span>
                                </button>
                            </div>
                        </div>

                        <!-- Sample Information Card -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Basic Info -->
                                <div class="space-y-4">
                                    <h4 class="font-semibold text-gray-900 flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Informações Básicas
                                    </h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Tipo:</span>
                                            <span class="font-medium">{{ formatSampleType(foundSample.sample_type) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Status:</span>
                                            <span class="px-2 py-1 text-xs rounded-full" :class="getStatusClass(foundSample.status)">
                                                {{ formatStatus(foundSample.status) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Data de Coleta:</span>
                                            <span class="font-medium">{{ formatDate(foundSample.collection_date) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Owner Info -->
                                <div class="space-y-4">
                                    <h4 class="font-semibold text-gray-900 flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Proprietário
                                    </h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Nome:</span>
                                            <span class="font-medium">{{ foundSample.owner?.name || 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Propriedade:</span>
                                            <span class="font-medium">{{ foundSample.owner?.property_name || 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Animal Info -->
                                <div class="space-y-4">
                                    <h4 class="font-semibold text-gray-900 flex items-center">
                                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        Animal Filho
                                    </h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Nome:</span>
                                            <span class="font-medium">{{ foundSample.child?.name || 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">RG:</span>
                                            <span class="font-medium">{{ foundSample.child?.rg || 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Sexo:</span>
                                            <span class="font-medium">{{ formatSex(foundSample.child?.sex) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Parents Info -->
                            <div v-if="foundSample.father || foundSample.mother" class="mt-6 pt-6 border-t border-blue-200">
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Informações dos Pais
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div v-if="foundSample.father" class="bg-white p-4 rounded-lg border border-blue-100">
                                        <h5 class="font-medium text-gray-900 mb-2">Pai</h5>
                                        <div class="space-y-1 text-sm">
                                            <div><span class="text-gray-600">Nome:</span> {{ foundSample.father.name }}</div>
                                            <div><span class="text-gray-600">RG:</span> {{ foundSample.father.rg }}</div>
                                        </div>
                                    </div>
                                    <div v-if="foundSample.mother" class="bg-white p-4 rounded-lg border border-blue-100">
                                        <h5 class="font-medium text-gray-900 mb-2">Mãe</h5>
                                        <div class="space-y-1 text-sm">
                                            <div><span class="text-gray-600">Nome:</span> {{ foundSample.mother.name }}</div>
                                            <div><span class="text-gray-600">RG:</span> {{ foundSample.mother.rg }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Observations -->
                            <div v-if="foundSample.observations" class="mt-6 pt-6 border-t border-blue-200">
                                <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                                    <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Observações
                                </h4>
                                <p class="text-gray-700 bg-white p-3 rounded-lg border border-blue-100">{{ foundSample.observations }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Samples List -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Amostras no Formulário</h3>
                                    <p class="text-sm text-gray-600">{{ formSamples.length }} amostra(s) adicionada(s)</p>
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <button
                                    @click="clearForm"
                                    :disabled="formSamples.length === 0"
                                    class="px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                >
                                    Limpar Formulário
                                </button>
                                <button
                                    @click="generateForm"
                                    :disabled="formSamples.length === 0"
                                    class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                >
                                    Gerar Formulário
                                </button>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-if="formSamples.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma amostra adicionada</h3>
                            <p class="mt-1 text-sm text-gray-500">Busque e adicione amostras para criar o formulário.</p>
                        </div>

                        <!-- Samples Grid -->
                        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div
                                v-for="(sample, index) in formSamples"
                                :key="sample.id"
                                class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow"
                            >
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-sm font-medium text-blue-600">
                                            {{ index + 1 }}
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="font-medium text-gray-900">{{ sample.sample_code }}</h4>
                                            <p class="text-xs text-gray-500">{{ formatSampleType(sample.sample_type) }}</p>
                                        </div>
                                    </div>
                                    <button
                                        @click="removeSample(index)"
                                        class="text-red-400 hover:text-red-600 transition-colors"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Animal:</span>
                                        <span class="font-medium">{{ sample.child?.name || 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Proprietário:</span>
                                        <span class="font-medium">{{ sample.owner?.name || 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="px-2 py-1 text-xs rounded-full" :class="getStatusClass(sample.status)">
                                            {{ formatStatus(sample.status) }}
                                        </span>
                                    </div>
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
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

// Reactive data
const searchForm = ref({
    sample_code: null
})

const foundSample = ref(null)
const formSamples = ref([])
const loading = ref(false)
const errorMessage = ref('')
const formNumber = ref('993184')

// Computed properties
const isAlreadyAdded = computed(() => {
    if (!foundSample.value) return false
    return formSamples.value.some(sample => sample.id === foundSample.value.id)
})

// Methods
const searchSample = async () => {
    console.log('🔍 FUNÇÃO SEARCHSAMPLE CHAMADA!')
    console.log('searchSample called with:', searchForm.value.sample_code)
    
    // Converter para string e verificar se não está vazio
    const sampleCode = String(searchForm.value.sample_code || '').trim()
    
    if (!sampleCode) {
        console.log('Empty sample code, returning')
        errorMessage.value = 'Por favor, digite um ID de amostra'
        return
    }

    loading.value = true
    errorMessage.value = ''
    foundSample.value = null

    try {
        console.log('Making request to:', route('admin.samples.search-by-code'))
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        console.log('CSRF Token:', csrfToken)
        
        const response = await fetch(route('admin.samples.search-by-code'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                sample_code: sampleCode
            })
        })

        console.log('Response status:', response.status)
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        
        const data = await response.json()
        console.log('Response data:', data)

        if (data.success) {
            foundSample.value = data.sample
            searchForm.value.sample_code = null // Limpar campo number corretamente
        } else {
            errorMessage.value = data.message || 'Amostra não encontrada'
        }
    } catch (error) {
        errorMessage.value = 'Erro ao buscar amostra. Tente novamente.'
        console.error('Error searching sample:', error)
    } finally {
        loading.value = false
    }
}

const addToFormList = () => {
    if (foundSample.value && !isAlreadyAdded.value) {
        formSamples.value.push({ ...foundSample.value })
        foundSample.value = null
        errorMessage.value = ''
    }
}

const removeSample = (index) => {
    formSamples.value.splice(index, 1)
}

const clearForm = () => {
    formSamples.value = []
    foundSample.value = null
    errorMessage.value = ''
}

const generateForm = async () => {
    if (formSamples.value.length === 0) return

    try {
        const response = await fetch(route('admin.samples.generate-form'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                samples: formSamples.value,
                form_number: formNumber.value
            })
        })

        if (response.ok) {
            // Redirecionar para a página do formulário gerado
            router.post(route('admin.samples.generate-form'), {
                samples: formSamples.value,
                form_number: formNumber.value
            })
        } else {
            alert('Erro ao gerar formulário. Tente novamente.')
        }
    } catch (error) {
        console.error('Error generating form:', error)
        alert('Erro ao gerar formulário. Tente novamente.')
    }
}

// Utility functions
const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('pt-BR')
}

const formatSampleType = (type) => {
    const types = {
        'sangue': 'Sangue',
        'pelo': 'Pelo',
        'saliva': 'Saliva'
    }
    return types[type] || type
}

const formatStatus = (status) => {
    const statuses = {
        'pendente': 'Pendente',
        'processando': 'Em Processamento',
        'concluido': 'Concluído'
    }
    return statuses[status] || status
}

const formatSex = (sex) => {
    if (!sex) return 'N/A'
    return sex === 'macho' ? 'Macho' : 'Fêmea'
}

const getStatusClass = (status) => {
    const classes = {
        'pendente': 'bg-yellow-100 text-yellow-800',
        'processando': 'bg-blue-100 text-blue-800',
        'concluido': 'bg-green-100 text-green-800'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

// Focus input on mount
onMounted(() => {
    const input = document.getElementById('sample_code')
    if (input) {
        input.focus()
    }
})
</script>