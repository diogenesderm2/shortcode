<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$emit('close')"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-[95vw] sm:w-full">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-600 to-blue-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <span class="text-2xl">🧬</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-white" id="modal-title">
                                    Comparação Genética - Marcadores
                                </h3>
                                <p v-if="data?.animal" class="text-sm text-green-100">
                                    {{ data.animal.name }} vs Pais
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
                <div class="px-6 py-4 max-h-[85vh] overflow-y-auto">
                    <!-- Loading state -->
                    <div v-if="loading" class="flex items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600"></div>
                        <span class="ml-3 text-gray-600">Carregando dados genéticos...</span>
                    </div>

                    <!-- Error state -->
                    <div v-else-if="error" class="text-center py-12">
                        <div class="text-red-500 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Erro ao carregar dados</h3>
                        <p class="text-gray-600">{{ error }}</p>
                    </div>

                    <!-- Data table -->
                    <div v-else-if="data?.markers?.length > 0" class="overflow-x-auto">
                        <!-- Animal info header -->
                        <div class="mb-6 grid grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <h4 class="font-semibold text-blue-800 mb-2">{{ data.animal.name }}</h4>
                                <p class="text-sm text-blue-600">Filho</p>
                            </div>
                            <div v-if="data.father" class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <h4 class="font-semibold text-green-800 mb-2">{{ data.father.name }}</h4>
                                <p class="text-sm text-green-600">Pai</p>
                                <div v-if="data.father_qualification !== undefined && data.father_qualification !== null" class="mt-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-green-700">Qualificação:</span>
                                        <span class="text-xs font-bold text-green-800">{{ data.father_qualification }}%</span>
                                    </div>
                                    <div class="w-full bg-green-200 rounded-full h-2 mt-1">
                                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300" 
                                             :style="{ width: data.father_qualification + '%' }"></div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-gray-500 mb-2">Pai não informado</h4>
                                <p class="text-sm text-gray-400">-</p>
                            </div>
                            <div v-if="data.mother" class="bg-pink-50 p-4 rounded-lg border border-pink-200">
                                <h4 class="font-semibold text-pink-800 mb-2">{{ data.mother.name }}</h4>
                                <p class="text-sm text-pink-600">Mãe</p>
                                <div v-if="data.mother_qualification !== undefined && data.mother_qualification !== null" class="mt-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-pink-700">Qualificação:</span>
                                        <span class="text-xs font-bold text-pink-800">{{ data.mother_qualification }}%</span>
                                    </div>
                                    <div class="w-full bg-pink-200 rounded-full h-2 mt-1">
                                        <div class="bg-pink-600 h-2 rounded-full transition-all duration-300" 
                                             :style="{ width: data.mother_qualification + '%' }"></div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-gray-500 mb-2">Mãe não informada</h4>
                                <p class="text-sm text-gray-400">-</p>
                            </div>
                        </div>

                        <!-- Genetic markers table -->
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Marcador
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-blue-600 uppercase tracking-wider bg-blue-50">
                                            {{ data.animal.name }} (Filho)
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-green-600 uppercase tracking-wider bg-green-50">
                                            {{ data.father?.name || 'Pai' }}
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-pink-600 uppercase tracking-wider bg-pink-50">
                                            {{ data.mother?.name || 'Mãe' }}
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Resultado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="marker in data.markers" :key="marker.marker_id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ marker.marker_name }}
                                        </td>
                                        
                                        <!-- Animal data -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center bg-blue-25">
                                            <div v-if="marker.animal_data && marker.animal_data.length > 0">
                                                <div v-for="result in marker.animal_data" :key="result.sample_id" class="mb-1">
                                                    <div class="flex justify-center space-x-1">
                                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                                                            {{ result.allele_1 || result.result_value }}
                                                        </span>
                                                        <span v-if="result.allele_2" class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                                                            {{ result.allele_2 }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span v-else class="text-gray-400">-</span>
                                        </td>
                                        
                                        <!-- Father data -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center bg-green-25">
                                            <div v-if="marker.father_data && marker.father_data.length > 0">
                                                <div v-for="result in marker.father_data" :key="result.sample_id" class="mb-1">
                                                    <div class="flex justify-center space-x-1">
                                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">
                                                            {{ result.allele_1 || result.result_value }}
                                                        </span>
                                                        <span v-if="result.allele_2" class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">
                                                            {{ result.allele_2 }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span v-else class="text-gray-400">-</span>
                                        </td>
                                        
                                        <!-- Mother data -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center bg-pink-25">
                                            <div v-if="marker.mother_data && marker.mother_data.length > 0">
                                                <div v-for="result in marker.mother_data" :key="result.sample_id" class="mb-1">
                                                    <div class="flex justify-center space-x-1">
                                                        <span class="px-2 py-1 text-xs bg-pink-100 text-pink-800 rounded">
                                                            {{ result.allele_1 || result.result_value }}
                                                        </span>
                                                        <span v-if="result.allele_2" class="px-2 py-1 text-xs bg-pink-100 text-pink-800 rounded">
                                                            {{ result.allele_2 }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span v-else class="text-gray-400">-</span>
                                        </td>
                                        
                                        <!-- Result/Compatibility -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="space-y-1">
                                                <!-- Father compatibility -->
                                                <div v-if="hasFatherData(marker)">
                                                    <span v-if="checkFatherCompatibility(marker)" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Q: Pai
                                                    </span>
                                                    <span v-else class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        NQ: Pai
                                                    </span>
                                                </div>
                                                
                                                <!-- Mother compatibility -->
                                                <div v-if="hasMotherData(marker)">
                                                    <span v-if="checkMotherCompatibility(marker)" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Q: Mãe
                                                    </span>
                                                    <span v-else class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        NQ: Mãe
                                                    </span>
                                                </div>
                                                
                                                <!-- No data available -->
                                                <div v-if="!hasFatherData(marker) && !hasMotherData(marker)">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        - Dados insuficientes
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- No data state -->
                    <div v-else class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum dado genético encontrado</h3>
                        <p class="text-gray-600">Não há resultados genéticos disponíveis para comparação.</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-3 flex justify-end">
                    <button @click="$emit('close')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    animalId: {
        type: Number,
        required: true
    }
})

