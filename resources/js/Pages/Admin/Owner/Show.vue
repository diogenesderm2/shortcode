<template>
    <AppLayout title="Proprietários">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Proprietário
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5">
                        <div class="owner-header bg-gradient-to-r from-blue-600 to-blue-500 p-6">
                            <div
                                class="flex items-center space-x-3 flex flex-col md:flex-row justify-between items-start mb-8">
                                <div class="flex items-center space-x-2">
                                    <div
                                        class=" flex owner-photo w-20 h-20 rounded-full bg-gray-200  items-center justify-center text-gray-500 text-sm font-semibold shadow-md ">
                                        80x80
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <h1 class="owner-property-name text-white text-2xl font-bold">
                                                Diogenes Estevao
                                            </h1>
                                            <span
                                                class="owner-id text-white bg-blue-400 px-2 text-xs py-0.5 rounded-full">
                                                ID: 19
                                            </span>
                                        </div>

                                        <p class="owner-property-name text-blue-100">
                                            Fazenda sao jose
                                        </p>
                                    </div>
                                </div>
                                <div class="owner-back-button ">
                                    <Link :href="route('admin.owner.index')"
                                        class="mt-4 md:mt-0 flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Voltar para a lista
                                    </Link>
                                </div>
                            </div>

                        </div>
                        <div class="owner-links p-6 flex justify-between items-center">
                            <a href="#" class="block bg-white border border-gray-200 rounded-xl p-4 card-hover group">
                                <div class="flex items-center">
                                    <div
                                        class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-blue-200 transition-colors">
                                        <i class="fas fa-user-circle text-blue-600 text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">
                                            Central do Cliente
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Acesso ao portal
                                        </p>
                                    </div>
                                    <div class="text-gray-400 group-hover:text-blue-500 transition-colors">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="block bg-white border border-gray-200 rounded-xl p-4 card-hover group">
                                <div class="flex items-center">
                                    <div
                                        class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-green-200 transition-colors">
                                        <i class="fas fa-file-invoice text-green-600 text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">Notas Fiscais</h3>
                                        <p class="text-sm text-gray-500">Visualizar documentos</p>
                                    </div>
                                    <div class="text-gray-400 group-hover:text-green-500 transition-colors">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="block bg-white border border-gray-200 rounded-xl p-4 card-hover group">
                                <div class="flex items-center">
                                    <div
                                        class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-purple-200 transition-colors">
                                        <i class="fas fa-history text-purple-600 text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">Histórico de Alterações</h3>
                                        <p class="text-sm text-gray-500">Ver logs do cadastro</p>
                                    </div>
                                    <div class="text-gray-400 group-hover:text-purple-500 transition-colors">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="block bg-white border border-gray-200 rounded-xl p-4 card-hover group">
                                <div class="flex items-center">
                                    <div
                                        class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-blue-200 transition-colors">
                                        <i class="fas fa-credit-card text-blue-600 text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">Link de Pagamento</h3>
                                        <p class="text-sm text-gray-500">Visualizar amostras</p>
                                    </div>
                                    <div class="text-gray-400 group-hover:text-blue-500 transition-colors">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="card-owner-personal-data py-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Dados Pessoais</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { ref } from 'vue';
import NewOwnerModal from '@/Components/NewOwnerModal.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    owner: Object,
    require: true
})

</script>


<style scoped>
.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
