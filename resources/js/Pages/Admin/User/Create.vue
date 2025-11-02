<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    roles: Array,
    permissions: Array,
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: [],
    permissions: [],
});

const submit = () => {
    form.post(route('admin.users.store'));
};

// Função para alternar role
const toggleRole = (roleName) => {
    const index = form.roles.indexOf(roleName);
    if (index > -1) {
        form.roles.splice(index, 1);
    } else {
        form.roles.push(roleName);
    }
};

// Função para alternar permissão
const togglePermission = (permissionName) => {
    const index = form.permissions.indexOf(permissionName);
    if (index > -1) {
        form.permissions.splice(index, 1);
    } else {
        form.permissions.push(permissionName);
    }
};

// Função para obter cor do badge da role
const getRoleBadgeColor = (roleName) => {
    const colors = {
        'admin': 'bg-red-100 text-red-800 border-red-200',
        'manager': 'bg-blue-100 text-blue-800 border-blue-200',
        'user': 'bg-green-100 text-green-800 border-green-200',
    };
    return colors[roleName] || 'bg-gray-100 text-gray-800 border-gray-200';
};

// Agrupar permissões por categoria
const groupedPermissions = props.permissions.reduce((groups, permission) => {
    const category = permission.name.split(' ').slice(1).join(' ');
    if (!groups[category]) {
        groups[category] = [];
    }
    groups[category].push(permission);
    return groups;
}, {});
</script>

<template>
    <AppLayout title="Criar Usuário">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Criar Novo Usuário
                </h2>
                <a :href="route('admin.users.index')" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Informações Básicas -->
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>
                                </div>

                                <!-- Nome -->
                                <div>
                                    <InputLabel for="name" value="Nome" />
                                    <TextInput
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                        autofocus
                                        autocomplete="name"
                                    />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <InputLabel for="email" value="Email" />
                                    <TextInput
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        required
                                        autocomplete="username"
                                    />
                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>

                                <!-- Senha -->
                                <div>
                                    <InputLabel for="password" value="Senha" />
                                    <TextInput
                                        id="password"
                                        v-model="form.password"
                                        type="password"
                                        class="mt-1 block w-full"
                                        required
                                        autocomplete="new-password"
                                    />
                                    <InputError class="mt-2" :message="form.errors.password" />
                                </div>

                                <!-- Confirmar Senha -->
                                <div>
                                    <InputLabel for="password_confirmation" value="Confirmar Senha" />
                                    <TextInput
                                        id="password_confirmation"
                                        v-model="form.password_confirmation"
                                        type="password"
                                        class="mt-1 block w-full"
                                        required
                                        autocomplete="new-password"
                                    />
                                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                                </div>

                                <!-- Roles -->
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Roles</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div
                                            v-for="role in roles"
                                            :key="role.id"
                                            class="relative"
                                        >
                                            <label
                                                :class="[
                                                    'flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all',
                                                    form.roles.includes(role.name)
                                                        ? getRoleBadgeColor(role.name) + ' border-current'
                                                        : 'bg-white border-gray-200 hover:border-gray-300'
                                                ]"
                                            >
                                                <input
                                                    type="checkbox"
                                                    :checked="form.roles.includes(role.name)"
                                                    @change="toggleRole(role.name)"
                                                    class="sr-only"
                                                />
                                                <div class="flex-1">
                                                    <div class="font-medium">{{ role.name }}</div>
                                                    <div class="text-sm text-gray-500 mt-1">
                                                        {{ role.permissions?.length || 0 }} permissões
                                                    </div>
                                                </div>
                                                <div v-if="form.roles.includes(role.name)" class="ml-2">
                                                    <i class="fas fa-check text-current"></i>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <InputError class="mt-2" :message="form.errors.roles" />
                                </div>

                                <!-- Permissões Específicas -->
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Permissões Específicas</h3>
                                    <p class="text-sm text-gray-600 mb-4">
                                        Selecione permissões específicas além das roles (opcional)
                                    </p>
                                    
                                    <div class="space-y-6">
                                        <div v-for="(permissions, category) in groupedPermissions" :key="category">
                                            <h4 class="font-medium text-gray-900 mb-3 capitalize">{{ category }}</h4>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                                <label
                                                    v-for="permission in permissions"
                                                    :key="permission.id"
                                                    class="flex items-center space-x-2 p-2 rounded hover:bg-gray-50 cursor-pointer"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        :checked="form.permissions.includes(permission.name)"
                                                        @change="togglePermission(permission.name)"
                                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                    />
                                                    <span class="text-sm text-gray-700">
                                                        {{ permission.name.split(' ')[0] }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <InputError class="mt-2" :message="form.errors.permissions" />
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                            <a
                                :href="route('admin.users.index')"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                            >
                                Cancelar
                            </a>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                <i class="fas fa-save mr-2"></i>
                                Criar Usuário
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>