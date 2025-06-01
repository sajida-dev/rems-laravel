<template>
    <div>{{ count }}</div>
</template>

<script setup>
defineProps({
    count: {
        type: Number,
        required: true
    }
});
</script>