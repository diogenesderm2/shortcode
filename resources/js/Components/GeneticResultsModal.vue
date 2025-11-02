<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$emit('close')"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <span class="text-2xl">🧬</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-white" id="modal-title">
                                    Resultados Genéticos
                                </h3>
                                <p v-if="animal" class="text-sm text-purple-100">
                                    {{ animal.name }} ({{ animal.rg }})
                                </p>
                            </div>
                        </div>
                        <button @click="$emit('close')" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-4 max-h-96 overflow-y-auto">
                    <!-- Loading state -->
                    <div v-if="loading" class="flex items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
                        <span class="ml-3 text-gray-600">Carregando resultados genéticos...</span>
                    </div>

                    <!-- Error state -->
                    <div v-else-if="error" class="text-center py-12">
                        <div class="text-red-500 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Erro ao carregar dados</h3>
                        <p class="text-gray-600 mb-4">{{ error }}</p>
                        <button @click="fetchGeneticResults" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition">
                            Tentar novamente
                        </button>
                    </div>

                    <!-- No results state -->
                    <div v-else-if="!geneticResults || geneticResults.length === 0" class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum resultado encontrado</h3>
                        <p class="text-gray-600">Este animal ainda não possui resultados genéticos cadastrados.</p>
                    </div>

                    <!-- Results content -->
                    <div v-else>
                        <!-- Search and filters -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar marcador</label>
                                    <input 
                                        v-model="searchTerm" 
                                        type="text" 
                                        placeholder="Nome do marcador..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Cromossomo</label>
                                    <select 
                                        v-model="selectedChromosome" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    >
                                        <option value="">Todos</option>
                                        <option v-for="chr in availableChromosomes" :key="chr" :value="chr">
                                            {{ chr }}
                                        </option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <button @click="resetFilters" class="w-full px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                                        Limpar filtros
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Results summary -->
                        <div class="mb-4 flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                Mostrando {{ filteredResults.length }} de {{ geneticResults.length }} marcadores
                            </div>
                        </div>

                        <!-- Results table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Marcador
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cromossomo
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Posição
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Alelos
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amostra
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="result in filteredResults" :key="result.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ result.marker.name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ result.marker.chromosome }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ result.marker.position?.toLocaleString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-1">
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    {{ result.allele1 }}
                                                </span>
                                                <span class="text-gray-400">/</span>
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    {{ result.allele2 }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ result.sample.id }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-3 flex justify-end">
                    <button @click="$emit('close')" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    animal: {
        type: Object,
        default: null
    }
})

const emit = defineEmits(['close'])

// State
const loading = ref(false)
const error = ref(null)
const geneticResults = ref([])
const searchTerm = ref('')
const selectedChromosome = ref('')

// Computed properties
const availableChromosomes = computed(() => {
    if (!geneticResults.value) return []
    const chromosomes = [...new Set(geneticResults.value.map(result => result.marker.chromosome))]
    return chromosomes.sort((a, b) => {
        // Sort numerically for numeric chromosomes, then alphabetically
        const aNum = parseInt(a)
        const bNum = parseInt(b)
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return aNum - bNum
        }
        return a.localeCompare(b)
    })
})

const filteredResults = computed(() => {
    if (!geneticResults.value) return []
    
    let filtered = geneticResults.value
    
    // Filter by search term
    if (searchTerm.value) {
        const term = searchTerm.value.toLowerCase()
        filtered = filtered.filter(result => 
            result.marker.name.toLowerCase().includes(term)
        )
    }
    
    // Filter by chromosome
    if (selectedChromosome.value) {
        filtered = filtered.filter(result => 
            result.marker.chromosome === selectedChromosome.value
        )
    }
    
    return filtered
})

// Methods
const fetchGeneticResults = async () => {
    if (!props.animal?.id) {
        console.log('❌ Nenhum animal fornecido')
        return
    }
    
    loading.value = true
    error.value = null
    
    try {
        console.log('🔍 Buscando resultados genéticos para animal ID:', props.animal.id)
        const response = await fetch(`/admin/animals/${props.animal.id}/genetic-results`)
        
        console.log('📊 Status da resposta:', response.status)
        console.log('✅ Response headers:', Object.fromEntries(response.headers.entries()))
        
        if (!response.ok) {
            const errorText = await response.text()
            console.error('❌ Erro na resposta:', errorText)
            throw new Error(`Erro ${response.status}: ${response.statusText}`)
        }
        
        const data = await response.json()
        console.log('📋 Dados da resposta:', data)
        console.log('🔍 Estrutura dos dados:', Object.keys(data))
        
        geneticResults.value = data.results || []
        console.log('🧬 Resultados processados:', geneticResults.value.length, 'itens')
        if (geneticResults.value.length > 0) {
            console.log('📝 Primeiro resultado:', geneticResults.value[0])
        }
    } catch (err) {
        console.error('❌ Erro ao buscar resultados genéticos:', err)
        console.error('📄 Stack trace:', err.stack)
        error.value = err.message || 'Erro ao carregar resultados genéticos'
        geneticResults.value = []
    } finally {
        loading.value = false
        console.log('🏁 Busca finalizada. Loading:', loading.value)
    }
}

const resetFilters = () => {
    searchTerm.value = ''
    selectedChromosome.value = ''
}

// Watch for modal opening
watch(() => props.show, (newValue) => {
    if (newValue && props.animal) {
        fetchGeneticResults()
    }
})

// Reset state when modal closes
watch(() => props.show, (newValue) => {
    if (!newValue) {
        resetFilters()
        geneticResults.value = []
        error.value = null
    }
})
</script>