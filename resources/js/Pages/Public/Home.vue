<template>

    <HeroSection variant="home" heading="The Simplest Way to Find Property"
        subheading="A small river named Duden flows by their place and supplies it with the necessary regelialia."
        showSearchForm formAction="filter-properties.php" />
    <ServicesSection />
</template>
<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
defineOptions({ layout: PublicLayout })

import HeroSection from '@/Components/Public/Common/HeroSection.vue'
import ServicesSection from '@/Components/Public/Home/ServicesSection.vue';
</script>
