<template>
    <AppLayout title="Categorias">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categorias
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div v-if="$page.props.flash.message"
                    class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ $page.props.flash.message }}
                </div>


            <div class="mb-6">
                <Link :href="route('admin.categorias.create')"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                + Nova Categoria
                </Link>
            </div>
        </div>
        </div>

    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
</script>
