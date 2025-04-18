<template>
    <div class="page-header py-4 px-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center">
        <h4 class="text-xl font-semibold text-gray-800">{{ title }}</h4>
        <ul class="flex items-center text-sm text-gray-600 space-x-2 mt-2 sm:mt-0">
            <li>
                <Link href="/">
                <i class="icon-home text-lg" />
                </Link>
            </li>
            <li>
                <i class="icon-arrow-right text-xs text-gray-400" />
            </li>
            <li>
                <span>{{ mainPage }}</span>
            </li>
            <li>
                <i class="icon-arrow-right text-xs text-gray-400" />
            </li>
            <li>
                <span>{{ page }}</span>
            </li>
        </ul>
    </div>
</template>

<script setup>
defineProps({
    title: String,
    mainPage: String,
    page: String
})

import { Link } from '@inertiajs/vue3'
</script>
