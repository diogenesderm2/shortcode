<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#C1272D] via-white to-[#222]">
        <!-- Container Principal -->
        <div class="max-w-md w-full space-y-8">
            <!-- Logo e Título -->
            <div class="text-center">
                <AuthenticationCardLogo />
                <h2 class="mt-6 text-3xl font-extrabold text-[#C1272D]">
                    Laboratório Raça
                </h2>
                <p class="mt-2 text-sm text-gray-700">
                    Acesse sua conta para continuar
                </p>
            </div>

            <!-- Card do Formulário -->
            <div class="bg-white py-8 px-6 shadow-xl rounded-xl border border-gray-100">
                <!-- Status Message -->
                <div v-if="status" class="mb-6 p-4 bg-red-50 border border-[#C1272D] rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-[#C1272D]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-[#C1272D]">
                                {{ status }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Formulário -->
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Campo Email -->
                    <div>
                        <InputLabel for="email" value="Email" class="block text-sm font-medium text-gray-700 mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="pl-10 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-[#C1272D] transition duration-150 ease-in-out"
                                placeholder="seu.email@exemplo.com"
                                required
                                autofocus
                                autocomplete="username"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Campo Senha -->
                    <div>
                        <InputLabel for="password" value="Senha" class="block text-sm font-medium text-gray-700 mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="pl-10 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-[#C1272D] transition duration-150 ease-in-out"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Lembrar de mim -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <Checkbox v-model:checked="form.remember" name="remember" class="h-4 w-4 text-[#C1272D] focus:ring-[#C1272D] border-gray-300 rounded" />
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Lembrar de mim
                            </label>
                        </div>

                        <div v-if="canResetPassword" class="text-sm">
                            <Link :href="route('password.request')" class="font-medium text-[#C1272D] hover:text-[#a31e23] transition duration-150 ease-in-out">
                                Esqueceu a senha?
                            </Link>
                        </div>
                    </div>

                    <!-- Botão de Login -->
                    <div>
                        <PrimaryButton
                            type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-[#C1272D] hover:bg-[#a31e23] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C1272D] transition duration-150 ease-in-out shadow-lg"
                            :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing" class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            {{ form.processing ? 'Entrando...' : 'Entrar' }}
                        </PrimaryButton>
                    </div>
                </form>

                <!-- Social Login Divider -->
                <div class="flex items-center my-6">
                    <div class="flex-grow border-t border-gray-200"></div>
                    <span class="mx-4 text-gray-400 text-sm">ou continue com</span>
                    <div class="flex-grow border-t border-gray-200"></div>
                </div>

                <!-- Botão Google -->
                <Link href="/auth/google" class="w-full flex items-center justify-center gap-2 border border-gray-300 rounded-lg py-3 mb-3 bg-white hover:bg-gray-50 transition">
                    <svg class="h-5 w-5" viewBox="0 0 48 48"><g><path fill="#4285F4" d="M24 9.5c3.54 0 6.7 1.22 9.19 3.23l6.85-6.85C35.64 2.36 30.18 0 24 0 14.82 0 6.44 5.48 2.23 13.44l7.98 6.2C12.13 13.09 17.61 9.5 24 9.5z"/><path fill="#34A853" d="M46.1 24.55c0-1.64-.15-3.22-.43-4.74H24v9.01h12.42c-.54 2.9-2.18 5.36-4.65 7.01l7.19 5.59C43.98 37.13 46.1 31.36 46.1 24.55z"/><path fill="#FBBC05" d="M10.21 28.65c-1.13-3.36-1.13-6.99 0-10.35l-7.98-6.2C.8 16.14 0 19.01 0 22c0 2.99.8 5.86 2.23 8.44l7.98-6.2z"/><path fill="#EA4335" d="M24 44c6.18 0 11.64-2.04 15.54-5.54l-7.19-5.59c-2.01 1.35-4.6 2.13-8.35 2.13-6.39 0-11.87-3.59-13.79-8.73l-7.98 6.2C6.44 42.52 14.82 48 24 48z"/><path fill="none" d="M0 0h48v48H0z"/></g></svg>
                    <span class="font-medium text-gray-700">Continuar com Google</span>
                </Link>

                <!-- Botão Apple -->
                <Link href="/auth/apple" class="w-full flex items-center justify-center gap-2 border border-gray-900 rounded-lg py-3 bg-black hover:bg-gray-900 transition mb-1">
                    <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M16.365 1.43c0 1.14-.93 2.06-2.07 2.06-.02-1.18.95-2.06 2.07-2.06zm4.7 16.57c-.06-3.36 2.74-4.97 2.86-5.05-1.57-2.3-4.01-2.62-4.87-2.65-2.07-.21-4.04 1.21-5.09 1.21-1.06 0-2.7-1.18-4.45-1.15-2.29.03-4.41 1.33-5.59 3.39-2.39 4.13-.61 10.24 1.7 13.6 1.13 1.62 2.47 3.44 4.23 3.37 1.7-.07 2.34-1.09 4.39-1.09 2.05 0 2.62 1.09 4.41 1.06 1.83-.03 2.98-1.65 4.09-3.28 1.29-1.89 1.82-3.72 1.85-3.81-.04-.02-3.56-1.37-3.62-5.44zm-6.13-10.3c1.13-1.37.95-2.62.92-2.74-1.01-.04-2.19.67-2.9 1.51-.64.75-1.2 1.95-.99 3.09 1.05.08 2.13-.53 2.97-1.86z"/></svg>
                    <span class="font-medium text-white">Continuar com Apple</span>
                </Link>

                <!-- Link para Registro -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-700">
                        Não possui uma conta?
                        <Link :href="route('register')" class="font-medium text-[#C1272D] hover:text-[#a31e23] transition duration-150 ease-in-out">
                            Cadastre-se
                        </Link>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    © 2024 Laboratório Raça. Todos os direitos reservados.
                </p>
            </div>
        </div>
    </div>
</template>
