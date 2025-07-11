<template>
    <AppLayout title="Posts">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Todos os posts
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div v-if="$page.props.flash.message"
                    class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ $page.props.flash.message }}
                </div>

                <!-- Botão Novo Post -->
                <div class="mb-6">
                    <Link :href="route('admin.posts.create')"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Novo Post
                    </Link>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="posts.data.length === 0" class="text-center text-gray-500">
                            Nenhum post encontrado.
                        </div>

                        <div v-else class="space-y-4">
                            <div v-for="post in posts.data" :key="post.id"
                                class="border rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-semibold">{{ post.title }}</h3>
                                        <p class="text-gray-600 mt-1">{{ post.content.substring(0, 150) }}...</p>
                                        <div class="flex items-center mt-2 text-sm text-gray-500">
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                {{ post.category.name }}
                                            </span>
                                            <span class="ml-4">Por: {{ post.user.name }}</span>
                                            <span class="ml-4">{{ formatDate(post.created_at) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <Link :href="route('admin.posts.edit', post.id)"
                                            class="text-blue-600 hover:text-blue-800 p-1 rounded transition-colors" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </Link>
                                        <button @click="deletePost(post.id)" class="text-red-600 hover:text-red-800 p-1 rounded transition-colors" title="Excluir">
                                            <TrashIcon class="w-5 h-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';



// Props recebidas do controlador
const props = defineProps({
    posts: Object
})



const deletePost = (id) => {
    if (confirm('Tem certeza que deseja excluir este post?')) {
        router.delete(route('admin.posts.destroy', id))
    }
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('pt-BR')
}
</script>
