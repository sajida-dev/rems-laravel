<template>
    <HeroSection variant="page" heading="Search Properties" :breadcrumbs="[
        { label: 'Home', link: '/' },
        { label: 'Filtered Properties' }
    ]" />
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Sidebar Filter -->
                <FilterForm :categories="categories" :allAmenities="allAmenities" @applyFilters="applyFilters" />

                <!-- Property Listings -->
                <div class="md:col-span-3">
                    <PropertyListSection :properties="properties" />
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import PublicLayout from '@/Layouts/PublicLayout.vue';
import HeroSection from '@/Components/Public/Common/HeroSection.vue';
import FilterForm from '@/Components/Public/Property/Filter/FilterForm.vue'
import PropertyListSection from '@/Components/Public/Property/PropertyListSection.vue'
defineOptions({ layout: PublicLayout })

defineProps(['categories', 'allAmenities'])

const applyFilters = (filters) => {
    // Use Inertia to apply filters
    console.log('Apply filters:', filters)
    // Inertia.get(route('properties.index'), filters, { preserveScroll: true })
}


const filters = ref({
    search: '',
    category_id: '',
    location: '',
    min_price: '',
    max_price: '',
    bedrooms: '',
    amenities: []
})



const properties = ref([
    {
        id: 1,
        title: 'Modern Family House',
        location: 'Los Angeles, CA',
        purchase_price: 2500,
        rent_price: 1800,
        bedrooms: 3,
        bathrooms: 2,
        area: 1600,
        image_url: '/frontend/images/image_1.jpg'
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
        image_url: '/frontend/images/image_2.jpg'
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
        image_url: '/frontend/images/image_3.jpg'
    },
    {
        id: 4,
        title: 'Cozy Apartment',
        location: 'New York, NY',
        purchase_price: 1900,
        rent_price: 1500,
        bedrooms: 2,
        bathrooms: 1,
        area: 900,
        image_url: '/frontend/images/image_3.jpg'
    },
    {
        id: 5,
        title: 'Cozy Apartment',
        location: 'New York, NY',
        purchase_price: 1900,
        rent_price: 1500,
        bedrooms: 2,
        bathrooms: 1,
        area: 900,
        image_url: '/frontend/images/image_3.jpg'
    },
    {
        id: 6,
        title: 'Cozy Apartment',
        location: 'New York, NY',
        purchase_price: 1900,
        rent_price: 1500,
        bedrooms: 2,
        bathrooms: 1,
        area: 900,
        image_url: '/frontend/images/image_3.jpg'
    },
    {
        id: 7,
        title: 'Cozy Apartment',
        location: 'New York, NY',
        purchase_price: 1900,
        rent_price: 1500,
        bedrooms: 2,
        bathrooms: 1,
        area: 900,
        image_url: '/frontend/images/image_3.jpg'
    },
    {
        id: 8,
        title: 'Cozy Apartment',
        location: 'New York, NY',
        purchase_price: 1900,
        rent_price: 1500,
        bedrooms: 2,
        bathrooms: 1,
        area: 900,
        image_url: '/frontend/images/image_3.jpg'
    },
    {
        id: 9,
        title: 'Cozy Apartment',
        location: 'New York, NY',
        purchase_price: 1900,
        rent_price: 1500,
        bedrooms: 2,
        bathrooms: 1,
        area: 900,
        image_url: '/frontend/images/image_3.jpg'
    },
    {
        id: 10,
        title: 'Cozy Apartment',
        location: 'New York, NY',
        purchase_price: 1900,
        rent_price: 1500,
        bedrooms: 2,
        bathrooms: 1,
        area: 900,
        image_url: '/frontend/images/image_3.jpg'
    },
    {
        id: 11,
        title: 'Cozy Apartment',
        location: 'New York, NY',
        purchase_price: 1900,
        rent_price: 1500,
        bedrooms: 2,
        bathrooms: 1,
        area: 900,
        image_url: '/frontend/images/image_3.jpg'
    },
    {
        id: 12,
        title: 'Cozy Apartment',
        location: 'New York, NY',
        purchase_price: 1900,
        rent_price: 1500,
        bedrooms: 2,
        bathrooms: 1,
        area: 900,
        image_url: '/frontend/images/image_3.jpg'
    },
    {
        id: 13,
        title: 'Cozy Apartment',
        location: 'New York, NY',
        purchase_price: 1900,
        rent_price: 1500,
        bedrooms: 2,
        bathrooms: 1,
        area: 900,
        image_url: '/frontend/images/image_3.jpg'
    },
])
</script>

<script>
export default {
    name: "PropertyFilter",
    components: {
        HeroSection,
        FilterForm,
        PropertyListSection
    }
}
</script>
