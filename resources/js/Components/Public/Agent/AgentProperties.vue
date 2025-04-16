<template>
    <h2 class="text-4xl text-center border-b-2 border-pink-400 p-2 w-fit mx-auto">Agent Properties</h2>
    <div class="container mx-auto mt-6 px-4">
        <div v-if="properties.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <PropertyCard v-for="property in properties" :key="property.id" :property="property" />
        </div>
        <div v-else class="text-center text-gray-600 text-lg py-16">
            <i class="fas fa-info-circle text-4xl text-pink-500 mb-4"></i>
            <p class="text-xl">This agent hasn't uploaded any properties yet.</p>
        </div>
    </div>
</template>

<script setup>
import PropertyCard from '@/Components/Public/Property/PropertyCard.vue';

const { properties } = defineProps({
    properties: {
        type: Array,
        default: () => [],
    },
});
</script>
