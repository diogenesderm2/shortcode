<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    user: Object,
});

const showDeleteModal = ref(false);

// Função para confirmar exclusão
const confirmDelete = () => {
    showDeleteModal.value = true;
};

// Função para deletar usuário
const deleteUser = () => {
    router.delete(route('admin.users.destroy', props.user.id), {
        onSuccess: () => {
            router.visit(route('admin.users.index'));
        }
    });
};

// Função para obter cor do badge da role
const getRoleBadgeColor = (roleName) => {
    const colors = {
        'admin': 'bg-red-100 text-red-800',
        'manager': 'bg-blue-100 text-blue-800',
        'user': 'bg-green-100 text-green-800',
    };
    return colors[roleName] || 'bg-gray-100 text-gray-800';
};

// Agrupar permissões por categoria
const groupedPermissions = props.user.permissions.reduce((groups, permission) => {
    const category = permission.name.split(' ').slice(1).join(' ');
    if (!groups[category]) {
        groups[category] = [];
    }
    groups[category].push(permission);
    return groups;
}, {});
</script>

<template>
    <AppLayout title="Visualizar Usuário">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Usuário: {{ user.name }}
                </h2>
                <div class="flex space-x-2">
                    <a :href="route('admin.users.edit', user.id)" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    <button
                        @click="confirmDelete"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                    >
                        <i class="fas fa-trash mr-2"></i>
                        Deletar
                    </button>
                    <a :href="route('admin.users.index')" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar
                    </a>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <!-- Informações do Usuário -->
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <img class="h-20 w-20 rounded-full mr-6" :src="user.profile_photo_url" :alt="user.name">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ user.name }}</h3>
                                <p class="text-gray-600">{{ user.email }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm text-gray-500 mr-2">Status:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Ativo
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informações Básicas -->
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h4>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Nome</dt>
                                        <dd class="text-sm text-gray-900">{{ user.name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="text-sm text-gray-900">{{ user.email }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email Verificado</dt>
                                        <dd class="text-sm text-gray-900">
                                            <span v-if="user.email_verified_at" class="text-green-600">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Verificado em {{ new Date(user.email_verified_at).toLocaleDateString('pt-BR') }}
                                            </span>
                                            <span v-else class="text-red-600">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Não verificado
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                                        <dd class="text-sm text-gray-900">{{ new Date(user.created_at).toLocaleDateString('pt-BR') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Última atualização</dt>
                                        <dd class="text-sm text-gray-900">{{ new Date(user.updated_at).toLocaleDateString('pt-BR') }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Roles -->
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Roles</h4>
                                <div v-if="user.roles.length > 0" class="space-y-2">
                                    <div
                                        v-for="role in user.roles"
                                        :key="role.id"
                                        :class="getRoleBadgeColor(role.name)"
                                        class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium mr-2 mb-2"
                                    >
                                        <i class="fas fa-user-tag mr-2"></i>
                                        {{ role.name }}
                                    </div>
                                </div>
                                <div v-else class="text-sm text-gray-500">
                                    Nenhuma role atribuída
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissões -->
                    <div class="border-t border-gray-200 p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Permissões</h4>
                        
                        <div v-if="Object.keys(groupedPermissions).length > 0" class="space-y-6">
                            <div v-for="(permissions, category) in groupedPermissions" :key="category">
                                <h5 class="font-medium text-gray-900 mb-3 capitalize">{{ category }}</h5>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                    <div
                                        v-for="permission in permissions"
                                        :key="permission.id"
                                        class="flex items-center space-x-2 p-2 bg-gray-50 rounded"
                                    >
                                        <i class="fas fa-check text-green-600 text-xs"></i>
                                        <span class="text-sm text-gray-700">
                                            {{ permission.name.split(' ')[0] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div v-else class="text-sm text-gray-500">
                            Nenhuma permissão específica atribuída
                        </div>
                    </div>

                    <!-- Estatísticas -->
                    <div class="border-t border-gray-200 p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Estatísticas</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ user.owners_count || 0 }}</div>
                                <div class="text-sm text-gray-600">Proprietários</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">{{ user.roles.length }}</div>
                                <div class="text-sm text-gray-600">Roles</div>
                            </div>
                            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                                <div class="text-2xl font-bold text-yellow-600">{{ user.permissions.length }}</div>
                                <div class="text-sm text-gray-600">Permissões</div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ Math.floor((new Date() - new Date(user.created_at)) / (1000 * 60 * 60 * 24)) }}
                                </div>
                                <div class="text-sm text-gray-600">Dias no sistema</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmação de exclusão -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-4">Confirmar Exclusão</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Tem certeza que deseja deletar o usuário <strong>{{ user.name }}</strong>?
                            Esta ação não pode ser desfeita.
                        </p>
                    </div>
                    <div class="flex justify-center space-x-4 mt-4">
                        <button
                            @click="showDeleteModal = false"
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="deleteUser"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                        >
                            Deletar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>