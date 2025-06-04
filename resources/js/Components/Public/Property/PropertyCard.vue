<template>
    <Link :href="url" class="group overflow-hidden ftco-animate cursor-pointer relative block">
    <!-- Property image -->
    <div class="block w-full h-[270px] bg-cover bg-center" :style="{ backgroundImage: `url(${imageUrl})` }"></div>

    <button aria-label="Bookmark Property" @click.stop.prevent="toggleBookmark" :class="[
        'absolute top-2 right-2 w-10 text-pink-500 text-xl h-10 shadow rounded-full flex bg-white items-center justify-center transition duration-300 z-10',
        localBookmarkStatus ? ' ' : '  hover:bg-pink-500 hover:text-white'
    ]">
        <!-- <i class="fas fa-heart"></i> -->
        <i :class="[localBookmarkStatus ? 'fas' : 'far', 'fa-heart']"></i>

    </button>

    <!-- Property details -->
    <div
        class="relative transition-all duration-300 ease-in-out -mt-[50px] ml-[10px] lg:ml-5 bg-white p-6 w-[95%] md:w-[83%] rounded-md shadow-md group-hover:shadow-lg">
        <p class="price text-lg font-semibold text-gray-800 mb-2 flex flex-wrap items-center gap-6">
            <span class="flex items-center text-[16px] text-black/60 relative">
                <i class="fas fa-money-bill-wave text-pink-500 mr-2"></i>
                ${{ formattedPurchasePrice }}
            </span>

            <span class="flex items-center text-[16px] font-semibold">
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
                {{ property.lot_area }} sqft
            </li>
            <li class="flex items-center gap-1">
                <i class="fas fa-ruler-combined"></i>
                {{ property.floor_area }} sqft
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
    </Link>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

const props = defineProps({
    property: {
        type: Object,
        required: true,
    }
})

const { auth } = usePage().props
const isAuthenticated = computed(() => auth?.user !== null)

// Local bookmark status state
const localBookmarkStatus = ref(props.property.bookmark?.length > 0)

let url = ref(null)
const page = usePage();
if (page.props.auth.user?.role === 'admin') {
    url = `/properties/${props.property.id}`;
} else {
    url = `/property/${props.property.id}-${slugify(props.property.title)}`
}

const imageUrl = computed(() => `/storage/${props.property.image_url}`)
const formattedPurchasePrice = computed(() =>
    Number(props.property.purchase_price).toLocaleString()
)
const formattedRentPrice = computed(() =>
    Number(props.property.rent_price).toLocaleString()
)

function slugify(name) {
    return name.toLowerCase().replace(/\s+/g, '-')
}

// Toggle bookmark status
const toggleBookmark = () => {
    if (!isAuthenticated.value) {
        router.visit(route('login'))
        return
    }

    // Optimistically update UI
    localBookmarkStatus.value = !localBookmarkStatus.value
    if (localBookmarkStatus.value) {
        router.post(route('bookmarks.toggle', props.property.id), {}, {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Property added to bookmarks!')
            },
            onError: () => {
                // Revert on error
                localBookmarkStatus.value = !localBookmarkStatus.value
                toast.error('Failed to add property to bookmarks')
            }
        })
    } else {
        router.delete(route('bookmarks.toggle', props.property.id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Property removed from bookmarks!')
            },
            onError: () => {
                // Revert on error
                localBookmarkStatus.value = !localBookmarkStatus.value
                toast.error('Failed to remove property from bookmarks')
            }
        })
    }
}
</script>
