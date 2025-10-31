<script setup >
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    status: {
        type: String,
        default: 'pending',
    },
})
const status = ref(props.status);

// Trigger processing via plain JSON request (not Inertia)
const process = async () => {
    await axios.post(route('admin.reports.process'));
};

onMounted(() => {
    // Confirma ciclo de vida
    console.log('Reports onMounted');

    // Mostra objeto Echo e opções internas
    console.log('Echo:', window.Echo);
    console.log('Echo options:', window.Echo?.connector?.options);

    // Bind em mudanças de estado e erros da conexão
    const conn = window.Echo?.connector?.pusher?.connection;
    conn?.bind('state_change', (states) => console.log('WS state change:', states));
    conn?.bind('error', (err) => console.error('WS error:', err));

    // Inscreve no canal e escuta evento
    window.Echo?.channel('reports')
        .listen('.ReportProcessed', (e) => {
            console.log('ReportProcessed payload:', e);
            alert('ReportProcessed received');
            if (e?.status === 'done') {
                status.value = 'done';
            }
        });
});
</script>

<template>
    <AppLayout title="Relatórios">
       <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Relatórios
            </h2>
        </template>

        <div class="mt-2">
            <span
                :class="[
                    'inline-flex h-4 w-4 rounded-full',
                    status === 'pending' ? 'bg-red-500' : 'bg-green-500'
                ]"
            ></span>
        </div>

        <div class="flex justify-end">
            <button @click="process" class="bg-blue-500 text-white px-4 py-2 rounded-md">Processar relatorios</button>
        </div>
    </AppLayout>
</template>
