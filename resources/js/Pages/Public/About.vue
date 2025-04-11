<template>
    <HeroSection variant="page" heading="About Us" :breadcrumbs="[
        { label: 'Home', link: '/' },
        { label: 'About' }
    ]" />
    <AboutUsSection />
</template>
<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
defineOptions({ layout: PublicLayout })

import HeroSection from '@/Components/Public/Common/HeroSection.vue'
import AboutUsSection from '@/Components/Public/About/AboutUsSection.vue';
</script>
