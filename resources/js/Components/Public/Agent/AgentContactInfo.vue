<!-- <template>
    <h2 class="text-4xl text-center">Contact Information</h2>
    <div class="text-center mt-6">
        <p><strong>Email:</strong> {{ agent.email }}</p>
        <p><strong>Contact Number:</strong> {{ agent.contact }}</p>
    </div>
</template>

<script setup>
defineProps({
    agent: Object
});
</script> -->



<template>
    <div>
        <h2 class="text-4xl text-center">Contact Information</h2>
        <div class="text-center mt-6">
            <p><strong>Email:</strong> {{ agent.email }}</p>
            <p><strong>Contact Number:</strong> {{ agent.contact }}</p>
        </div>
    </div>
</template>

<script setup>
const { agent } = defineProps({
    agent: Object,
});
</script>
