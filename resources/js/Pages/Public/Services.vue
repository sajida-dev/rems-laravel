<template lang="">
    <HeroSection variant="page" heading="Services" :breadcrumbs="[
        { label: 'Home', link: '/' },
        { label: 'Services' }
    ]" />
    <div>
        Services page
    </div>
</template>
<script setup>
import HeroSection from '@/Components/Public/Common/HeroSection.vue'

import PublicLayout from '@/Layouts/PublicLayout.vue';
defineOptions({ layout: PublicLayout })
</script>

<style lang="">

</style>