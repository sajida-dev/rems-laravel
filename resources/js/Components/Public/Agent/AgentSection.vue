<template>
    <section class="py-16 bg-white mb-16">
        <div class="container mx-auto px-4">
            <!-- Heading -->
            <SectionHeading subheading="Agents" title="Our Agents" />


            <!-- Agent Cards -->
            <div class="flex flex-wrap -mx-4">
                <div v-for="agent in agents" :key="agent.id" class="w-full md:w-1/2 lg:w-1/4 px-4 mb-10">
                    <AgentCard :agent="agent" />
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import AgentCard from '@/components/Public/Agent/AgentCard.vue'
import SectionHeading from '@/components/Public/Common/SectionHeading.vue'

const agents = [
    {
        id: 1,
        name: 'John Doe',
        profile_pic: 'frontend/images/team-1.jpg',
        property_count: 12,
    },
    {
        id: 2,
        name: 'Jane Smith',
        profile_pic: '',
        property_count: 8,
    },
    {
        id: 3,
        name: 'Mike Johnson',
        profile_pic: 'frontend/images/team-2.jpg',
        property_count: 5,
    },
    {
        id: 4,
        name: 'Sara Lee',
        profile_pic: '',
        property_count: 20,
    },
]
</script>

<style scoped>
.custom-img {
    height: 300px;
    overflow: hidden;
}
</style>
