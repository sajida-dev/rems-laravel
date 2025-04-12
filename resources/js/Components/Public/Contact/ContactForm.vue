<template>
    <div class="bg-gray-50 p-6 rounded-md shadow-md w-full">
        <h2 class="text-2xl md:text-3xl font-semibold text-center mb-6">
            Got any questions?<br />
            <span class="text-pink-600">Don't hesitate to message us!</span>
        </h2>
        <form @submit.prevent="submitForm" class="space-y-4">
            <input v-model="form.name" type="text" placeholder="Your Name" class="form-input" />
            <input v-model="form.email" type="email" placeholder="Your Email" class="form-input" />
            <input v-model="form.subject" type="text" placeholder="Subject" class="form-input" />
            <textarea v-model="form.message" rows="5" placeholder="Message" class="form-input"></textarea>
            <button type="submit" class="btn btn-primary w-full py-2">Send Message</button>
        </form>
    </div>
</template>

<script setup>
import { reactive } from 'vue'

const form = reactive({
    name: '',
    email: '',
    subject: '',
    message: ''
})

function submitForm() {
    // You can emit or use axios here
    alert('Form submitted!')
}
</script>

<style scoped>
.form-input {
    @apply w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500;
}

.btn-primary {
    @apply bg-pink-600 text-white font-semibold rounded hover:bg-pink-700 transition;
}
</style>
