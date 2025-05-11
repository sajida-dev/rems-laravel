<script setup>
import PropertyCard from './PropertyCard.vue'
import SectionHeading from '@/Components/Public/Common/SectionHeading.vue'

const { properties } = defineProps({
    properties: {
        type: Array,
        required: true
    },
})

</script>

<template>
    <section class="ftco-section goto-here py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <SectionHeading subheading="What we offer" title="Exclusive Offer For You" />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <PropertyCard v-for="property in properties" :key="property.id" :property="property" />
            </div>
        </div>
    </section>
</template>
