<!-- <script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/Default/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/Default/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Default/Checkbox.vue';
import InputError from '@/Components/Default/InputError.vue';
import InputLabel from '@/Components/Default/InputLabel.vue';
import PrimaryButton from '@/Components/Default/PrimaryButton.vue';
import TextInput from '@/Components/Default/TextInput.vue';

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
    <Head title="Register" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />
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

            <div class="mt-4">
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

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
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

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />
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

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                        <div class="ms-2">
                            I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">Privacy Policy</a>
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.terms" />
                </InputLabel>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template> -->




<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import InputError from '@/Components/Default/InputError.vue'
import InputLabel from '@/Components/Default/InputLabel.vue'
import PrimaryButton from '@/Components/Default/PrimaryButton.vue'
import TextInput from '@/Components/Default/TextInput.vue'
import Checkbox from '@/Components/Default/Checkbox.vue';

const form = useForm({
    name: '',
    email: '',
    role: '',
    password: '',
    password_confirmation: '',
    terms: false,

})

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>

    <Head title="Register" />
    <div class="min-h-screen wrapper lg:py-16 bg-gray-100">
        <div class="container">
            <div class="page-inner">
                <div class="row justify-center items-center">
                    <div class="w-full lg:w-3/4 mx-auto">
                        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                            <div class="flex flex-wrap">
                                <div class="hidden md:block md:w-1/2 p-2">
                                    <img src="frontend/images/register.jpg" alt="Register Image"
                                        class="w-full h-full object-cover rounded-lg" />
                                </div>
                                <div class="w-full md:w-1/2 p-8 lg:my-9">
                                    <h3 class="text-3xl text-center font-semibold mb-6">Register</h3>

                                    <form @submit.prevent="submit">
                                        <div class="mb-4">
                                            <TextInput id="name" v-model="form.name" type="text"
                                                placeholder="Enter name" class="w-full" required autofocus />
                                            <InputError class="mt-2" :message="form.errors.name" />
                                        </div>

                                        <div class="mb-4">
                                            <select v-model="form.role" id="role"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-pink-200">
                                                <option value="">Select Role</option>
                                                <option value="2">Property Agent</option>
                                                <option value="1">End User</option>
                                            </select>
                                            <InputError class="mt-2" :message="form.errors.role" />
                                        </div>

                                        <div class="mb-4">
                                            <TextInput id="email" v-model="form.email" type="email"
                                                placeholder="Enter email" class="w-full" required />
                                            <InputError class="mt-2" :message="form.errors.email" />
                                        </div>

                                        <div class="mb-4">
                                            <TextInput id="password" v-model="form.password" type="password"
                                                placeholder="Enter password" class="w-full" required />
                                            <InputError class="mt-2" :message="form.errors.password" />
                                        </div>

                                        <div class="mb-4">
                                            <TextInput id="password_confirmation" v-model="form.password_confirmation"
                                                type="password" placeholder="Confirm password" class="w-full"
                                                required />
                                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                                        </div>

                                        <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                                            <InputLabel for="terms">
                                                <div class="flex items-center">
                                                    <Checkbox id="terms" v-model:checked="form.terms" name="terms"
                                                        required />

                                                    <div class="ms-2">
                                                        I agree to the <a target="_blank" :href="route('terms.show')"
                                                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">Terms
                                                            of Service</a> and <a target="_blank"
                                                            :href="route('policy.show')"
                                                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">Privacy
                                                            Policy</a>
                                                    </div>
                                                </div>
                                                <InputError class="mt-2" :message="form.errors.terms" />
                                            </InputLabel>
                                        </div>

                                        <div class="flex items-center justify-between mt-6">
                                            <Link :href="route('login')" class="text-sm text-pink-600 hover:underline">
                                            Already registered? Login Here
                                            </Link>

                                            <PrimaryButton class="ml-4" :disabled="form.processing">
                                                Register
                                            </PrimaryButton>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
