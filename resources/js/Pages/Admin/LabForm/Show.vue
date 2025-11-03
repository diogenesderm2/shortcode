<template>
    <AppLayout title="Visualizar Formulário">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Formulário {{ labForm.form_number }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Informações do Formulário -->
                    <div class="p-6 border-b border-gray-200 no-print">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Número do Formulário</label>
                                <p class="text-lg font-semibold">{{ labForm.form_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Criado por</label>
                                <p class="text-lg">{{ labForm.user_created?.name || 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Data de Geração</label>
                                <p class="text-lg">{{ formatDate(labForm.generated_at) }}</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <Link :href="route('admin.lab-forms.index')"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                ← Voltar para Lista
                            </Link>
                            <div class="space-x-2">
                                <button @click="printForm"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    🖨️ Imprimir
                                </button>
                                <button @click="exportForm"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    📄 Exportar PDF
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário -->
                    <FormView 
                        :samples="reconstructedSamples"
                        :form-number="labForm.form_number"
                        :generated-at="labForm.generated_at"
                        :is-stored-form="true"
                        :lab-form="labForm"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import FormView from '@/Pages/Admin/Sample/FormView.vue'

const props = defineProps({
    labForm: Object
})

// Função global route
const route = window.route

// Reconstruir array de amostras baseado nas posições salvas
const reconstructedSamples = computed(() => {
    const samples = new Array(96).fill(null) // 8x12 = 96 posições
    
    if (props.labForm.sample_positions) {
        props.labForm.sample_positions.forEach(samplePos => {
            const { position } = samplePos
            const index = position.index
            
            if (index >= 0 && index < 96) {
                // Reconstruir objeto da amostra com dados salvos
                samples[index] = {
                    id: samplePos.sample_id,
                    label: samplePos.sample?.label || `${position.row}${position.col}`,
                    priority: samplePos.sample?.priority || 0,
                    created_at: samplePos.sample?.created_at,
                    // Adicionar outros campos necessários se disponíveis
                    tests: samplePos.sample?.tests || []
                }
            }
        })
    }
    
    return samples.filter(sample => sample !== null)
})

// Função para formatar data
const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    
    const date = new Date(dateString)
    return date.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

// Função para imprimir
const printForm = () => {
    window.print()
}

// Função para exportar PDF
const exportForm = () => {
    // Implementar exportação PDF se necessário
    alert('Funcionalidade de exportação PDF será implementada em breve.')
}
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
}
</style>