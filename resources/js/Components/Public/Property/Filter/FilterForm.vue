<template>
    <div class="w-full p-4 bg-white sticky top-24 h-[calc(100vh-5rem)] overflow-y-auto rounded-lg shadow-md">
        <h4 class="text-lg font-semibold mb-4">Filter Properties</h4>
        <form @submit.prevent="submitFilter" class="flex flex-col space-y-3">
            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Property Type</label>
                <select v-model="filters.type"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                    <option value="">All Types</option>
                    <option value="rent">For Rent</option>
                    <option value="buy">For Sale</option>
                </select>
            </div>

            <SearchInput v-model="filters.search" />
            <CategorySelect :categories="categories" v-model="filters.category_id" />
            <LocationInput v-model="filters.location" />
            <PriceRange v-model:rentMin="filters.rent_min_price" v-model:rentMax="filters.rent_max_price"
                v-model:purchaseMin="filters.purchase_min_price" v-model:purchaseMax="filters.purchase_max_price"
                :type="filters.type" />
            <div class="grid grid-cols-2 gap-2">
                <!-- <BedroomSelect v-model="filters.bedrooms" /> -->
                <NumberInput label="Bedrooms" v-model="filters.bedrooms" />
                <NumberInput label="Bathrooms" v-model="filters.bathrooms" />
            </div>
            <div class="grid grid-cols-2 gap-2">
                <NumberInput label="Lot Area (sq ft)" v-model="filters.lot_area" />
                <NumberInput label="Floor Area (sq ft)" v-model="filters.floor_area" />
            </div>
            <div class="grid grid-cols-2 gap-2">
                <NumberInput label="Stories" v-model="filters.stories" />
                <NumberInput label="Garage" v-model="filters.garage" />
            </div>
            <NumberInput label="Year Built" v-model="filters.year_built" />
            <BooleanSelect label="Water Availability" v-model="filters.is_water" />
            <div class="grid grid-cols-2 gap-2">
                <BooleanSelect label="New Roofing" v-model="filters.is_new_roofing" />
                <BooleanSelect label="Luggage Space" v-model="filters.is_luggage" />
            </div>



            <SpecializationSelect :list="allAmenities" v-model="filters.amenities" />
            <button type="submit" class="mt-4 btn btn-sm bg-pink-400 text-white px-4 py-2 rounded">Apply
                Filters</button>
        </form>
    </div>
</template>

<script setup>
import { reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'

import SearchInput from '@Components/Public/Property/Filter/SearchInput.vue'
import CategorySelect from '@Components/Public/Property/Filter/CategorySelect.vue'
import LocationInput from '@Components/Public/Property/Filter/LocationInput.vue'
import PriceRange from '@Components/Public/Property/Filter/PriceRange.vue'
import BedroomSelect from '@Components/Public/Property/Filter/BedroomSelect.vue'
import AmenitiesCheckbox from '@Components/Public/Property/Filter/AmenitiesCheckbox.vue'
import SpecializationSelect from '@/Components/Dashboard/Common/SpecializationSelect.vue'
import InputError from '@/Components/Default/InputError.vue'
import NumberInput from './NumberInput.vue'
import BooleanSelect from './BooleanSelect.vue'

const props = defineProps({
    categories: Array,
    allAmenities: Array,
    initialFilters: Object
})

const filters = reactive({
    type: props.initialFilters?.type || '',
    ...props.initialFilters
})

// Watch for type changes to reset price fields
watch(() => filters.type, (newType) => {
    if (newType === 'rent') {
        filters.purchase_min_price = null;
        filters.purchase_max_price = null;
    } else if (newType === 'buy') {
        filters.rent_min_price = null;
        filters.rent_max_price = null;
    }
});

const submitFilter = () => {
    router.get('/all-properties', filters, {
        preserveState: true,
        replace: true
    })
}
</script>
