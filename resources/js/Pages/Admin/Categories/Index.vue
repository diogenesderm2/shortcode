<template>
    <AppLayout title="Categorias">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categorias
            </h2>
        </template>
        <div class="">
            <div class="">
                <!-- Flash Message -->
                <transition enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 transform translate-y-2"
                    enter-to-class="opacity-100 transform translate-y-0" leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 transform translate-y-0"
                    leave-to-class="opacity-0 transform translate-y-2">
                    <div v-if="$page.props.flash?.message"
                        class="mb-2 p-2 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ $page.props.flash.message }}
                        </div>
                    </div>
                </transition>



                <!-- Tabela -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border border-gray-200">
                    <div class="">
                        <!-- Header com botão -->
                        <div class="flex justify-between items-center mb-6 mt-6 mr-6
                        ">
                            <div>

                            </div>
                            <button @click="openModal()"
                                class="p-y-6 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200 transform hover:scale-105 shadow-sm hover:shadow-md flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span>Nova Categoria</span>
                            </button>
                        </div>
                        <div v-if="categories.data.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma categoria</h3>
                            <p class="mt-1 text-sm text-gray-500">Comece criando sua primeira categoria.</p>
                            <div class="mt-6">
                                <button @click="openModal()"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Nova Categoria
                                </button>
                            </div>
                        </div>
                        <div v-else class="space-y-4 overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left p-3 w-8">
                                            <input type="checkbox" name="categoria[]" id="categoria_id"
                                                class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                        </th>
                                        <th class="text-left p-3 font-medium text-gray-700">Nome</th>
                                        <th class="text-left p-3 font-medium text-gray-700">Slug</th>
                                        <th class="text-left p-3 font-medium text-gray-700">Descrição</th>
                                        <th class="text-left p-3 font-medium text-gray-700">Status</th>
                                        <th class="text-left p-3 font-medium text-gray-700">Data Cadastro</th>
                                        <th class="text-left p-3 font-medium text-gray-700">Ações</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="category in categories.data"
                                        class="border-b border-gray-100 hover:bg-gray-50 transition-all duration-200">
                                        <td class="p-3">
                                            <input type="checkbox"
                                                class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                        </td>
                                        <td class="p-3">
                                            <div class="font-medium text-gray-900">
                                                {{ category.name }}
                                            </div>
                                        </td>
                                        <td class="p-3">
                                            <div class="font-medium text-gray-500 font-mono text-sm">
                                                {{ category.slug }}
                                            </div>
                                        </td>
                                        <td class="p-3">
                                            <div class="text-sm text-gray-600 max-w-xs truncate">
                                                {{ category.description || 'Sem descrição' }}
                                            </div>
                                        </td>
                                        <td class="p-3">
                                            <span
                                                :class="category.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                                class="px-2 py-1 text-xs font-medium rounded-full">
                                                {{ category.is_active ? 'Ativa' : 'Inativa' }}
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <div class="text-sm text-gray-600">
                                                {{ formatDate(category.created_at) }}
                                            </div>
                                        </td>
                                        <td class="p-3">
                                            <div class="flex items-center gap-2">
                                                <button @click="editCategory(category)"
                                                    class="text-blue-600 hover:text-blue-800 p-2 rounded-lg transition-all duration-200 hover:bg-blue-50"
                                                    title="Editar">
                                                    <PencilSquareIcon class="w-5 h-5" />
                                                </button>
                                                <button
                                                    class="text-red-600 hover:text-red-800 p-2 rounded-lg transition-all duration-200 hover:bg-red-50"
                                                    title="Excluir">
                                                    <TrashIcon class="w-5 h-5" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Categoria -->
        <CategoryModal :show="showModal" :category="selectedCategory" @close="closeModal" @saved="onCategorySaved" />
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import CategoryModal from '@/Components/CategoryModal.vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    categories: Object
});

const showModal = ref(false);
const selectedCategory = ref(null);

const openModal = (category = null) => {
    selectedCategory.value = category;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedCategory.value = null;
};

const editCategory = (category) => {
    openModal(category);
};

const onCategorySaved = () => {
    // Recarregar a página para mostrar as mudanças
    window.location.reload();
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};
</script>
