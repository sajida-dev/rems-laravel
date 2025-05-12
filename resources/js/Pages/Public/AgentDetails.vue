<template>

    <Head :title="agent.user.name" />

    <div>
        <HeroSection variant="page" :heading="agent.user.name" :breadcrumbs="[
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

const { agent } = defineProps({
    agent: {
        type: Object,
        required: true,
    }
})


const hireLink = `/request-services?agent_id=1`
</script>
