<template>
    <div class="w-full p-4 bg-white sticky top-24 h-[calc(100vh-5rem)] overflow-y-auto rounded-lg shadow-md">
        <h4 class="text-lg font-semibold mb-4">Filter Properties</h4>
        <form @submit.prevent="submitFilter" class="flex flex-col space-y-3">
            <SearchInput v-model="filters.search" />
            <CategorySelect :categories="categories" v-model="filters.category_id" />
            <LocationInput v-model="filters.location" />
            <PriceRange v-model:min="filters.min_price" v-model:max="filters.max_price" />
            <BedroomSelect v-model="filters.bedrooms" />
            <AmenitiesCheckbox :amenities="allAmenities" v-model="filters.amenities" />
            <button type="submit" class="mt-4 btn btn-sm bg-pink-400 text-white px-4 py-2 rounded">Apply
                Filters</button>
        </form>
    </div>
</template>

<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

import SearchInput from '@Components/Public/Property/Filter/SearchInput.vue'
import CategorySelect from '@Components/Public/Property/Filter/CategorySelect.vue'
import LocationInput from '@Components/Public/Property/Filter/LocationInput.vue'
import PriceRange from '@Components/Public/Property/Filter/PriceRange.vue'
import BedroomSelect from '@Components/Public/Property/Filter/BedroomSelect.vue'
import AmenitiesCheckbox from '@Components/Public/Property/Filter/AmenitiesCheckbox.vue'

const props = defineProps({
    categories: Array,
    allAmenities: Array,
    initialFilters: Object
})

const filters = reactive({ ...props.initialFilters })

const submitFilter = () => {
    router.get('/filter-properties', filters, {
        preserveState: true,
        replace: true
    })
}
</script>
