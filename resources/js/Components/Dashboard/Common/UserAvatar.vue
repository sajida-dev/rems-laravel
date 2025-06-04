<template>
    <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
        <div class="relative">
            <img :src="user.profile_photo_path ? '/storage/' + user.profile_photo_path : '/images/default-avatar.png'"
                :alt="user.name" class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-sm">
            <div class="absolute -bottom-1 -right-1 h-4 w-4 bg-pink-500 rounded-full border-2 border-white"></div>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">{{ user.name }}</p>
            <p class="text-sm text-gray-500 truncate">{{ user.email }}</p>
            <p class="text-xs text-gray-400">{{ formatDate(date) }}</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    user: {
        type: Object,
        required: true
    },
    date: {
        type: String,
        required: true
    }
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>