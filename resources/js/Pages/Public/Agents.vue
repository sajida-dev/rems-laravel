<template lang="">
        <Head title="Agent" />

    <HeroSection 
            variant="page" heading="Agent" :breadcrumbs="[
            { label: 'Home', link: '/' },
            { label: 'Agent' }
        ]"
     />
    <AgentListSection
    :agents="paginatedAgents"
    :message="message"
    :currentPage="currentPage"
    :totalPages="totalPages"
    @updatePage="goToPage"
  />
</template>
<script setup>
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue';
import HeroSection from '@/Components/Public/Common/HeroSection.vue'
import { ref } from 'vue'
import AgentListSection from '@/Components/Public/Agent/AgentListSection.vue'
defineOptions({ layout: PublicLayout })
import { computed } from 'vue'

const { agents } = defineProps({
    agents: {
        type: Object,
        required: true,
    }
})

// const agents = [
//     {
//         id: 1,
//         name: 'John Doe',
//         profile_pic: 'frontend/images/team-1.jpg',
//         property_count: 12,
//     },
//     {
//         id: 2,
//         name: 'Jane Smith',
//         profile_pic: '',
//         property_count: 8,
//     },
//     {
//         id: 3,
//         name: 'Mike Johnson',
//         profile_pic: 'frontend/images/team-2.jpg',
//         property_count: 5,
//     },
//     {
//         id: 4,
//         name: 'Sara Lee',
//         profile_pic: '',
//         property_count: 20,
//     },
//     { id: 5, name: 'Agent 5', profile_pic: '', property_count: 3 },
//     { id: 6, name: 'Agent 6', profile_pic: '', property_count: 9 },
//     { id: 7, name: 'Agent 7', profile_pic: '', property_count: 6 },
//     { id: 8, name: 'Agent 8', profile_pic: '', property_count: 7 },
//     { id: 9, name: 'Agent 9', profile_pic: '', property_count: 4 },
//     { id: 10, name: 'Agent 10', profile_pic: '', property_count: 2 },
//     { id: 11, name: 'Agent 11', profile_pic: '', property_count: 11 },
//     { id: 12, name: 'Agent 12', profile_pic: '', property_count: 13 },
//     { id: 13, name: 'Agent 13', profile_pic: '', property_count: 1 },
// ]
// const agents = ref([]) // from API
const message = ref('Loading agents...')
const currentPage = ref(1)
const perPage = 12
const totalPages = computed(() => Math.ceil(agents.length / perPage))

const paginatedAgents = computed(() => {
    const start = (currentPage.value - 1) * perPage
    return agents.slice(start, start + perPage)
})

function goToPage(page) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
    }
}
// const message = ref('')

</script>
<style lang="">

</style>