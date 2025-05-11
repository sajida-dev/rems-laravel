<template lang="">
        <Head :title="property.title" />

    <HeroSection variant="page" :heading="property.title" :breadcrumbs="[
            { label: 'Home', link: '/' },
            { label: 'Property Details' }
        ]" />
        <section class="py-8">
                <div class="container mx-auto px-4">
                    <PropertyHeader :property="property" @buy="buyNow" />
                    <PropertyTabs :property="property" />
                </div>
            </section>
</template>
<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'

import HeroSection from '@/Components/Public/Common/HeroSection.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import PropertyHeader from '@/Components/Public/Property/PropertyHeader.vue';
import PropertyTabs from '@/Components/Public/Property/PropertyTabs.vue';
defineOptions({ layout: PublicLayout })
const props = defineProps({
    property: {
        type: Object,
        required: true
    }
})

console.log('props.property', props.property)
// const property = ref({
//     title: 'Modern Family Home in Prime Location',
//     location: '123 Green Street, Springfield',
//     image_url: '/frontend/images/image_4.jpg',
//     status: 'available', // or "sold"
//     area: 2500,
//     bedrooms: 4,
//     bathrooms: 3,
//     yearBuilt: 2019,
//     water: true,
//     stories: 2,
//     amenity_list: 'Pool,Gym,Garden,Garage,Gym',
//     description:
//         'A beautiful modern family home featuring an open layout, spacious rooms, and a serene backyard perfect for entertaining.',
//     images: [
//         '/frontend/images/image_1.jpg',
//         '/frontend/images/image_2.jpg',
//         '/frontend/images/image_3.jpg',
//         '/frontend/images/image_7.jpg',
//     ],
// })

const buyNow = () => {
    // Replace with your actual navigation or purchase logic.
    alert('Proceeding to buy this property!')
}
</script>
<script>
export default {
    name: "Property Details",
    components: {
        HeroSection,
        PropertyHeader,
        PropertyTabs,
    }
}
</script>
<style lang="">

</style>
