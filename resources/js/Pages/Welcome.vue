<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Home from './Public/Home.vue';

</script>

<template>

    <Head title="Welcome" />
    <PublicLayout>
        <Home />
    </PublicLayout>
</template>
