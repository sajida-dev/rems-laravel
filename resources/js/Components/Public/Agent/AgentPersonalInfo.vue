<template>
    <div class="flex flex-col md:flex-row justify-center items-center gap-10 mt-12 px-4 lg:px-0">
        <!-- Agent Info -->
        <div class="w-full md:w-[35%] bg-white rounded-xl  p-6 space-y-4 text-gray-800">
            <h2 class="text-3xl font-bold border-b pb-2 mb-10">Agent Details</h2>
            <div class="space-y-2">
                <p><strong>Name:</strong> {{ agent?.user?.name }}</p>
                <p><strong>License:</strong> {{ agent?.licence_no }}</p>
                <p><strong>Email:</strong> {{ agent?.user?.email }}</p>
                <p><strong>Agency:</strong> {{ agent?.agency }}</p>
                <p><strong>Experience:</strong> {{ agent?.experience }} years</p>
                <p><strong>Bio:</strong> {{ agent?.bio }}</p>
                <p><strong>Contact Number:</strong> {{ agent?.user?.contact ?? 'N/A' }}</p>
                <p><strong>Specializations:</strong>
                    {{agent.categories && agent.categories.length
                        ? agent.categories.map(cat => cat.name).join(', ')
                        : 'Residential, Commercial'
                    }}
                </p>
            </div>
            <div class="pt-4">
                <HireButton :link="hireLink" />
            </div>
        </div>

        <!-- Agent Image -->
        <div class="w-full md:w-1/3 h-[450px] overflow-hidden bg-gray-200 rounded-xl shadow-md">
            <img :src="`/storage/${agent?.user?.profile_photo_path}`" alt="Agent"
                class="w-full h-full object-cover rounded-xl" />
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
