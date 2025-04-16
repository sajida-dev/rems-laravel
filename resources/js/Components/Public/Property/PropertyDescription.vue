<template>
    <div class="tab-content lg:my-20 mx-auto text-center">
        <p class="text-gray-700 leading-relaxed">
            {{ description }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    description: {
        type: String,
        required: true,
    },
})
</script>
