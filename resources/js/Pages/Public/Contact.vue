<template lang="">
    <HeroSection variant="page" heading="Contact Us" :breadcrumbs="[
        { label: 'Home', link: '/' },
        { label: 'Contact Us' }
    ]" />
    <div>
        Contact Page
    </div>
</template>
<script setup>
import HeroSection from '@/Components/Public/Common/HeroSection.vue'

import PublicLayout from '@/Layouts/PublicLayout.vue';
defineOptions({ layout: PublicLayout })
</script>

<style lang="">

</style>