<template>
  <div v-if="order" class="fixed inset-0 z-50 flex items-start justify-center">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40" @click="emit('close')"></div>

    <!-- Modal -->
    <div class="relative bg-white rounded-2xl shadow-xl w-[92vw] max-w-4xl mt-10 p-6">
      <!-- Header -->
      <div class="flex items-center justify-between mb-3">
        <div>
          <h2 class="text-xl font-bold text-gray-900">
            Pedido #{{ order.number }}
            <span class="ml-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full">Pago</span>
          </h2>
          <p class="text-sm text-gray-500">Detalhes completos do pedido</p>
        </div>
        <button @click="emit('close')" class="text-gray-400 hover:text-gray-600">
          <i class="fas fa-times text-lg"></i>
        </button>
      </div>

      <!-- Cliente -->
      <div class="mt-6">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Informações do Cliente</h3>
        <div class="flex items-center gap-3 text-gray-800">
          <i class="fas fa-user text-pink-500"></i>
          <span>{{ order.cliente }}</span>
        </div>
      </div>

      <!-- Valores -->
      <div class="mt-6">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Valores</h3>
        <div class="flex items-center justify-between max-w-md">
          <div class="text-gray-700">Valor do Pedido</div>
          <div class="font-semibold text-pink-600">{{ formatCurrency(order.total) }}</div>
        </div>
        <div class="flex items-center justify-between max-w-md mt-2">
          <div class="text-gray-700">Valor Pago</div>
          <div class="font-semibold text-teal-500">{{ formatCurrency(order.total) }}</div>
        </div>
      </div>

      <!-- Datas -->
      <div class="mt-6">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Datas</h3>
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <i class="far fa-calendar text-pink-500"></i>
            <div class="text-gray-700">
              <div class="text-xs text-gray-500">Data de Emissão</div>
              <div class="text-sm">{{ order.emitidoEm }}</div>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <i class="far fa-calendar-check text-teal-500"></i>
            <div class="text-gray-700">
              <div class="text-xs text-gray-500">Data de Pagamento</div>
              <div class="text-sm">{{ order.pagoEm }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Forma de pagamento -->
      <div class="mt-6">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Forma de Pagamento</h3>
        <div class="flex items-center gap-3 text-gray-800">
          <i v-if="order.pagamento === 'PIX'" class="fab fa-pix text-teal-600"></i>
          <i v-else-if="order.pagamento.includes('CRÉDITO')" class="fas fa-credit-card text-blue-600"></i>
          <i v-else class="fas fa-money-bill text-yellow-600"></i>
          <span>{{ order.pagamento }}</span>
        </div>
      </div>

      <!-- Animais -->
      <div class="mt-8">
        <h3 class="text-sm font-medium text-gray-500 mb-3">Animais</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="text-left text-gray-500">
                <th class="py-2 px-2">Ord</th>
                <th class="py-2 px-2">Entrada</th>
                <th class="py-2 px-2">RG</th>
                <th class="py-2 px-2">Nome</th>
                <th class="py-2 px-2">Raça</th>
                <th class="py-2 px-2">Espécie</th>
                <th class="py-2 px-2">Tipo</th>
                <th class="py-2 px-2 text-right">Valor</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(a, idx) in order.animais" :key="idx" class="border-t text-gray-800">
                <td class="py-2 px-2">{{ a.ord }}</td>
                <td class="py-2 px-2">{{ a.entrada }}</td>
                <td class="py-2 px-2">{{ a.rg }}</td>
                <td class="py-2 px-2">{{ a.nome }}</td>
                <td class="py-2 px-2">{{ a.raca }}</td>
                <td class="py-2 px-2">{{ a.especie }}</td>
                <td class="py-2 px-2">{{ a.tipo }}</td>
                <td class="py-2 px-2 text-right">{{ formatCurrency(a.valor) }}</td>
              </tr>
              <tr class="border-t">
                <td colspan="7" class="py-2 px-2 text-right font-medium text-gray-700">Total</td>
                <td class="py-2 px-2 text-right font-bold text-pink-600">{{ formatCurrency(order.total) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  order: { type: Object, required: true }
});
const emit = defineEmits(['close']);

const formatCurrency = (v) =>
  new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(v ?? 0);
</script>