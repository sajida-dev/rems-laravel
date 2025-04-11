<template lang="">
<HeroSection variant="page" heading="Agent" :breadcrumbs="[
        { label: 'Home', link: '/' },
        { label: 'Agent' }
    ]" />
    <div>
        Agent page
    </div>
</template>
<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import HeroSection from '@/Components/Public/Common/HeroSection.vue'

defineOptions({ layout: PublicLayout })
</script>
<style lang="">

</style>