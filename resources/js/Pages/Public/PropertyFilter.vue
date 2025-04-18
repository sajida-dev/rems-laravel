<template>

    <Head title="Properties Filter" />

    <HeroSection variant="page" heading="Search Properties" :breadcrumbs="[
        { label: 'Home', link: '/' },
        { label: 'Filtered Properties' }
    ]" />

    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <FilterForm :categories="categories" :allAmenities="allAmenities" @applyFilters="applyFilters" />

                <div class="md:col-span-3">
                    <PropertyListSection :properties="paginatedProperties"
                        :message="!paginatedProperties.length ? 'No properties found.' : ''" :currentPage="currentPage"
                        :totalPages="totalPages" :columns="2" @updatePage="goToPage" />
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3'

import PublicLayout from '@/Layouts/PublicLayout.vue';
import HeroSection from '@/Components/Public/Common/HeroSection.vue';
import FilterForm from '@/Components/Public/Property/Filter/FilterForm.vue';
import PropertyListSection from '@/Components/Public/Property/PropertyListSection.vue';

defineOptions({ layout: PublicLayout });
const props = defineProps({
    categories: Array,
    allAmenities: Array,
});

// Full list of properties (could be fetched or passed as props)
const properties = ref([
    { id: 1, title: 'Modern Family House', location: 'Los Angeles, CA', purchase_price: 2500, rent_price: 1800, bedrooms: 3, bathrooms: 2, area: 1600, image_url: '/frontend/images/image_1.jpg' },
    { id: 2, title: 'Luxury Villa in Bali', location: 'Bali, Indonesia', purchase_price: 4000, rent_price: 3200, bedrooms: 5, bathrooms: 4, area: 3200, image_url: '/frontend/images/image_2.jpg' },
    { id: 3, title: 'Cozy Apartment', location: 'New York, NY', purchase_price: 1900, rent_price: 1500, bedrooms: 2, bathrooms: 1, area: 900, image_url: '/frontend/images/image_3.jpg' },
    { id: 4, title: 'Cozy Apartment', location: 'New York, NY', purchase_price: 1900, rent_price: 1500, bedrooms: 2, bathrooms: 1, area: 900, image_url: '/frontend/images/image_3.jpg' },
    { id: 5, title: 'Cozy Apartment', location: 'New York, NY', purchase_price: 1900, rent_price: 1500, bedrooms: 2, bathrooms: 1, area: 900, image_url: '/frontend/images/image_3.jpg' },
    { id: 6, title: 'Cozy Apartment', location: 'New York, NY', purchase_price: 1900, rent_price: 1500, bedrooms: 2, bathrooms: 1, area: 900, image_url: '/frontend/images/image_3.jpg' },
    { id: 7, title: 'Cozy Apartment', location: 'New York, NY', purchase_price: 1900, rent_price: 1500, bedrooms: 2, bathrooms: 1, area: 900, image_url: '/frontend/images/image_3.jpg' },
    { id: 8, title: 'Cozy Apartment', location: 'New York, NY', purchase_price: 1900, rent_price: 1500, bedrooms: 2, bathrooms: 1, area: 900, image_url: '/frontend/images/image_3.jpg' },
    { id: 9, title: 'Cozy Apartment', location: 'New York, NY', purchase_price: 1900, rent_price: 1500, bedrooms: 2, bathrooms: 1, area: 900, image_url: '/frontend/images/image_3.jpg' },
    { id: 10, title: 'Cozy Apartment', location: 'New York, NY', purchase_price: 1900, rent_price: 1500, bedrooms: 2, bathrooms: 1, area: 900, image_url: '/frontend/images/image_3.jpg' },
    { id: 11, title: 'Cozy Apartment', location: 'New York, NY', purchase_price: 1900, rent_price: 1500, bedrooms: 2, bathrooms: 1, area: 900, image_url: '/frontend/images/image_3.jpg' },
    { id: 12, title: 'Cozy Apartment', location: 'New York, NY', purchase_price: 1900, rent_price: 1500, bedrooms: 2, bathrooms: 1, area: 900, image_url: '/frontend/images/image_3.jpg' },
    { id: 13, title: 'Cozy Apartment', location: 'New York, NY', purchase_price: 1900, rent_price: 1500, bedrooms: 2, bathrooms: 1, area: 900, image_url: '/frontend/images/image_3.jpg' },
]);

// Pagination state
const currentPage = ref(1);
const perPage = 8;

// Compute total pages
const totalPages = computed(() => Math.ceil(properties.value.length / perPage));

// Compute paginated items
const paginatedProperties = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    return properties.value.slice(start, start + perPage);
});

// Navigate to a page without reloading
function goToPage(page) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        window.scrollTo({ top: 650, behavior: 'smooth' });
    }
}

// Reset page on filtering (filter logic to be implemented)
function applyFilters(newFilters) {
    currentPage.value = 1;
    // TODO: Apply filter logic to 'properties' array
}
</script>

<script>
export default {
    name: 'PropertyFilter',
};
</script>
