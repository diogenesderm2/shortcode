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
                                    <Link :href="route('admin.owners.index')"
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
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hand-holding-usd text-yellow-600"></i>
                            </div>
                            <div class="">
                                <h2 class="text-xl font-bold text-gray-900">Historico de Pagamentos</h2>
                                <p class="text-sm text-gray-500">Gerenciar pedidos pagos e pendentes</p>
                            </div>
                            <div class=" flex-1 text-right">
                                <p class="text-sm text-gray-500">Total pendente</p>
                                <p class="text-xl font-bold font-black text-orange-600">R$ 1000,00</p>
                            </div>
                        </div>
                    </div>
                    <div class="owner-tabs-order-payed-and-not-payed py-6">
                        <div class="flex gap-4">
                            <button @click="setActiveTab('pagos')" :class="[
                                'flex-1 flex items-center justify-center gap-2 px-6 py-3 rounded-lg transition-colors duration-200 shadow-sm',
                                activeTab === 'pagos'
                                    ? 'bg-green-500 text-white hover:bg-green-600'
                                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                            ]">
                                <i class="far fa-check-circle"
                                    :class="activeTab === 'pagos' ? 'text-white' : 'text-gray-700'"></i>
                                <span>Pedidos Pagos</span>
                                <span :class="[
                                    'px-2 py-1 rounded-full text-sm font-semibold',
                                    activeTab === 'pagos'
                                        ? 'bg-white bg-opacity-20 text-white'
                                        : 'bg-gray-300 text-gray-700'
                                ]">5</span>
                            </button>

                            <button @click="setActiveTab('pendentes')" :class="[
                                'flex-1 flex items-center justify-center gap-2 px-6 py-3 rounded-lg transition-colors duration-200 shadow-sm',
                                activeTab === 'pendentes'
                                    ? 'bg-blue-500 text-white hover:bg-blue-600'
                                    : 'bg-gray-300 text-gray-700 hover:bg-gray-300'
                            ]">
                                <i class="far fa-clock"
                                    :class="activeTab === 'pendentes' ? 'text-white' : 'text-gray-700'"></i>
                                <span>Pedidos Pendentes</span>
                                <span :class="[
                                    'px-2 py-1 rounded-full text-sm font-semibold',
                                    activeTab === 'pendentes'
                                        ? 'bg-white bg-opacity-20 text-white'
                                        : 'bg-gray-400 text-gray-700'
                                ]">2</span>
                            </button>
                        </div>
                    </div>
                    <div class="owner-filter-finences border border-gray-100 rounded-xl shadow-md p-6 bg-white mt-6">
                        <div class="mb-4  flex items-center space-x-3">
                            <i class="fas  fa-solid fa-sliders"></i>
                            <h1 class="text-xl  text-gray-600 text-sm">Filtros</h1>
                        </div>
                        <div class="flex gap-4 items-center">
                            <div class="input-search relative w-1/2">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-search text-gray-400 pointer-events-none"></i>
                                </div>
                                <input
                                    class="pl-10 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent focus:border-blue-500 outline-none transition duration-300"
                                    type="text" name="search" id="" placeholder="Buscar por numero, cliente...">
                            </div>

                            <div class="input-payment-forms w-1/2">
                                <select
                                    class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent focus:border-blue-500 outline-none transition duration-300"
                                    name="payment-form" id="">
                                    <option value="">Todos os meios de pagamento</option>
                                    <option value="1">Cartão de Crédito</option>
                                    <option value="2">Pix</option>
                                    <option value="3">Credito no Sistema</option>
                                    <option value="4">Boleto Bancário</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="owner-cards-order-payed-and-not-payed mt-6">
                        <!-- Cards de Pedidos Pagos -->
                        <div v-if="activeTab === 'pagos'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Card 1 - Pago -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        Pedido #118473
                                        <span class="bg-green-500 text-white text-xs font-medium px-2.5 py-1 rounded-full ml-2">Pago</span>
                                    </h3>
                                    <span class="text-2xl font-bold text-green-500">R$ 1,00</span>
                                </div>

                                <div class="flex justify-between items-center mb-4">
                                    <p class="text-sm text-gray-600">Rosival Martins</p>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <span>Emitido: 03/07/2024</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="far fa-check-circle mr-2"></i>
                                        <span>Pago: 03/07/2024</span>
                                    </div>
                                </div>

                                <div class="border-t pt-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fab fa-pix mr-2 text-teal-600"></i>
                                            <span>PIX</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                                            @click="openPaymentModal({
                                                number: 118473,
                                                cliente: 'Rosival Martins',
                                                total: 96.00,
                                                pagamento: 'PIX',
                                                emitidoEm: '29/09/2025'
                                            })"
                                        >
                                            Pagar Agora
                                        </button>
                                        <button
                                          class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors"
                                          @click="openPaidDetails({
                                            number: 118473,
                                            cliente: 'Rosival Martins',
                                            total: 36.00,
                                            pagamento: 'PIX',
                                            emitidoEm: '05/06/2024 às 21:16',
                                            pagoEm: '05/06/2024 às 21:16',
                                            animais: [
                                              { ord: 1, entrada: '04/06/2024', rg: 'JST300', nome: 'RELÂMPAGO', raca: 'NELORE', especie: 'BOVINO', tipo: 'SANGUE', valor: 36.00 }
                                            ]
                                          })"
                                        >
                                            Ver Detalhes
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2 - Pago -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        Pedido #118418
                                        <span class="bg-green-500 text-white text-xs font-medium px-2.5 py-1 rounded-full ml-2">Pago</span>
                                    </h3>
                                    <span class="text-2xl font-bold text-green-500">R$ 1,10</span>
                                </div>

                                <div class="flex justify-between items-center mb-4">
                                    <p class="text-sm text-gray-600">Rosival Martins</p>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <span>Emitido: 03/07/2024</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="far fa-check-circle mr-2"></i>
                                        <span>Pago: 03/07/2024</span>
                                    </div>
                                </div>

                                <div class="border-t pt-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fab fa-pix mr-2 text-teal-600"></i>
                                            <span>PIX</span>
                                        </div>
                                    </div>
                                    <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors"
                                      @click="openPaidDetails({
                                        number: 118418,
                                        cliente: 'Rosival Martins',
                                        total: 1.10,
                                        pagamento: 'PIX',
                                        emitidoEm: '03/07/2024',
                                        pagoEm: '03/07/2024',
                                        animais: [
                                          { ord: 1, entrada: '03/07/2024', rg: 'RM001', nome: 'ANIMAL B', raca: 'NELORE', especie: 'BOVINO', tipo: 'SANGUE', valor: 1.10 }
                                        ]
                                      })"
                                    >
                                        Ver Detalhes
                                    </button>
                                </div>
                            </div>

                            <!-- Card 3 - Pago -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        Pedido #103182
                                        <span class="bg-green-500 text-white text-xs font-medium px-2.5 py-1 rounded-full ml-2">Pago</span>
                                    </h3>
                                    <span class="text-2xl font-bold text-green-500">R$ 129,24</span>
                                </div>

                                <div class="flex justify-between items-center mb-4">
                                    <p class="text-sm text-gray-600">Maria Silva</p>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <span>Emitido: 23/08/2023</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="far fa-check-circle mr-2"></i>
                                        <span>Pago: 23/08/2023</span>
                                    </div>
                                </div>

                                <div class="border-t pt-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-credit-card mr-2 text-blue-600"></i>
                                            <span>CARTÃO DE CRÉDITO</span>
                                        </div>
                                    </div>
                                    <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors"
                                      @click="openPaidDetails({
                                        number: 103182,
                                        cliente: 'Maria Silva',
                                        total: 129.24,
                                        pagamento: 'CARTÃO DE CRÉDITO',
                                        emitidoEm: '23/08/2023',
                                        pagoEm: '23/08/2023',
                                        animais: [
                                          { ord: 1, entrada: '23/08/2023', rg: 'XYZ789', nome: 'ANIMAL 2', raca: 'NELORE', especie: 'BOVINO', tipo: 'SANGUE', valor: 129.24 }
                                        ]
                                      })"
                                    >
                                        Ver Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Cards de Pedidos Pendentes -->
                        <div v-if="activeTab === 'pendentes'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Card 1 - Pendente -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        Pedido #118500
                                        <span class="bg-blue-500 text-white text-xs font-medium px-2.5 py-1 rounded-full ml-2">Pendente</span>
                                    </h3>
                                    <span class="text-2xl font-bold text-blue-500">R$ 25,50</span>
                                </div>

                                <div class="flex justify-between items-center mb-4">
                                    <p class="text-sm text-gray-600">Carlos Silva</p>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <span>Emitido: 15/01/2025</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="far fa-clock mr-2"></i>
                                        <span>Aguardando pagamento</span>
                                    </div>
                                </div>

                                <div class="border-t pt-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fab fa-pix mr-2 text-teal-600"></i>
                                            <span>PIX</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button 
                                            class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                                            @click="openPaymentModal({
                                                number: 118500,
                                                cliente: 'Carlos Silva',
                                                total: 25.50,
                                                pagamento: 'PIX',
                                                emitidoEm: '15/01/2025'
                                            })"
                                        >
                                            Pagar Agora
                                        </button>
                                        <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors"
                                          @click="openUnpaidDetails({
                                            number: 118500,
                                            cliente: 'Carlos Silva',
                                            total: 25.50,
                                            pagamento: 'PIX',
                                            emitidoEm: '15/01/2025',
                                            animais: [
                                              { ord: 1, entrada: '15/01/2025', rg: 'CS001', nome: 'ANIMAL A', raca: 'NELORE', especie: 'BOVINO', tipo: 'SANGUE', valor: 25.50 }
                                            ]
                                          })"
                                        >
                                          Ver Detalhes
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2 - Pendente -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        Pedido #118501
                                        <span class="bg-blue-500 text-white text-xs font-medium px-2.5 py-1 rounded-full ml-2">Pendente</span>
                                    </h3>
                                    <span class="text-2xl font-bold text-blue-500">R$ 45,00</span>
                                </div>

                                <div class="flex justify-between items-center mb-4">
                                    <p class="text-sm text-gray-600">Ana Costa</p>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <span>Emitido: 14/01/2025</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="far fa-clock mr-2"></i>
                                        <span>Aguardando pagamento</span>
                                    </div>
                                </div>

                                <div class="border-t pt-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-credit-card mr-2 text-blue-600"></i>
                                            <span>CARTÃO DE CRÉDITO</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button 
                                            class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                                            @click="openPaymentModal({
                                                number: 118501,
                                                cliente: 'Ana Costa',
                                                total: 45.00,
                                                pagamento: 'CARTÃO DE CRÉDITO',
                                                emitidoEm: '14/01/2025'
                                            })"
                                        >
                                            Pagar Agora
                                        </button>
                                        <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors"
                                          @click="openUnpaidDetails({
                                            number: 118501,
                                            cliente: 'Ana Costa',
                                            total: 45.00,
                                            pagamento: 'CARTÃO DE CRÉDITO',
                                            emitidoEm: '14/01/2025',
                                            animais: [
                                              { ord: 1, entrada: '14/01/2025', rg: 'AC001', nome: 'ANIMAL C', raca: 'NELORE', especie: 'BOVINO', tipo: 'SANGUE', valor: 45.00 }
                                            ]
                                          })"
                                        >
                                            Ver Detalhes
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3 - Pendente -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        Pedido #118502
                                        <span class="bg-blue-500 text-white text-xs font-medium px-2.5 py-1 rounded-full ml-2">Pendente</span>
                                    </h3>
                                    <span class="text-2xl font-bold text-blue-500">R$ 78,90</span>
                                </div>

                                <div class="flex justify-between items-center mb-4">
                                    <p class="text-sm text-gray-600">Pedro Santos</p>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <span>Emitido: 13/01/2025</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="far fa-clock mr-2"></i>
                                        <span>Aguardando pagamento</span>
                                    </div>
                                </div>

                                <div class="border-t pt-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fab fa-pix mr-2 text-teal-600"></i>
                                            <span>PIX</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button 
                                            class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                                            @click="openPaymentModal({
                                                number: 118502,
                                                cliente: 'Pedro Santos',
                                                total: 78.90,
                                                pagamento: 'PIX',
                                                emitidoEm: '13/01/2025'
                                            })"
                                        >
                                            Pagar Agora
                                        </button>
                                        <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors"
                                            @click="openUnpaidDetails({
                                            number: 118502,
                                            cliente: 'Pedro Santos',
                                            total: 78.90,
                                            pagamento: 'PIX',
                                            emitidoEm: '13/01/2025',
                                            animais: [
                                            { ord: 1, entrada: '13/01/2025', rg: 'PS001', nome: 'ANIMAL D', raca: 'NELORE', especie: 'BOVINO', tipo: 'SANGUE', valor: 78.90 }
                                            ]
                                            })"
                                            >
                                                Ver Detalhes
                                            </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 4 - Pendente -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        Pedido #118503
                                        <span class="bg-blue-500 text-white text-xs font-medium px-2.5 py-1 rounded-full ml-2">Pendente</span>
                                    </h3>
                                    <span class="text-2xl font-bold text-blue-500">R$ 120,00</span>
                                </div>

                                <div class="flex justify-between items-center mb-4">
                                    <p class="text-sm text-gray-600">Lucia Oliveira</p>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <span>Emitido: 12/01/2025</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="far fa-clock mr-2"></i>
                                        <span>Aguardando pagamento</span>
                                    </div>
                                </div>

                                <div class="border-t pt-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-credit-card mr-2 text-blue-600"></i>
                                            <span>CARTÃO DE CRÉDITO</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button 
                                            class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                                            @click="openPaymentModal({
                                                number: 118503,
                                                cliente: 'Lucia Oliveira',
                                                total: 120.00,
                                                pagamento: 'CARTÃO DE CRÉDITO',
                                                emitidoEm: '12/01/2025'
                                            })"
                                        >
                                            Pagar Agora
                                        </button>
                                        <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors"
                                          @click="openUnpaidDetails({
                                            number: 118503,
                                            cliente: 'Lucia Oliveira',
                                            total: 120.00,
                                            pagamento: 'CARTÃO DE CRÉDITO',
                                            emitidoEm: '12/01/2025',
                                            animais: [
                                              { ord: 1, entrada: '12/01/2025', rg: 'LO001', nome: 'ANIMAL E', raca: 'NELORE', especie: 'BOVINO', tipo: 'SANGUE', valor: 120.00 }
                                            ]
                                          })"
                                        >
                                            Ver Detalhes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          <!-- Modal de Seleção de Pagamento -->
        <div v-if="paymentModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <!-- Header do Modal -->
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Escolha a forma de pagamento</h3>
                    <button @click="closePaymentModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Conteúdo do Modal -->
                <div class="p-6">
                    <!-- Informações do Pedido -->
                    <div v-if="selectedPaymentOrder" class="mb-6 text-center">
                        <p class="text-sm text-gray-600 mb-1">
                            Pedido #{{ selectedPaymentOrder.number }} - Cliente: {{ selectedPaymentOrder.cliente }}
                        </p>
                        <p class="text-sm text-gray-500 mb-3">Emitido: {{ selectedPaymentOrder.emitidoEm }}</p>
                        <p class="text-xs text-gray-400 mb-2">Valor a pagar</p>
                        <p class="text-2xl font-bold text-red-500">R$ {{ Number(selectedPaymentOrder.total).toFixed(2).replace('.', ',') }}</p>
                    </div>

                    <!-- Opções de Pagamento -->
                    <div class="space-y-3">
                        <!-- PIX -->
                        <button
                            @click="selectPaymentMethod('pix')"
                            class="w-full flex items-center p-4 border-2 border-red-200 rounded-lg hover:border-red-300 hover:bg-red-50 transition-colors group"
                        >
                            <div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded mr-4">
                                <i class="fab fa-pix text-red-600"></i>
                            </div>
                            <div class="text-left">
                                <p class="font-medium text-gray-900">Pagar com PIX</p>
                                <p class="text-sm text-gray-500">Pagamento instantâneo</p>
                            </div>
                        </button>

                        <!-- Cartão de Crédito -->
                        <button
                            @click="selectPaymentMethod('cartao')"
                            class="w-full flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-gray-300 hover:bg-gray-50 transition-colors"
                        >
                            <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded mr-4">
                                <i class="fas fa-credit-card text-gray-600"></i>
                            </div>
                            <div class="text-left">
                                <p class="font-medium text-gray-900">Cartão de Crédito</p>
                                <p class="text-sm text-gray-500">Até 12x sem juros</p>
                            </div>
                        </button>

                        <!-- Boleto Bancário -->
                        <button
                            @click="selectPaymentMethod('boleto')"
                            class="w-full flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-gray-300 hover:bg-gray-50 transition-colors"
                        >
                            <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded mr-4">
                                <i class="fas fa-barcode text-gray-600"></i>
                            </div>
                            <div class="text-left">
                                <p class="font-medium text-gray-900">Boleto Bancário</p>
                                <p class="text-sm text-gray-500">Vencimento em 3 dias úteis</p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Footer do Modal -->
                <div class="px-6 py-4 border-t bg-gray-50 rounded-b-lg">
                    <button
                        @click="closePaymentModal"
                        class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Cancelar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modais existentes -->
        <!-- Modal PIX -->
        <div v-if="pixModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <!-- Header do Modal -->
                <div class="flex items-center justify-between p-6 border-b">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Pagamento via PIX</h3>
                        <!-- Número do Pedido no Header -->
                        <p v-if="selectedPaymentOrder" class="text-sm text-gray-600 mt-1">
                            Pedido #{{ selectedPaymentOrder.number }} - Escaneie o QR Code ou copie o código
                        </p>
                    </div>
                    <button @click="closePixModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Conteúdo do Modal -->
                <div class="p-6">
                    <!-- Valor a Pagar no Topo -->
                    <div v-if="selectedPaymentOrder" class="mb-6 text-center">
                        <p class="text-xs text-gray-400 mb-2">Valor a pagar</p>
                        <p class="text-2xl font-bold text-red-500">R$ {{ Number(selectedPaymentOrder.total).toFixed(2).replace('.', ',') }}</p>
                    </div>

                    <!-- QR Code -->
                    <div class="flex justify-center mb-6">
                        <div class="border-2 border-red-500 rounded-lg p-4 bg-white">
                            <!-- QR Code placeholder - você pode usar uma biblioteca como qrcode.js -->
                            <div class="w-48 h-48 bg-white flex items-center justify-center">
                                <svg class="w-full h-full" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <!-- QR Code pattern baseado na imagem -->
                                    <rect width="200" height="200" fill="white"/>
                                    <!-- Cantos do QR Code -->
                                    <rect x="10" y="10" width="50" height="50" fill="#C53030" rx="5"/>
                                    <rect x="20" y="20" width="30" height="30" fill="white" rx="2"/>
                                    <rect x="25" y="25" width="20" height="20" fill="#C53030" rx="2"/>
                                    
                                    <rect x="140" y="10" width="50" height="50" fill="#C53030" rx="5"/>
                                    <rect x="150" y="20" width="30" height="30" fill="white" rx="2"/>
                                    <rect x="155" y="25" width="20" height="20" fill="#C53030" rx="2"/>
                                    
                                    <rect x="10" y="140" width="50" height="50" fill="#C53030" rx="5"/>
                                    <rect x="20" y="150" width="30" height="30" fill="white" rx="2"/>
                                    <rect x="25" y="155" width="20" height="20" fill="#C53030" rx="2"/>
                                    
                                    <!-- Padrão de dados do QR Code -->
                                    <g fill="#C53030">
                                        <circle cx="80" cy="30" r="3"/>
                                        <circle cx="90" cy="30" r="3"/>
                                        <circle cx="100" cy="30" r="3"/>
                                        <circle cx="110" cy="30" r="3"/>
                                        <circle cx="120" cy="30" r="3"/>
                                        
                                        <circle cx="30" cy="80" r="3"/>
                                        <circle cx="30" cy="90" r="3"/>
                                        <circle cx="30" cy="100" r="3"/>
                                        <circle cx="30" cy="110" r="3"/>
                                        <circle cx="30" cy="120" r="3"/>
                                        
                                        <circle cx="170" cy="80" r="3"/>
                                        <circle cx="170" cy="90" r="3"/>
                                        <circle cx="170" cy="100" r="3"/>
                                        <circle cx="170" cy="110" r="3"/>
                                        <circle cx="170" cy="120" r="3"/>
                                        
                                        <circle cx="80" cy="170" r="3"/>
                                        <circle cx="90" cy="170" r="3"/>
                                        <circle cx="100" cy="170" r="3"/>
                                        <circle cx="110" cy="170" r="3"/>
                                        <circle cx="120" cy="170" r="3"/>
                                        <circle cx="130" cy="170" r="3"/>
                                        <circle cx="140" cy="170" r="3"/>
                                        <circle cx="150" cy="170" r="3"/>
                                        <circle cx="160" cy="170" r="3"/>
                                        <circle cx="170" cy="170" r="3"/>
                                        
                                        <!-- Padrão central e outros elementos -->
                                        <rect x="80" y="80" width="40" height="40" fill="#C53030" rx="3"/>
                                        <rect x="85" y="85" width="30" height="30" fill="white" rx="2"/>
                                        <rect x="90" y="90" width="20" height="20" fill="#C53030" rx="2"/>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Texto "Ou copie o código PIX" -->
                    <div class="text-center mb-4">
                        <p class="text-sm text-gray-600">Ou copie o código PIX</p>
                    </div>

                    <!-- Código PIX -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-xs text-gray-500 break-all font-mono">{{ pixCode }}</p>
                    </div>

                    <!-- Botão Copiar -->
                    <button 
                        @click="copyPixCode"
                        class="w-full flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition-colors mb-4"
                    >
                        <i class="far fa-copy"></i>
                        Copiar Código PIX
                    </button>

                    <!-- Aviso -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-info-circle text-green-600 mt-0.5"></i>
                            <p class="text-sm text-green-700">
                                Após o pagamento, a confirmação pode levar alguns minutos
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer do Modal -->
                <div class="px-6 py-4 border-t bg-gray-50 rounded-b-lg">
                    <button 
                        @click="closePixModal"
                        class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Fechar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modais existentes -->
        <OrderPaidDetailsModal
            v-if="paidDetailsOpen && selectedOrder"
            :open="paidDetailsOpen"
            :order="selectedOrder"
            @close="paidDetailsOpen = false"
        />

        <OrderUnpaidDetailsModal
            v-if="unpaidDetailsOpen && selectedUnpaidOrder"
            :open="unpaidDetailsOpen"
            :order="selectedUnpaidOrder"
            @close="unpaidDetailsOpen = false"
        />
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { ref } from 'vue';
import OrderPaidDetailsModal from '@/Components/OrderPaidDetailsModal.vue'
import OrderUnpaidDetailsModal from '@/Components/OrderUnpaidDetailsModal.vue'
import NewOwnerModal from '@/Components/NewOwnerModal.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    owner: Object,
    require: true
})

