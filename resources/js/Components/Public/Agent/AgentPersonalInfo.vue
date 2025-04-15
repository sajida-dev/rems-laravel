<!-- <template>
    <div class="flex flex-wrap justify-center gap-8 mt-8">
        <div class="w-full md:w-1/2 space-y-3">
            <p><strong>Name:</strong> {{ agent.name }}</p>
            <p><strong>Licence:</strong> {{ agent.licence }}</p>
            <p><strong>Email:</strong> {{ agent.email }}</p>
            <p><strong>Agency:</strong> {{ agent.agency }}</p>
            <p><strong>Experience:</strong> {{ agent.experience }} years</p>
            <p><strong>Bio:</strong> {{ agent.bio }}</p>
            <HireButton :link="hireLink" />
        </div>
        <div class="w-full md:w-1/3 h-[370px] overflow-hidden">
            <img :src="agent.profile_pic" alt="Agent" class="w-full h-full object-cover rounded shadow" />
        </div>
    </div>
</template>

<script setup>
import HireButton from './HireButton.vue'

defineProps({
    agent: Object,
    hireLink: String
});
</script> -->



<template>
    <div class="flex flex-wrap justify-center gap-8 mt-8">
        <div class="w-full md:w-1/2 space-y-3 bg-red-300 lg:py-10 text-right">
            <p><strong>Name:</strong> {{ agent.name }}</p>
            <p><strong>Licence:</strong> {{ agent.licence }}</p>
            <p><strong>Email:</strong> {{ agent.email }}</p>
            <p><strong>Agency:</strong> {{ agent.agency }}</p>
            <p><strong>Experience:</strong> {{ agent.experience }} years</p>
            <p><strong>Bio:</strong> {{ agent.bio }}</p>
            <HireButton :link="hireLink" />
        </div>
        <div class="w-full md:w-1/3 h-[370px] overflow-hidden bg-gray-300">
            <img :src="agent.profile_pic" alt="Agent" class="w-full h-full object-cover rounded shadow" />
        </div>
    </div>
</template>

<script setup>
import HireButton from './HireButton.vue';

const { props } = defineProps({
    agent: Object,
    hireLink: String,
});
</script>
