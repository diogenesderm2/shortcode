<template>
    <AppLayout title="Animais">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Animais
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <!-- Filtros de pesquisa -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pesquisar Animais</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nome ou RG</label>
                                <input 
                                    v-model="filters.search" 
                                    type="text" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Digite o nome ou RG do animal"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Proprietário</label>
                                <select 
                                    v-model="filters.owner_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">Todos os proprietários</option>
                                    <option v-for="owner in owners" :key="owner.id" :value="owner.id">
                                        {{ owner.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button 
                                    @click="search" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                                >
                                    Pesquisar
                                </button>
                                <button 
                                    @click="resetFilters" 
                                    class="ml-2 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                                >
                                    Limpar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela de resultados -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nome
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        RG
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Data de Nascimento
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Sexo
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Proprietário
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="animal in animals.data" :key="animal.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ animal.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ animal.rg }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ formatDate(animal.birth_date) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ animal.sex === 'macho' ? 'Macho' : 'Fêmea' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ animal.owner?.name }}
                                    </td>
                                </tr>
                                <tr v-if="animals.data.length === 0">
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Nenhum animal encontrado
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4 flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Mostrando {{ animals.from }} a {{ animals.to }} de {{ animals.total }} resultados
                        </div>
                        <div class="flex space-x-2">
                            <button 
                                v-for="link in animals.links" 
                                :key="link.label"
                                @click="goToPage(link.url)"
                                :disabled="!link.url"
                                :class="[
                                    'px-3 py-1 rounded-md',
                                    link.active ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700',
                                    !link.url && 'opacity-50 cursor-not-allowed'
                                ]"
                                v-html="link.label"
                            ></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    animals: Object,
    filters: Object,
    owners: Array,
});

// Reactive filters
const filters = reactive({
    search: props.filters.search || '',
    owner_id: props.filters.owner_id || '',
});

// Search function
const search = () => {
    router.get(route('admin.animals.index'), filters, {
        preserveState: true,
        replace: true,
    });
};

// Reset filters
const resetFilters = () => {
    filters.search = '';
    filters.owner_id = '';
    search();
};

// Pagination
const goToPage = (url) => {
    if (url) {
        router.visit(url, {
            preserveState: true,
        });
    }
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
};
</script>