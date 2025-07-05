<template>
    <AppLayout title="Criar Post">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Criar Post
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <form @submit.prevent="store">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h1 class="text-2xl font-bold">Criar Postsss</h1>
                        <div class="mt-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                            <input type="text" id="title" v-model="form.title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="mt-4">
                            <label for="content" class="block text-sm font-medium text-gray-700">Conteúdo</label>
                            <textarea id="content" v-model="form.content" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>
                        <div class="mt-4">

                            <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                            <select id="category_id" v-model="form.category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Selecione uma categoria</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Criar Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    content: '',
    category_id: '',
});

const props = defineProps({
    categories: Object
});


const store = () => {
    form.post(route('admin.posts.store'));
};
</script>