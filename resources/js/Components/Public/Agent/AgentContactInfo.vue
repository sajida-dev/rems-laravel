<template>
    <div>
        <h2 class="text-4xl text-center">Contact Information</h2>
        <div class="text-center mt-6">
            <p><strong>Email:</strong> {{ agent?.user?.email }}</p>
            <p><strong>Contact Number:</strong> {{ agent?.user?.contact }}</p>
        </div>
    </div>
</template>

<script setup>
const { agent } = defineProps({
    agent: Object,
});
</script>
