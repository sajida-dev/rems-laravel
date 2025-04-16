<template>
    <div class="flex flex-col md:flex-row justify-center items-center gap-10 mt-12 px-4 lg:px-0">
        <!-- Agent Info -->
        <div class="w-full md:w-[35%] bg-white rounded-xl  p-6 space-y-4 text-gray-800">
            <h2 class="text-3xl font-bold border-b pb-2 mb-10">Agent Details</h2>
            <div class="space-y-2">
                <p><strong>Name:</strong> {{ agent.name }}</p>
                <p><strong>License:</strong> {{ agent.licence }}</p>
                <p><strong>Email:</strong> {{ agent.email }}</p>
                <p><strong>Agency:</strong> {{ agent.agency }}</p>
                <p><strong>Experience:</strong> {{ agent.experience }} years</p>
                <p><strong>Bio:</strong> {{ agent.bio }}</p>
                <p><strong>Contact Number:</strong> {{ agent.phone ?? 'N/A' }}</p>
                <p><strong>Specialties:</strong> {{ agent.specialties ?? 'Residential, Commercial' }}</p>
            </div>
            <div class="pt-4">
                <HireButton :link="hireLink" />
            </div>
        </div>

        <!-- Agent Image -->
        <div class="w-full md:w-1/3 h-[450px] overflow-hidden bg-gray-200 rounded-xl shadow-md">
            <img :src="agent.profile_pic" alt="Agent" class="w-full h-full object-cover rounded-xl" />
        </div>
    </div>
</template>

<script setup>
import HireButton from './HireButton.vue'

defineProps({
    agent: Object,
    hireLink: String,
})
</script>
