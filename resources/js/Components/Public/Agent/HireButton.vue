<template>
    <Link :href="link"
        class="btn-custom bg-pink-500 hover:bg-transparent text-white hover:border hover:border-pink-500 transition px-5 py-2 rounded font-semibold inline-flex items-center">
    <i class="fas fa-phone mr-2"></i> Hire Now
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    link: String
})
</script>
