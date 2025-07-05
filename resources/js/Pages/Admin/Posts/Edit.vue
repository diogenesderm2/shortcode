<template>
    <AppLayout title="Editar Post">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Editar Post
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6">
                        <!-- Título -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                Título
                            </label>
                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': form.errors.title }"
                            />
                            <div v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <!-- Categoria -->
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">
                                Categoria
                            </label>
                            <select
                                id="category"
                                v-model="form.category_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': form.errors.category_id }"
                            >
                                <option value="">Selecione uma categoria</option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">
                                {{ form.errors.category_id }}
                            </div>
                        </div>

                        <!-- Conteúdo -->
                        <div class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-700">
                                Conteúdo
                            </label>
                            <textarea
                                id="content"
                                v-model="form.content"
                                rows="6"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': form.errors.content }"
                            ></textarea>
                            <div v-if="form.errors.content" class="mt-1 text-sm text-red-600">
                                {{ form.errors.content }}
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end space-x-3">
                            <Link
                                :href="route('admin.posts.index')"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Salvando...' : 'Atualizar Post' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

// Props recebidas do controlador
const props = defineProps({
    post: Object,
    categories: Array
})

// Formulário reativo com os dados iniciais do post
const form = useForm({
    title: props.post.title,
    content: props.post.content,
    category_id: props.post.category_id
})

// Método de envio - CORRIGIDO: passando o ID do post
const submit = () => {
    form.put(route('admin.posts.update', props.post.id))
}
</script>
