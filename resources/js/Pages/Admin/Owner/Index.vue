<template>
    <AppLayout title="Proprietários">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Proprietários
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
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
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="flex justify-between items-center px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Proprietários
                        </h3>
                        <button @click="openNewOwnerModal"
                            class="bg-blue-500 text-white px-2 py-1 rounded-md shadow">Adicionar</button>
                    </div>

                    <!-- Campo de Busca -->
                    <div class="px-4 pb-4 sm:px-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <label for="search" class="sr-only">Buscar proprietários</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input id="search" v-model="searchQuery" @input="performSearch" type="text"
                                        placeholder="Buscar por nome, RG, CPF ou CNPJ..."
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                </div>
                            </div>
                            <button v-if="searchQuery" @click="clearSearch"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Limpar
                            </button>
                        </div>
                    </div>

                    <div class="px-4 py-5 sm:p-6">
                        <div v-if="owners.data.length === 0" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum proprietário encontrado</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ searchQuery ? 'Tente ajustar os termos de busca.' : 'Comece adicionando um novo proprietário.' }}
                            </p>
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Imagem
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nome
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            RG
                                        </th>

                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            CPF
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            CNPJ
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Propriedade
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">

                                    <tr v-for="owner in owners.data" :key="owner.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ owner.id }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <img v-if="owner.image" class="w-12 h-12 rounded-full object-cover"
                                                    :src="owner.image" :alt="owner.name">
                                                <div v-else
                                                    class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <Link :href="route('admin.owners.show', owner.id)"
                                                    class="text-blue-600 hover:text-blue-800 hover:underline">{{
                                                owner.name }}
                                                </Link>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ owner.rg || '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ formatCpf(owner.cpf) || '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ formatCnpj(owner.cnpj) || '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ owner.property_name || '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button @click="openNewOwnerModal(owner)"
                                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Editar
                                                </button>
                                                <button @click="deleteOwner(owner.id)"
                                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Excluir
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="mt-6">
                            <Pagination :meta="owners.meta" v-if="owners.meta"></Pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <NewOwnerModal :show="showModal" :owner="selectedOwner" @close="closeModal" />
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { ref, watch } from 'vue';
import NewOwnerModal from '@/Components/NewOwnerModal.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    owners: Object,
    filters: Object,
    require: true
})

const selectedOwner = ref(null)
const showModal = ref(false)
const searchQuery = ref(props.filters.search || '')

const openNewOwnerModal = (owner = null) => {
    selectedOwner.value = owner;
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
}

const deleteOwner = (ownerId) => {
    if (confirm('Tem certeza que deseja excluir este proprietário?')) {
        router.delete(route('admin.owners.destroy', ownerId), {
            onSuccess: () => {
                // A página será recarregada automaticamente
            }
        })
    }
}

const formatCpf = (cpf) => {
    if (!cpf) return null
    return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
}

const formatCnpj = (cnpj) => {
    if (!cnpj) return null
    return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5')
}

const performSearch = () => {
    router.get(route('admin.owners.index'), {
        search: searchQuery.value
    }, {
        preserveState: true,
        replace: true
    })
}

const clearSearch = () => {
    searchQuery.value = ''
    router.get(route('admin.owners.index'), {}, {
        preserveState: true,
        replace: true
    })
}

// Debounce da busca para evitar muitas requisições
let searchTimeout = null
watch(searchQuery, (newValue) => {
    if (searchTimeout) {
        clearTimeout(searchTimeout)
    }

    searchTimeout = setTimeout(() => {
        if (newValue !== props.filters.search) {
            performSearch()
        }
    }, 500)
})
</script>
