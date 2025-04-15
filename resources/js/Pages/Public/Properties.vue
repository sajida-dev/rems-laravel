<template lang="">
    <HeroSection variant="page" heading="Properties" :breadcrumbs="[
        { label: 'Home', link: '/' },
        { label: 'Properties' }
    ]" />
     <PropertyListSection
    :properties="paginatedProperty"
    :message="message"
    :currentPage="currentPage"
    :totalPages="totalPages"
    @updatePage="goToPage"
  />
</template>

<script setup>
import HeroSection from '@/Components/Public/Common/HeroSection.vue'
import PropertyListSection from '@/Components/Public/Property/PropertyListSection.vue'
import PublicLayout from '@/Layouts/PublicLayout.vue';
defineOptions({ layout: PublicLayout })
import { computed } from 'vue'
import { ref } from 'vue'


const properties = [
    {
        id: 1,
        title: 'Modern Family House',
        location: 'Los Angeles, CA',
        purchase_price: 2500,
        rent_price: 1800,
        bedrooms: 3,
        bathrooms: 2,
        area: 1600,
        image_url: 'frontend/images/image_1.jpg'
    },
    {
        id: 2,
        title: 'Luxury Villa in Bali',
        location: 'Bali, Indonesia',
        purchase_price: 4000,
        rent_price: 3200,
        bedrooms: 5,
        bathrooms: 4,
        area: 3200,
        image_url: 'frontend/images/image_2.jpg'
    },
    {
        id: 3,
        title: 'Cozy Apartment',
        location: 'New York, NY',
        purchase_price: 1900,
        rent_price: 1500,
        bedrooms: 2,
        bathrooms: 1,
        area: 900,
        image_url: 'frontend/images/image_3.jpg'
    }
]
const message = ref('Loading properties...')
const currentPage = ref(1)
const perPage = 12
const totalPages = computed(() => Math.ceil(properties.length / perPage))

const paginatedProperty = computed(() => {
    const start = (currentPage.value - 1) * perPage
    return properties.slice(start, start + perPage)
})

function goToPage(page) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
    }
}
</script>

<style lang="">

</style>