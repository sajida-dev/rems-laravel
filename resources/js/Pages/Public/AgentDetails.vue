<template>
    <div>
        <HeroSection variant="page" :heading="agent.name" :breadcrumbs="[
            { label: 'Home', link: '/' },
            { label: 'Agent Details' }
        ]" />
        <AgentTabs :agent="agent" :hireLink="hireLink" />
    </div>
</template>

<script setup>
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
    properties: []
};

const hireLink = `/request-services?agent_id=1`
</script>
