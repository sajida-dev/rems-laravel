<template>

    <Head :title="agent.user.name" />
    <div class="container mx-auto py-8 px-4">
        <!-- Agent Card -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6 flex flex-col md:flex-row items-center gap-6">
            <img :src="profile_pic" :alt="agent.user.name"
                class="w-36 h-36 object-cover rounded-full border border-gray-400" />
            <div>
                <h2 class="text-2xl font-bold">{{ agent.user.name }}</h2>
                <p class="text-gray-700"><strong>Agency:</strong> {{ agent.agency }}</p>
                <p class="text-gray-700"><strong>Experience:</strong> {{ agent.experience }} years</p>
                <p class="text-gray-700"><strong>Contact:</strong> {{ agent.user.contact }}</p>
                <p class="text-gray-700"><strong>Email:</strong> {{ agent.user.email }}</p>
                <p class="text-gray-700"><strong>Bio:</strong> {{ agent.bio }}</p>

                <div v-if="agentStatus === 0" class="mt-4">
                    <button @click="approveAgent" class="bg-green-700 text-white py-2 px-4 rounded hover:bg-green-800">
                        Approve Agent
                    </button>
                </div>
                <div v-if="agentStatus === 1" class="mt-4">
                    <span class="text-green-600 font-bold">Approved</span>
                </div>
            </div>

        </div>

        <!-- Agent's Properties -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold border-b border-gray-300 pb-2 mb-4">
                Properties by {{ agent.user.name }}
            </h3>
            <PropertyListSection :properties="paginatedProperties"
                :message="!paginatedProperties.length ? 'No properties found.' : ''" :currentPage="currentPage"
                :totalPages="totalPages" :columns="2" @updatePage="goToPage" :isAgentProperties="true" />
        </div>
    </div>
</template>

<script setup>
import { defineProps, ref, computed, inject } from 'vue';
import { Head, router } from '@inertiajs/vue3'
import PropertyListSection from '@/Components/Public/Property/PropertyListSection.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { toast } from 'vue3-toastify'
import { usePage } from '@inertiajs/vue3'
defineOptions({ layout: DashboardLayout })
const props = defineProps({
    agent: Object,
    properties: Array,
    pendingCount: {
        type: Number,
        required: true
    }
});
const currentPage = ref(1)
const perPage = 9
const totalPages = computed(() => Math.ceil(props.properties.length / perPage))

const paginatedProperties = computed(() => {
    const start = (currentPage.value - 1) * perPage
    return props.properties.slice(start, start + perPage)
})

const page = usePage();
const agentStatus = ref(props.agent.status);
const updatePendingCount = inject('updatePendingCount');

const emit = defineEmits(['update:pendingCount']);

function goToPage(page) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
    }
}
const profile_pic = ref(`/storage/${props.agent.user.profile_photo_path}`)
const approveAgent = () => {
    router.post(route('agents.approve', props.agent.id), {}, {
        preserveScroll: true,
        onSuccess: (response) => {
            agentStatus.value = 1;
            if (updatePendingCount) {
                updatePendingCount(page.props.pendingCount - 1);
            }
            toast.success(response.props.flash.success || "Agent approved successfully!");
        },
        onError: () => {
            toast.error(page.props.flash.error || "Something went wrong!");
        }
    });
}
</script>

<style scoped>
.container {
    max-width: 1200px;
}
</style>
