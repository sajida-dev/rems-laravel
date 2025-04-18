<template>

    <Head :title="agent.name" />

    <div>
        <HeroSection variant="page" :heading="agent.name" :breadcrumbs="[
            { label: 'Home', link: '/' },
            { label: 'Agent Details' }
        ]" />
        <AgentTabs :agent="agent" :hireLink="hireLink" />
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'

import HeroSection from '@/Components/Public/Common/HeroSection.vue'
import AgentTabs from '@/Components/Public/Agent/AgentTabs.vue'
import PublicLayout from '@/Layouts/PublicLayout.vue';
defineOptions({ layout: PublicLayout })

const agent = {
    name: 'John Doe',
    email: 'john@example.com',
    agency: 'Dream Homes',
    experience: 5,
    bio: 'Experienced agent with a focus on client satisfaction.',
    profile_pic: '/frontend/images/team-1.jpg',
    contact: '+123456789',
    licence: 'ABC123',
    properties: [
        {
            id: 1,
            title: 'Modern Family House',
            location: 'Los Angeles, CA',
            purchase_price: 2500,
            rent_price: 1800,
            bedrooms: 3,
            bathrooms: 2,
            area: 1600,
            image_url: '/frontend/images/image_1.jpg'
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
            image_url: '/frontend/images/image_2.jpg'
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
            image_url: '/frontend/images/image_3.jpg'
        }
    ]
};

const hireLink = `/request-services?agent_id=1`
</script>
