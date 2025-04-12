<template lang="">
    <HeroSection variant="page" heading="Services" :breadcrumbs="[
        { label: 'Home', link: '/' },
        { label: 'Services' }
    ]" />
    <ServicesSection />
    <WorkFlowSection />

</template>
<script setup>
import WorkFlowSection from '@/Components/Public/Services/WorkFlowSection.vue'
import HeroSection from '@/Components/Public/Common/HeroSection.vue'
import ServicesSection from '@/Components/Public/Services/ServicesSection.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
defineOptions({ layout: PublicLayout })
</script>

<style lang="">

</style>