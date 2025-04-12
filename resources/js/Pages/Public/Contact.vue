<template lang="">
    <HeroSection variant="page" heading="Contact Us" :breadcrumbs="[
        { label: 'Home', link: '/' },
        { label: 'Contact Us' }
    ]" />
    <ContactSection />
</template>
<script setup>
import HeroSection from '@/Components/Public/Common/HeroSection.vue'
import ContactSection from '@/Components/Public/Contact/ContactSection.vue'
import PublicLayout from '@/Layouts/PublicLayout.vue';
defineOptions({ layout: PublicLayout })
</script>

<style lang="">

</style>