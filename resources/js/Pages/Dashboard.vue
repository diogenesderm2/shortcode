<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Props recebidas do controlador
const props = defineProps({
    stats: Object,
    charts: Object,
    recentActivity: Object,
});

// Computed para formatar números
const formatNumber = (num) => {
    return new Intl.NumberFormat('pt-BR').format(num);
};

// Computed para calcular percentuais dos tipos de animais
const animalTypesWithPercentage = computed(() => {
    const total = props.charts.animalTypes.reduce((sum, item) => sum + item.count, 0);
    return props.charts.animalTypes.map(item => ({
        ...item,
        percentage: total > 0 ? Math.round((item.count / total) * 100) : 0
    }));
});

// Computed para calcular percentuais do status das amostras
const sampleStatusWithPercentage = computed(() => {
    const total = props.charts.sampleStatus.reduce((sum, item) => sum + item.count, 0);
    return props.charts.sampleStatus.map(item => ({
        ...item,
        percentage: total > 0 ? Math.round((item.count / total) * 100) : 0
    }));
});

// Cores para os gráficos
const colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500'];
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Cards de Estatísticas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                    <!-- Total de Animais -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-paw text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Animais</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ formatNumber(stats.totalAnimals) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total de Proprietários -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-users text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Proprietários</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ formatNumber(stats.totalOwners) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total de Amostras -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-vial text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Amostras</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ formatNumber(stats.totalSamples) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total de Resultados Genéticos -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-dna text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Resultados Genéticos</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ formatNumber(stats.totalGeneticResults) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total de Usuários -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-tie text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Usuários</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ formatNumber(stats.totalUsers) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Atividade Recente -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Atividade dos Últimos 7 Dias</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ formatNumber(recentActivity.animals) }}</p>
                                <p class="text-sm text-gray-500">Novos Animais</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600">{{ formatNumber(recentActivity.owners) }}</p>
                                <p class="text-sm text-gray-500">Novos Proprietários</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-yellow-600">{{ formatNumber(recentActivity.samples) }}</p>
                                <p class="text-sm text-gray-500">Novas Amostras</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-red-600">{{ formatNumber(recentActivity.genetic_results) }}</p>
                                <p class="text-sm text-gray-500">Novos Resultados</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Cadastros Mensais -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Cadastros por Mês (Últimos 12 meses)</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div v-for="(item, index) in charts.monthlyAnimals" :key="index" class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">{{ item.period }}</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-20 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-500 h-2 rounded-full" :style="{ width: `${Math.min(100, (item.count / Math.max(...charts.monthlyAnimals.map(i => i.count))) * 100)}%` }"></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 w-8">{{ item.count }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tipos de Animais -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Distribuição por Tipo de Animal</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div v-for="(item, index) in animalTypesWithPercentage" :key="index" class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div :class="colors[index % colors.length]" class="w-4 h-4 rounded-full"></div>
                                        <span class="text-sm text-gray-600">{{ item.name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-900">{{ formatNumber(item.count) }}</span>
                                        <span class="text-xs text-gray-500">({{ item.percentage }}%)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status das Amostras -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Status das Amostras</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div v-for="(item, index) in sampleStatusWithPercentage" :key="index" class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div :class="item.status === 'Liberado' ? 'bg-green-500' : 'bg-yellow-500'" class="w-4 h-4 rounded-full"></div>
                                        <span class="text-sm text-gray-600">{{ item.status }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-900">{{ formatNumber(item.count) }}</span>
                                        <span class="text-xs text-gray-500">({{ item.percentage }}%)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Usuários -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Top 10 Usuários por Cadastros</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div v-for="(user, index) in charts.userRegistrations" :key="index" class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-700">{{ index + 1 }}</span>
                                        </div>
                                        <span class="text-sm text-gray-600">{{ user.name }}</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">{{ user.total }} total</div>
                                        <div class="text-xs text-gray-500">
                                            {{ user.owners }} proprietários, {{ user.animals }} animais, {{ user.samples }} amostras
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Cadastros Mensais Detalhado -->
                <div class="mt-8 bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Evolução de Cadastros (Últimos 12 meses)</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Animais -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                    Animais
                                </h4>
                                <div class="space-y-2">
                                    <div v-for="(item, index) in charts.monthlyAnimals.slice(-6)" :key="index" class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ item.period }}</span>
                                        <span class="font-medium">{{ item.count }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Amostras -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    Amostras
                                </h4>
                                <div class="space-y-2">
                                    <div v-for="(item, index) in charts.monthlySamples.slice(-6)" :key="index" class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ item.period }}</span>
                                        <span class="font-medium">{{ item.count }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Resultados Genéticos -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                    Resultados Genéticos
                                </h4>
                                <div class="space-y-2">
                                    <div v-for="(item, index) in charts.monthlyGeneticResults.slice(-6)" :key="index" class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ item.period }}</span>
                                        <span class="font-medium">{{ item.count }}</span>
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
