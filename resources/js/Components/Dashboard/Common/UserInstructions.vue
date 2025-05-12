<template>
    <div class="p-6 mt-5 bg-white rounded-2xl shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">
            Instructions
        </h2>

        <div class="text-gray-700 space-y-2 text-base leading-relaxed">
            <template v-if="user.role === 'agent'">
                <p>✔️ Complete your profile with accurate personal and professional information.</p>
                <p>✔️ Submit your profile for admin approval.</p>
                <p>✔️ After verification, you will receive an email with your <strong>username</strong> and
                    <strong>password</strong>.
                </p>
                <p>✔️ Once approved, log in to your dashboard.</p>
                <p>✔️ You can now <strong>add and manage property listings</strong>.</p>
            </template>

            <template v-else-if="user.role === 'endUser'">
                <p>✔️ Browse available properties listed by trusted agents.</p>
                <p>✔️ Use filters to narrow down properties based on your preferences.</p>
                <p>✔️ You can create an account to save properties, contact agents, and leave reviews.</p>
                <p>✔️ Enjoy a personalized experience based on your activity and interests.</p>
            </template>

            <template v-else>
                <p>Please select a valid user type.</p>
            </template>
        </div>
    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
const { props } = usePage();
const user = props.auth.user;

</script>
