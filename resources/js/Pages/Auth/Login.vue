<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/Default/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/Default/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Default/Checkbox.vue';
import InputError from '@/Components/Default/InputError.vue';
import InputLabel from '@/Components/Default/InputLabel.vue';
import PrimaryButton from '@/Components/Default/PrimaryButton.vue';
import TextInput from '@/Components/Default/TextInput.vue';
import { toast } from 'vue3-toastify'

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onSuccess: (responce) => {
            toast.success("Login Successfully.")
            form.reset('password')
        },
        onError: (error) => {
            toast.error("Try Again. " + error)
        }
    });
};
</script>

<template>

    <Head title="Log in" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />

        </template>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="username" value="username" />
                <TextInput id="username" v-model="form.username" type="text" class="mt-1 block w-full" required
                    autofocus autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.username" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required
                    autocomplete="current-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Forgot your password?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>

            </div>
            <div class="flex items-center justify-center mt-6">
                <Link :href="route('register')" class="text-sm text-pink-600 hover:underline">
                Don't have an account? Register Here
                </Link>
            </div>
        </form>
    </AuthenticationCard>
</template>
