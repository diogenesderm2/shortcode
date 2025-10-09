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

                    </div>
                </div>

                <div class="card-owner-personal-data grid grid-cols-1 lg:grid-cols-2 gap-6 ">
                    <div class="card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600 text-xl"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Dados Pessoais</h2>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Nome Completo</h3>
                                <p class="text-lg font-semibold text-gray-800">Diogenes Estevao</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">RG:</h3>
                                <p class="text-lg font-semibold text-gray-800">MG-12.345.678</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">CPF:</h3>
                                <p class="text-lg font-semibold text-gray-800">127.654.712-90</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">CNPJ:</h3>
                                <p class="text-lg font-semibold text-gray-800">00.123.456/0001-90</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Data de Cadastro:</h3>
                                <p class="text-lg font-semibold text-gray-800">30/09/2025, 22:38</p>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-map-marker-alt text-green-600"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Endereço</h2>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Logradouro</h3>
                                <p class="text-lg font-semibold text-gray-800">Rodovia BR-050, Km 245</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Cidade:</h3>
                                <p class="text-lg font-semibold text-gray-800">Belo Horizonte</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Estado:</h3>
                                <p class="text-lg font-semibold text-gray-800">Minas Gerais</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">CEP:</h3>
                                <p class="text-lg font-semibold text-gray-800">30.450-000</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">País:</h3>
                                <p class="text-lg font-semibold text-gray-800">Brasil</p>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-address-book text-purple-600"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Contatos</h2>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center mb-2">
                                    <div
                                        class="w-5 h-5 rounded-full flex items-center justify-center mr-2 border border-purple-600">
                                        <i class="fas fa-envelope text-white text-xs text-purple-600"></i>
                                    </div>
                                    <h3 class="font-medium text-gray-700">E-mails</h3>
                                </div>
                                <div class="ml-7 space-y-1">
                                    <p class="text-gray-800">diogenes@fazenda.com.br</p>
                                    <p class="text-gray-800">financeiro@fazenda.com.br</p>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center mb-2">
                                    <div
                                        class="w-5 h-5 rounded-full flex items-center justify-center mr-2 border border-purple-600">
                                        <i class="fas fa-phone-alt text-white text-xs text-purple-600"></i>
                                    </div>
                                    <h3 class="font-medium text-gray-700">Telefones</h3>
                                </div>
                                <div class="ml-7 space-y-1">
                                    <p class="text-gray-800">(34) 99999-8888</p>
                                    <p class="text-gray-800">(34) 3333-4444</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-paw text-amber-600"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Associações de Raças</h2>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h3 class="font-medium text-gray-700 mb-1">Nelore</h3>
                                <p class="text-gray-600 text-sm">registro@nelore.org.br</p>
                            </div>

                            <div>
                                <h3 class="font-medium text-gray-700 mb-1">Mangalarga Marchador</h3>
                                <p class="text-gray-600 text-sm">contato@mangalarga.com.br</p>
                            </div>

                            <div>
                                <h3 class="font-medium text-gray-700 mb-1">Girolando</h3>
                                <p class="text-gray-600 text-sm">cadastro@girolando.org.br</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="owner-finances ">
                    <div class="border border-gray-100 rounded-xl shadow-md p-6 bg-white">
                        <div class="flex  space-x-4">
                            <div
                                class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hand-holding-usd text-yellow-600"></i>
                            </div>
                            <div class="">
                                <h2 class="text-xl font-bold text-gray-900">Historico de Pagamentos</h2>
                                <p class="text-sm text-gray-500">Gerenciar pedidos pagos e pendentes</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Total pendente</p>
                                <p class="text-xl font-bold text-orange-600">R$ 1000,00</p>
                            </div>
                        </div>
                    </div>

                    <div class="owner-filter-finences border border-gray-100 rounded-xl shadow-md p-6 bg-white mt-6">
                     <i class="fas  fa-solid fa-sliders"></i>
                        <h1 class="text-xl font-bold text-gray-900">Filtros</h1>
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
