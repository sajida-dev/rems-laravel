<template>

    <Head title="Property Bookmarks" />
    <DashboardLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-2xl font-semibold mb-4">Property Bookmarks</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="property in properties.data" :key="property.id"
                                class="bg-white rounded-lg shadow-md overflow-hidden">
                                <div class="relative">
                                    <img :src="'/storage/' + property.image_url" :alt="property.title"
                                        class="w-full h-48 object-cover">
                                    <div class="absolute top-2 right-2 bg-pink-500 text-white px-3 py-1 rounded-full">
                                        <i class="fas fa-heart mr-1"></i>
                                        {{ property.bookmark_count }} Bookmarks
                                    </div>
                                </div>

                                <div class="p-4">
                                    <h3 class="text-lg font-semibold mb-2">{{ property.title }}</h3>
                                    <p class="text-gray-600 mb-2">{{ property.location }}</p>

                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-500">
                                            Agent: {{ property.agent?.user?.name }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            Category: {{ property.category?.name }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-bold text-pink-500">
                                            ${{ property.rent_price }}/mo
                                        </span>
                                        <Link :href="route('bookmarks.users', property.id)"
                                            class="text-blue-500 hover:text-blue-700">
                                        View Bookmarked Users
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Pagination :links="properties.links" class="mt-6" />
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
    properties: {
        type: Object,
        required: true
    }
});
</script>