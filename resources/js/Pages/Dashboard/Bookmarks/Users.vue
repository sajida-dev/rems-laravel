<template>

    <Head :title="'Users who bookmarked ' + property.title" />
    <DashboardLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-2xl font-semibold">Users who bookmarked</h2>
                                <h3 class="text-lg text-gray-600">{{ property.title }}</h3>
                            </div>
                            <Link :href="route('bookmarks.admin.index')" class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Back to Properties
                            </Link>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="bookmark in bookmarkedUsers.data" :key="bookmark.id"
                                class="bg-white rounded-lg shadow-md p-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img :src="bookmark.user.profile_photo_url" :alt="bookmark.user.name"
                                            class="h-12 w-12 rounded-full object-cover">
                                    </div>
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

                        <Pagination :links="bookmarkedUsers.links" class="mt-6" />
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

const props = defineProps({
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