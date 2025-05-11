<template>

    <Head title="Welcome" />
    <HeroSection variant="home" heading="The Simplest Way to Find Property"
        subheading="A small river named Duden flows by their place and supplies it with the necessary regelialia."
        showSearchForm formAction="filter-properties.php" />
    <ServicesSection />
    <PropertiesSection :properties="properties" />
    <WorkFlowSection />
    <AboutUsSection />
    <CounterSection />
    <AgentSection :agents="agents" />
</template>
<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
defineOptions({ layout: PublicLayout })
import { Head } from '@inertiajs/vue3'
import PropertiesSection from '@/Components/Public/Property/PropertiesSection.vue'
import WorkFlowSection from '@/Components/Public/Services/WorkFlowSection.vue'
import AgentSection from '@/Components/Public/Agent/AgentSection.vue'
import AboutUsSection from '@/Components/Public/About/AboutUsSection.vue'
import CounterSection from '@/Components/Public/About/CounterSection.vue'
import HeroSection from '@/Components/Public/Common/HeroSection.vue'
import ServicesSection from '@/Components/Public/Services/ServicesSection.vue';

const props = defineProps({
    properties: Object,
    agents: Object
})

</script>
