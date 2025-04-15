<!-- <template>
    <div class="group overflow-hidden relative ftco-animate">
        <a :href="`/property/${property.id}`" class="block w-full h-[250px] bg-cover bg-center"
            :style="{ backgroundImage: `url(${imageUrl})` }"></a>

        <button aria-label="Bookmark Property" @click.stop="bookmark"
            class="absolute book-mark top-2 right-2 w-10 h-10 bg-white shadow rounded-full flex items-center justify-center text-pink-500 hover:bg-pink-500 hover:text-white transition duration-300">
            <i class="fas fa-heart"></i>
        </button>
        <div
            class="relative transition-all duration-300 ease-in-out -mt-[50px] ml-[10px] lg:ml-5 bg-white p-[25px] w-[90%] md:w-[75%] shadow-[0px_5px_21px_-14px_rgba(0,0,0,0.14)] group-hover:shadow-[0px_5px_39px_-14px_rgba(0,0,0,0.26)]">
            <p class="price text-lg font-semibold text-gray-800 mb-2">
                <span
                    class="purchase-price text-[16px] font-semibold text-black/60 relative mr-5 before:absolute before:top-1/2 before:left-0 before:right-0 before:w-full before:h-[1px] before:bg-black before:content-['']">
                    ${{ formattedPurchasePrice }}
                </span>
                <span class="orig-price text-[16px] font-semibold text-green-600">
                    ${{ formattedRentPrice }}<small class="text-xs">/mo</small>
                </span>
            </p>

            <ul class="property_list flex gap-4 text-gray-600 text-sm my-3 p-0">
                <li class="flex items-center gap-1">
                    <i class="fas fa-bed"></i>
                    {{ property.bedrooms }}
                </li>
                <li class="flex items-center gap-1">
                    <i class="fas fa-bath"></i>
                    {{ property.bathrooms }}
                </li>
                <li class="flex items-center gap-1">
                    <i class="fas fa-ruler-combined"></i>
                    {{ property.area }} sqft
                </li>
            </ul>

            <h3 class="text-xl font-bold mb-1">
                <a :href="`/property/${property.id}`" class="hover:underline">
                    {{ property.title }}
                </a>
            </h3>

            <span class="location text-gray-500 text-sm block mb-3">
                {{ property.location }}
            </span>

            <a :href="`/property/${property.id}`"
                class="btn-custom flex items-center justify-center absolute bottom-0 right-0 w-10 h-10 bg-[#e86ed0] text-white rounded-tl-3xl hover:bg-pink-500 transition">
                <i class="fas fa-link"></i>
            </a>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const { property } = defineProps({
    property: {
        type: Object,
        required: true
    }
})
const bookmark = () => {

    console.log('Bookmark clicked for property:', property.id);
};

const imageUrl = computed(() => `${property.image_url}`)
const formattedPurchasePrice = computed(() => Number(property.purchase_price).toLocaleString())
const formattedRentPrice = computed(() => Number(property.rent_price).toLocaleString())
</script> -->


<template>
    <!-- The entire card is clickable -->
    <div class="group overflow-hidden ftco-animate cursor-pointer relative" @click="goToDetail">
        <!-- Property image -->
        <div class="block w-full h-[250px] bg-cover bg-center" :style="{ backgroundImage: `url(${imageUrl})` }"></div>

        <!-- Bookmark button with toggle logic -->
        <button aria-label="Bookmark Property" @click.stop="toggleBookmark" :class="[
            'absolute top-2 right-2 w-10 h-10 shadow rounded-full flex items-center justify-center transition duration-300',
            isBookmarked ? 'bg-pink-500 text-white' : 'bg-white text-pink-500 hover:bg-pink-500 hover:text-white'
        ]">
            <i class="fas fa-heart"></i>
        </button>

        <!-- Property details -->
        <div
            class="relative transition-all duration-300 ease-in-out -mt-[50px] ml-[10px] lg:ml-5 bg-white p-[25px] w-[90%] md:w-[75%] shadow-[0px_5px_21px_-14px_rgba(0,0,0,0.14)] group-hover:shadow-[0px_5px_39px_-14px_rgba(0,0,0,0.26)]">
            <p class="price text-lg font-semibold text-gray-800 mb-2">
                <span
                    class="purchase-price text-[16px] font-semibold text-black/60 relative mr-5 before:absolute before:top-1/2 before:left-0 before:right-0 before:w-full before:h-[1px] before:bg-black before:content-['']">
                    ${{ formattedPurchasePrice }}
                </span>
                <span class="orig-price text-[16px] font-semibold text-green-600">
                    ${{ formattedRentPrice }}<small class="text-xs">/mo</small>
                </span>
            </p>

            <ul class="property_list flex gap-4 text-gray-600 text-sm my-3 p-0">
                <li class="flex items-center gap-1">
                    <i class="fas fa-bed"></i>
                    {{ property.bedrooms }}
                </li>
                <li class="flex items-center gap-1">
                    <i class="fas fa-bath"></i>
                    {{ property.bathrooms }}
                </li>
                <li class="flex items-center gap-1">
                    <i class="fas fa-ruler-combined"></i>
                    {{ property.area }} sqft
                </li>
            </ul>

            <h3 class="text-xl font-bold mb-1">
                {{ property.title }}
            </h3>

            <span class="location text-gray-500 text-sm block mb-3">
                {{ property.location }}
            </span>

            <!-- Decorative link icon -->
            <div
                class="btn-custom flex items-center justify-center absolute bottom-0 right-0 w-10 h-10 bg-[#e86ed0] text-white rounded-tl-3xl hover:bg-pink-500 transition pointer-events-none">
                <i class="fas fa-link"></i>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

// Define the expected prop; optionally pass an initial bookmark status from the parent.
const { property, initialBookmarkStatus } = defineProps({
    property: {
        type: Object,
        required: true,
    },
    initialBookmarkStatus: {
        type: Boolean,
        default: false,
    }
})

// Reactive variable to track if the property is bookmarked.
const isBookmarked = ref(initialBookmarkStatus)

// Compute values for image URL and formatted prices.
const imageUrl = computed(() => `${property.image_url}`)
const formattedPurchasePrice = computed(() =>
    Number(property.purchase_price).toLocaleString()
)
const formattedRentPrice = computed(() =>
    Number(property.rent_price).toLocaleString()
)

// Navigate to the property detail page using Inertia.
const goToDetail = () => {
    Inertia.visit(`/property/${property.id}`)
}

// Toggle bookmark status using Inertia to update the backend.
const toggleBookmark = () => {
    if (!isBookmarked.value) {
        // Add bookmark. Adjust the route and payload as needed.
        Inertia.post('/bookmarks', { property_id: property.id }, {
            onSuccess: () => {
                isBookmarked.value = true
            },
            onError: () => {
                console.error('Failed to bookmark the property.')
            }
        })
    } else {
        // Remove bookmark. Adjust the route as per your API design.
        Inertia.delete(`/bookmarks/${property.id}`, {
            onSuccess: () => {
                isBookmarked.value = false
            },
            onError: () => {
                console.error('Failed to remove bookmark.')
            }
        })
    }
}
</script>
