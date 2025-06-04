<template lang="">
        <Head title="Properties" />
 
    <HeroSection variant="page" :showSearchForm="true" heading="Properties" :breadcrumbs="[
        { label: 'Home', link: '/' },
        { label: 'Properties' }
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
import { computed } from 'vue'
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import FilterForm from '@/Components/Public/Property/Filter/FilterForm.vue';
import HeroSection from '@/Components/Public/Common/HeroSection.vue'
import PropertyListSection from '@/Components/Public/Property/PropertyListSection.vue'
import PublicLayout from '@/Layouts/PublicLayout.vue';
defineOptions({ layout: PublicLayout })
const { properties } = defineProps({
    properties: {
        type: Object,
        required: true,
    },
    categories: {
        type: Object,
        required: true,
    },
    allAmenities: {
        type: Object,
        required: true,
    },
})



const message = ref('Loading properties...')
const currentPage = ref(1)
const perPage = 9
const totalPages = computed(() => Math.ceil(properties.length / perPage))
function applyFilters(newFilters) {
    currentPage.value = 1;
}
const paginatedProperties = computed(() => {
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

<script>
export default {
    name: "Properties",
    components: {
        PropertyListSection,
        HeroSection,
    }
}
</script>