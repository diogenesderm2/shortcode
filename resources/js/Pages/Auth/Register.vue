<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Registro" />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#C1272D] via-white to-[#222]">
        <!-- Container Principal -->
        <div class="max-w-md w-full space-y-8">
            <!-- Logo e Título -->
            <div class="text-center">
                <AuthenticationCardLogo />
                <h2 class="mt-6 text-3xl font-extrabold text-[#C1272D]">
                    Criar conta
                </h2>
                <p class="mt-2 text-sm text-gray-700">
                    Preencha os dados abaixo para criar sua conta
                </p>
            </div>

            <!-- Card do Formulário -->
            <div class="bg-white py-8 px-6 shadow-xl rounded-xl border border-gray-100">
                <!-- Formulário -->
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Campo Nome -->
                    <div>
                        <InputLabel for="name" value="Nome completo" class="block text-sm font-medium text-gray-700 mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="pl-10 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-[#C1272D] transition duration-150 ease-in-out"
                                placeholder="Seu nome completo"
                                required
                                autofocus
                                autocomplete="name"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

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
                                placeholder="seu@email.com"
                                required
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
                                autocomplete="new-password"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Campo Confirmar Senha -->
                    <div>
                        <InputLabel for="password_confirmation" value="Confirmar senha" class="block text-sm font-medium text-gray-700 mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <TextInput
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="pl-10 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-[#C1272D] transition duration-150 ease-in-out"
                                placeholder="••••••••"
                                required
                                autocomplete="new-password"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <!-- Termos e Condições -->
                    <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="flex items-start">
                        <div class="flex items-center h-5">
                            <Checkbox
                                id="terms"
                                v-model:checked="form.terms"
                                name="terms"
                                required
                                class="h-4 w-4 text-[#C1272D] focus:ring-[#C1272D] border-gray-300 rounded"
                            />
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="text-gray-700">
                                Concordo com os
                                <a target="_blank" :href="route('terms.show')" class="font-medium text-[#C1272D] hover:text-[#a31e23] transition duration-150 ease-in-out">
                                    Termos de Serviço
                                </a>
                                e
                                <a target="_blank" :href="route('policy.show')" class="font-medium text-[#C1272D] hover:text-[#a31e23] transition duration-150 ease-in-out">
                                    Política de Privacidade
                                </a>
                            </label>
                        </div>
                        <InputError class="mt-2" :message="form.errors.terms" />
                    </div>

                    <!-- Botão de Registro -->
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
                            {{ form.processing ? 'Criando conta...' : 'Criar conta' }}
                        </PrimaryButton>
                    </div>
                </form>

                <!-- Link para Login -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-700">
                        Já tem uma conta?
                        <Link :href="route('login')" class="font-medium text-[#C1272D] hover:text-[#a31e23] transition duration-150 ease-in-out">
                            Faça login aqui
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
