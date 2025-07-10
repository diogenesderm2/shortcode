<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Esqueci a senha" />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#C1272D] via-white to-[#222]">
        <!-- Container Principal -->
        <div class="max-w-md w-full space-y-8">
            <!-- Logo e Título -->
            <div class="text-center">
                <AuthenticationCardLogo />
                <h2 class="mt-6 text-3xl font-extrabold text-[#C1272D]">
                    Esqueceu a senha?
                </h2>
                <p class="mt-2 text-sm text-gray-700">
                    Não se preocupe! Enviaremos um link para redefinir sua senha
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

                <!-- Descrição -->
                <div class="mb-6 text-sm text-gray-700 text-center">
                    Digite seu endereço de email e enviaremos um link para redefinir sua senha.
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
                                placeholder="seu@email.com"
                                required
                                autofocus
                                autocomplete="username"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Botão de Envio -->
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
                            {{ form.processing ? 'Enviando...' : 'Enviar link de redefinição' }}
                        </PrimaryButton>
                    </div>
                </form>

                <!-- Link para Login -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-700">
                        Lembrou sua senha?
                        <Link :href="route('login')" class="font-medium text-[#C1272D] hover:text-[#a31e23] transition duration-150 ease-in-out">
                            Voltar ao login
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