const activeTab = ref('pagos');

const setActiveTab = (tab) => {
    activeTab.value = tab;
}

const paidDetailsOpen = ref(false)
const selectedOrder = ref(null)

const openPaidDetails = (order) => {
    selectedOrder.value = order
    paidDetailsOpen.value = true
}

const unpaidDetailsOpen = ref(false)
const selectedUnpaidOrder = ref(null)

const openUnpaidDetails = (order) => {
  selectedUnpaidOrder.value = order
  unpaidDetailsOpen.value = true
}

// Modal de pagamento
const paymentModalOpen = ref(false)
const selectedPaymentOrder = ref(null)

// Modal de pagamento PIX
const pixModalOpen = ref(false)
const pixCode = ref('00020126580014br.gov.bcb.pix0136a629532e-7393-4d1d-8c2b-e1c34d63e7565204000053039865802BR5924ROSIVAL GENETICS LA6609950A PAULO62070503***63041D3D')

const openPaymentModal = (order) => {
    selectedPaymentOrder.value = order
    paymentModalOpen.value = true
}

const closePaymentModal = () => {
    paymentModalOpen.value = false
    selectedPaymentOrder.value = null
}

const openPixModal = () => {
    pixModalOpen.value = true
}

const closePixModal = () => {
    pixModalOpen.value = false
}

const copyPixCode = () => {
    navigator.clipboard.writeText(pixCode.value).then(() => {
        // Você pode adicionar uma notificação aqui
        console.log('Código PIX copiado!')
    })
}

const selectPaymentMethod = (method) => {
    console.log(`Método de pagamento selecionado: ${method}`)

    if (method === 'pix') {
        closePaymentModal()
        openPixModal()
    } else {
        // Aqui você pode abrir os modais específicos para outros métodos de pagamento
        closePaymentModal()
    }
}
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


