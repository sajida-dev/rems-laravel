<template lang="">
    <Head :title="property.title" />
        <section class="py-8">
            <div class="container mx-auto px-4">
                <PropertyHeader 
                    :property="property" 
                    @buy="handleBuy" 
                    @rent="handleRent" 
                />
                <PropertyTabs :property="property" />
            </div>
        </section>
</template>
<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'

import HeroSection from '@/Components/Public/Common/HeroSection.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import PropertyHeader from '@/Components/Public/Property/PropertyHeader.vue';
import PropertyTabs from '@/Components/Public/Property/PropertyTabs.vue';
defineOptions({ layout: DashboardLayout })
const props = defineProps({
    property: {
        type: Object,
        required: true
    }
})

const handleBuy = (property) => {
    // Handle buy request
    alert('Proceeding to buy this property!')
}

const handleRent = (property) => {
    // Handle rent application
    alert('Proceeding to rent this property!')
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
