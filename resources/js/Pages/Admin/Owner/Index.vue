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
                    <div class=" flex justify-between items-center px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Proprietários
                        </h3>
                        <button @click="openNewOwnerModal" class="bg-blue-500 text-white px-2 py-1 rounded-md shadow">Adicionar</button>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
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
                            <tbody>

                                <tr v-for="owner in owners.data" :key="owner.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ owner.id }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <img class="w-20 h-20 rounded-full" :src="owner.image" alt="">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ owner.name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ owner.rg }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ owner.cpf }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ owner.cnpj }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 ">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ owner.property }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            <button class="bg-blue-500 text-white px-2 py-1 rounded-md">Editar</button>
                                            <button class="bg-red-500 text-white px-2 py-1 rounded-md">Excluir</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{ showModal }}
        <NewOwnerModal :show="showModal" :owner="selectedOwner" @close="closeModal"  />
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import NewOwnerModal from '@/Components/NewOwnerModal.vue'
const props = defineProps({
    owners: Object,
    require: true
})

const selectedOwner = ref(null)
const showModal = ref(false)

const openNewOwnerModal = ( owner = null) => {
 selectedOwner.value = owner;
 showModal.value = true

}

const closeModal = () => {
    showModal.value = false
}

</script>
