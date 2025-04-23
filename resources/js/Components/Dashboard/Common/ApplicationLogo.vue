<template>
    <Link href="/" class="inline-flex">
    <h2 class="text-3xl font-bold text-pink-400">Uptown</h2>

    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { useAttrs } from 'vue'

const attrs = useAttrs()
</script>