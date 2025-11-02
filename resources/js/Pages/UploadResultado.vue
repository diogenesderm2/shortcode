<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

// Estado do componente
const fileInput = ref(null)
const isProcessing = ref(false)
const uploadResult = ref(null)
const errorMessage = ref('')

// Form do Inertia
const form = useForm({
    file: null
})

// Função para selecionar arquivo
const selectFile = (event) => {
    const file = event.target.files[0]
    if (file) {
        // Validar extensão
        const allowedExtensions = ['tsv', 'txt']
        const fileExtension = file.name.split('.').pop().toLowerCase()
        
        if (!allowedExtensions.includes(fileExtension)) {
            errorMessage.value = 'Apenas arquivos .tsv são permitidos.'
            event.target.value = ''
            return
        }
        
        // Validar tamanho (10MB)
        if (file.size > 10 * 1024 * 1024) {
            errorMessage.value = 'O arquivo deve ter no máximo 10MB.'
            event.target.value = ''
            return
        }
        
        form.file = file
        errorMessage.value = ''
    }
}

// Função para processar arquivo
const processarResultado = async () => {
    if (!form.file) {
        errorMessage.value = 'Por favor, selecione um arquivo.'
        return
    }
    
    isProcessing.value = true
    errorMessage.value = ''
    uploadResult.value = null
    
    try {
        const formData = new FormData()
        formData.append('file', form.file)
        
        const response = await fetch('/api/upload-resultado', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        
        const result = await response.json()
        
        if (result.success) {
            uploadResult.value = result
            // Limpar formulário
            form.file = null
            fileInput.value.value = ''
        } else {
            errorMessage.value = result.message || 'Erro ao processar arquivo.'
        }
        
    } catch (error) {
        console.error('Erro:', error)
        errorMessage.value = 'Erro de conexão. Tente novamente.'
    } finally {
        isProcessing.value = false
    }
}

// Função para resetar
const resetForm = () => {
    form.file = null
    fileInput.value.value = ''
    errorMessage.value = ''
    uploadResult.value = null
}
</script>

<template>
    <AppLayout title="Upload de Resultado de DNA">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Upload de Resultado de DNA
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Card Principal -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-8">
                        <!-- Título -->
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                Upload de Resultado de DNA
                            </h3>
                            <p class="text-gray-600">
                                Faça upload de arquivos .tsv contendo resultados de análises genéticas
                            </p>
                        </div>

                        <!-- Área de Upload -->
                        <div class="mb-8">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-gray-400 transition-colors">
                                <div class="mb-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="file-upload" class="cursor-pointer">
                                        <span class="text-lg font-medium text-indigo-600 hover:text-indigo-500">
                                            Clique para selecionar um arquivo
                                        </span>
                                        <input
                                            id="file-upload"
                                            ref="fileInput"
                                            type="file"
                                            accept=".tsv,.txt"
                                            class="sr-only"
                                            @change="selectFile"
                                        />
                                    </label>
                                    <p class="text-gray-500 mt-1">ou arraste e solte aqui</p>
                                </div>
                                
                                <p class="text-sm text-gray-500">
                                    Apenas arquivos .tsv (máximo 10MB)
                                </p>
                            </div>
                            
                            <!-- Arquivo Selecionado -->
                            <div v-if="form.file" class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">{{ form.file.name }}</span>
                                        <span class="text-sm text-gray-500 ml-2">({{ (form.file.size / 1024 / 1024).toFixed(2) }} MB)</span>
                                    </div>
                                    <button
                                        @click="resetForm"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium"
                                    >
                                        Remover
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Mensagens de Erro -->
                        <div v-if="errorMessage" class="mb-6">
                            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-800">{{ errorMessage }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resultado do Upload -->
                        <div v-if="uploadResult && uploadResult.success" class="mb-6">
                            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800">
                                            {{ uploadResult.message }}
                                        </h3>
                                        <div class="mt-2 text-sm text-green-700">
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>Marcadores processados: {{ uploadResult.data.marcadores_processados }}</li>
                                                <li>Amostras processadas: {{ uploadResult.data.amostras_processadas }}</li>
                                                <li>Resultados processados: {{ uploadResult.data.resultados_processados }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botão de Processar -->
                        <div class="text-center">
                            <button
                                @click="processarResultado"
                                :disabled="!form.file || isProcessing"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <svg v-if="isProcessing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ isProcessing ? 'Processando...' : 'Processar Resultado' }}
                            </button>
                        </div>

                        <!-- Informações Adicionais -->
                        <div class="mt-8 bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Instruções:</h4>
                            <ul class="text-sm text-gray-600 space-y-2">
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 h-1.5 w-1.5 bg-gray-400 rounded-full mt-2 mr-3"></span>
                                    O arquivo deve estar no formato TSV (Tab-Separated Values)
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 h-1.5 w-1.5 bg-gray-400 rounded-full mt-2 mr-3"></span>
                                    A primeira linha deve conter os cabeçalhos: TARGET, CHROM, POS, REF, ALT, seguidos dos nomes das amostras
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 h-1.5 w-1.5 bg-gray-400 rounded-full mt-2 mr-3"></span>
                                    Cada linha subsequente representa um marcador genético com seus resultados
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 h-1.5 w-1.5 bg-gray-400 rounded-full mt-2 mr-3"></span>
                                    Animais serão criados automaticamente se não existirem
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>