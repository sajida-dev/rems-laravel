<template>

    <Head :title="property.title + ' - Bookmarks'" />
    <DashboardLayout>
        <div class="bg-white rounded w-[95%] p-5 mx-auto">
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold">Property Bookmarks</h2>
                    <Link :href="route('bookmarks.index')" class="text-blue-500 hover:text-blue-700">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Bookmarks
                    </Link>
                </div>
            </div>

            <div class="flex gap-6">
                <!-- Left Side - Property Details -->
                <div class="w-2/3">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex flex-col space-y-6">
                            <img :src="'/storage/' + property.image_url" :alt="property.title"
                                class="w-full h-64 object-cover rounded-lg">

                            <div class="space-y-4">
                                <h3 class="text-2xl font-semibold">{{ property.title }}</h3>
                                <p class="text-gray-600">{{ property.location }}</p>

                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm text-gray-500">Category</p>
                                        <p class="font-medium">{{ property.category?.name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Agent</p>
                                        <p class="font-medium">{{ property.agent?.user?.name }}</p>
                                    </div>
                                    <div v-if="property.type === 'rent'">
                                        <p class="text-sm text-gray-500">Rent Price</p>
                                        <p class="font-medium text-pink-500">${{ property.rent_price }}/mo</p>
                                    </div>
                                    <div v-if="property.type === 'buy'">
                                        <p class="text-sm text-gray-500">Purchase Price</p>
                                        <p class="font-medium text-green-500">${{ property.purchase_price }}</p>
                                    </div>
                                    <div v-if="property.type === 'both'">
                                        <p class="text-sm text-gray-500">Rent Price</p>
                                        <p class="font-medium text-pink-500">${{ property.rent_price }}/mo</p>
                                    </div>
                                    <div v-if="property.type === 'both'">
                                        <p class="text-sm text-gray-500">Purchase Price</p>
                                        <p class="font-medium text-green-500">${{ property.purchase_price }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-4 gap-4 mt-4">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-bed text-gray-500"></i>
                                        <span>{{ property.bedrooms }} Beds</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-bath text-gray-500"></i>
                                        <span>{{ property.bathrooms }} Baths</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-ruler-combined text-gray-500"></i>
                                        <span>{{ property.lot_area }} sqft</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-ruler-combined text-gray-500"></i>
                                        <span>{{ property.floor_area }} sqft</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Bookmarked Users -->
                <div class="w-1/3">
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-4 border-b">
                            <h3 class="text-lg font-semibold">Bookmarked Users</h3>
                            <p class="text-sm text-gray-500">Total: {{ bookmarkedUsers.total }}</p>
                        </div>

                        <div v-if="bookmarkedUsers.data.length === 0" class="p-6 text-center">
                            <i class="fas fa-heart text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500">No users have bookmarked this property yet.</p>
                        </div>

                        <div v-else class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                            <div v-for="bookmark in bookmarkedUsers.data" :key="bookmark.id"
                                class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <img :src="bookmark.user.profile_photo_url" :alt="bookmark.user.name"
                                        class="h-10 w-10 rounded-full object-cover">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ bookmark.user.name }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                            {{ bookmark.user.email }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            Bookmarked {{ formatDate(bookmark.created_at) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 border-t">
                            <Pagination :links="bookmarkedUsers.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({
    property: {
        type: Object,
        required: true
    },
    bookmarkedUsers: {
        type: Object,
        required: true
    }
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>