<template>
    <div class="text-center py-6 md:py-4" :class="borderClass">
        <div class="text-pink-600 text-2xl mb-2">
            <i :class="icon"></i>
        </div>
        <p>
            <span class="block text-lg font-semibold text-black">{{ label }}:</span>
            <Link v-if="link" :href="link" class="text-gray-800 hover:text-pink-600">{{ value }}</Link>
            <span v-else class="text-gray-700">{{ value }}</span>
        </p>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    icon: String,
    label: String,
    value: String,
    link: String,
    borderClass: {
        type: String,
        default: ''
    }
})
</script>
