<template>

    <HeroSection variant="home" heading="The Simplest Way to Find Property"
        subheading="A small river named Duden flows by their place and supplies it with the necessary regelialia."
        showSearchForm formAction="filter-properties.php" />
    <ServicesSection />
    <PropertiesSection :properties="properties" />
    <WorkFlowSection />
    <AboutUsSection />
    <CounterSection />
    <AgentSection />
</template>
<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
defineOptions({ layout: PublicLayout })

import PropertiesSection from '@/Components/Public/Property/PropertiesSection.vue'
import WorkFlowSection from '@/Components/Public/Services/WorkFlowSection.vue'
import AgentSection from '@/Components/Public/Agent/AgentSection.vue'
import AboutUsSection from '@/Components/Public/About/AboutUsSection.vue'
import CounterSection from '@/Components/Public/About/CounterSection.vue'
import HeroSection from '@/Components/Public/Common/HeroSection.vue'
import ServicesSection from '@/Components/Public/Services/ServicesSection.vue';



const properties = [
    {
        id: 1,
        title: 'Modern Family House',
        location: 'Los Angeles, CA',
        purchase_price: 2500,
        rent_price: 1800,
        bedrooms: 3,
        bathrooms: 2,
        area: 1600,
        image_url: 'frontend/images/image_1.jpg'
    },
    {
        id: 2,
        title: 'Luxury Villa in Bali',
        location: 'Bali, Indonesia',
        purchase_price: 4000,
        rent_price: 3200,
        bedrooms: 5,
        bathrooms: 4,
        area: 3200,
        image_url: 'frontend/images/image_2.jpg'
    },
    {
        id: 3,
        title: 'Cozy Apartment',
        location: 'New York, NY',
        purchase_price: 1900,
        rent_price: 1500,
        bedrooms: 2,
        bathrooms: 1,
        area: 900,
        image_url: 'frontend/images/image_3.jpg'
    }
]
</script>
