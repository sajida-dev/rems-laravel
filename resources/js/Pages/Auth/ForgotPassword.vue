<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/Default/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/Default/AuthenticationCardLogo.vue';
import InputError from '@/Components/Default/InputError.vue';
import InputLabel from '@/Components/Default/InputLabel.vue';
import PrimaryButton from '@/Components/Default/PrimaryButton.vue';
import TextInput from '@/Components/Default/TextInput.vue';

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

    <Head title="Forgot Password" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <!-- <div class="mb-4 text-sm text-gray-600">
            Enter your registered email.
        </div> -->

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Enter your registered Email" />
                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autofocus
                    autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
