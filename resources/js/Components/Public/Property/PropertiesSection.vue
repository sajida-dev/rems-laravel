<script setup>
import PropertyCard from './PropertyCard.vue'

const { properties } = defineProps({
    properties: {
        type: Array,
        required: true
    },

})
console.log('properties', properties)

</script>

<template>
    <section class="ftco-section goto-here py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-center mb-12">
                <div class="text-center heading-section ftco-animate">
                    <div class="flex items-center my-8 w-64 mx-auto">
                        <hr class="flex-grow border-t-2 border-pink-300">
                        <span class="subheading mx-4 text-base text-pink-700 uppercase tracking-widest">What we
                            offer</span>
                        <hr class="flex-grow border-t-2 border-pink-300">
                    </div>
                    <h2 class="text-5xl  my-2">Exclusive Offer For You</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <PropertyCard v-for="property in properties" :key="property.id" :property="property" />
            </div>
        </div>
    </section>
</template>
