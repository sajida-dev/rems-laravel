<template>
    <title>About</title>
    <HeroSection variant="page" heading="About Us" :breadcrumbs="[
        { label: 'Home', link: '/' },
        { label: 'About' }
    ]" />
    <AboutUsSection />
    <CounterSection />
</template>
<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
defineOptions({ layout: PublicLayout })
import { onMounted } from 'vue'

import HeroSection from '@/Components/Public/Common/HeroSection.vue'
import AboutUsSection from '@/Components/Public/About/AboutUsSection.vue';
import CounterSection from '@/Components/Public/About/CounterSection.vue';

onMounted(() => {
    document.title = 'About Us | Uptown'
})
</script>