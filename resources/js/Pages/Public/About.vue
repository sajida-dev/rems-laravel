<template>

    <Head title="About Us" />
    <HeroSection variant="page" heading="About Us" :breadcrumbs="[
        { label: 'Home', link: '/' },
        { label: 'About' }
    ]" />
    <AboutUsSection />
    <CounterSection />
</template>
<script setup>
import { onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue';
import HeroSection from '@/Components/Public/Common/HeroSection.vue'
import AboutUsSection from '@/Components/Public/About/AboutUsSection.vue';
import CounterSection from '@/Components/Public/About/CounterSection.vue';
defineOptions({ layout: PublicLayout })

onMounted(() => {
    document.title = 'About Us | Uptown'
})
</script>

<script>
export default {
    name: "About",
    components: {
        HeroSection,
        AboutUsSection,
        CounterSection,
    }
}
</script>