const emit = defineEmits(['close'])

const loading = ref(false)
const error = ref(null)
const data = ref(null)

// Watch for modal show/hide
watch(() => props.show, (newValue) => {
    if (newValue) {
        fetchGeneticComparison()
    } else {
        // Reset state when modal closes
        data.value = null
        error.value = null
    }
})

const fetchGeneticComparison = async () => {
    loading.value = true
    error.value = null
    
    try {
        const response = await axios.get(`/admin/animals/${props.animalId}/genetic-comparison`)
        
        if (response.data.success) {
            data.value = response.data.data
        } else {
            error.value = response.data.message || 'Erro ao carregar dados genéticos'
        }
    } catch (err) {
        console.error('Erro ao buscar dados genéticos:', err)
        error.value = err.response?.data?.message || 'Erro de conexão ao buscar dados genéticos'
    } finally {
        loading.value = false
    }
}

// Check if marker has compatibility between child and parents
const checkCompatibility = (marker) => {
    if (!hasAllData(marker)) return false
    
    const animalAlleles = getAlleles(marker.animal_data)
    const fatherAlleles = getAlleles(marker.father_data)
    const motherAlleles = getAlleles(marker.mother_data)
    
    // Check if at least one allele from child matches father and one matches mother
    const matchesFather = animalAlleles.some(allele => fatherAlleles.includes(allele))
    const matchesMother = animalAlleles.some(allele => motherAlleles.includes(allele))
    
    return matchesFather && matchesMother
}

// Check compatibility specifically with father
const checkFatherCompatibility = (marker) => {
    if (!marker.animal_data || !marker.father_data || 
        !Array.isArray(marker.animal_data) || !Array.isArray(marker.father_data) ||
        marker.animal_data.length === 0 || marker.father_data.length === 0) {
        return false
    }
    
    const animalAlleles = getAlleles(marker.animal_data)
    const fatherAlleles = getAlleles(marker.father_data)
    
    // Check if at least one allele from child matches father
    return animalAlleles.some(allele => fatherAlleles.includes(allele))
}

// Check compatibility specifically with mother
const checkMotherCompatibility = (marker) => {
    if (!marker.animal_data || !marker.mother_data || 
        !Array.isArray(marker.animal_data) || !Array.isArray(marker.mother_data) ||
        marker.animal_data.length === 0 || marker.mother_data.length === 0) {
        return false
    }
    
    const animalAlleles = getAlleles(marker.animal_data)
    const motherAlleles = getAlleles(marker.mother_data)
    
    // Check if at least one allele from child matches mother
    return animalAlleles.some(allele => motherAlleles.includes(allele))
}

// Check if we have father data
const hasFatherData = (marker) => {
    return marker.animal_data && marker.animal_data.length > 0 &&
           marker.father_data && marker.father_data.length > 0
}

// Check if we have mother data
const hasMotherData = (marker) => {
    return marker.animal_data && marker.animal_data.length > 0 &&
           marker.mother_data && marker.mother_data.length > 0
}

// Check if we have data for all three (animal, father, mother)
const hasAllData = (marker) => {
    return marker.animal_data && marker.animal_data.length > 0 &&
           marker.father_data && marker.father_data.length > 0 &&
           marker.mother_data && marker.mother_data.length > 0
}

// Extract all alleles from result data
const getAlleles = (resultData) => {
    if (!resultData || !Array.isArray(resultData)) return []
    
    const alleles = []
    resultData.forEach(result => {
        if (result.allele_1) alleles.push(result.allele_1)
        if (result.allele_2) alleles.push(result.allele_2)
        if (result.result_value && !result.allele_1) alleles.push(result.result_value)
    })
    
    return [...new Set(alleles)] // Remove duplicates
}
</script>

<style scoped>
.bg-blue-25 {
    background-color: rgba(239, 246, 255, 0.5);
}

.bg-green-25 {
    background-color: rgba(240, 253, 244, 0.5);
}

.bg-pink-25 {
    background-color: rgba(253, 242, 248, 0.5);
}
</style>