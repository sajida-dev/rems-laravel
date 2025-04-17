<template>
    <div class="group overflow-hidden ftco-animate cursor-pointer relative" @click="goToDetail">
        <!-- Property image -->
        <div class="block w-full h-[270px] bg-cover bg-center" :style="{ backgroundImage: `url(${imageUrl})` }"></div>

        <button aria-label="Bookmark Property" @click.stop="toggleBookmark" :class="[
            'absolute top-2 right-2 w-10 h-10 shadow rounded-full flex items-center justify-center transition duration-300 z-10',
            isBookmarked ? 'bg-pink-500 text-white' : 'bg-white text-pink-500 hover:bg-pink-500 hover:text-white'
        ]">
            <i class="fas fa-heart"></i>
        </button>

        <!-- Property details -->
        <div
            class="relative transition-all duration-300 ease-in-out -mt-[50px] ml-[10px] lg:ml-5 bg-white p-6 w-[90%] md:w-[75%] rounded-md shadow-md group-hover:shadow-lg">
            <p class="price text-lg font-semibold text-gray-800 mb-2 flex flex-wrap items-center gap-6">
                <span class="flex items-center text-[16px] text-black/60 relative ">
                    <i class="fas fa-money-bill-wave text-pink-500 mr-2"></i>
                    ${{ formattedPurchasePrice }}
                </span>

                <span class="flex items-center text-[16px]  font-semibold">
                    <i class="fas fa-handshake text-green-800 mr-2"></i>
                    ${{ formattedRentPrice }}<small class="text-xs ml-1">/mo</small>
                </span>
            </p>
            <ul class="property_list flex gap-4 text-gray-600 text-sm my-3">
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

            <h3 class="text-xl font-bold text-gray-800 mb-1">
                {{ property.title }}
            </h3>
            <p class="text-sm text-gray-500 mb-3">
                {{ property.location }}
            </p>
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

const isBookmarked = ref(initialBookmarkStatus)

const imageUrl = computed(() => `${property.image_url}`)
const formattedPurchasePrice = computed(() =>
    Number(property.purchase_price).toLocaleString()
)
const formattedRentPrice = computed(() =>
    Number(property.rent_price).toLocaleString()
)

const goToDetail = () => {
    Inertia.visit(`/property/${property.id}-${slugify(property.title)}`)
}
function slugify(name) {
    return name.toLowerCase().replace(/\s+/g, '-')
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
