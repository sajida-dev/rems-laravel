<template>
    <div class="bg-white rounded-lg shadow-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Notifications</h3>
            <button v-if="notifications.length" @click="$emit('mark-all-as-read')"
                class="text-sm text-blue-600 hover:text-blue-800">
                Mark all as read
            </button>
        </div>

        <div v-if="unreadNotifications.length" class="space-y-2">
            <div v-for="notification in unreadNotifications" :key="notification.id"
                class="p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 cursor-pointer"
                :class="{ 'bg-blue-50': !notification.read_at }" @click="handleNotificationClick(notification)">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i :class="notification.data?.icon || 'fa-bell'" class="text-gray-500"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">
                            {{ notification.data?.title || 'Notification' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ notification.data?.message || notification.message }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ formatDate(notification.created_at) }}
                        </p>
                    </div>
                    <button v-if="!notification.read_at" @click.stop="$emit('mark-as-read', notification.id)"
                        class="ml-2 text-sm text-blue-600 hover:text-blue-800">
                        Mark as read
                    </button>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-4 text-gray-500">
            No notifications
        </div>
    </div>
</template>

<script setup>
import { formatDistanceToNow } from 'date-fns'
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    notifications: {
        type: Array,
        required: true
    }
})

const unreadNotifications = computed(() => {
    return props.notifications.filter(notification => !notification.read_at)
})

const emit = defineEmits(['mark-as-read', 'mark-all-as-read', 'close'])

const formatDate = (date) => {
    return formatDistanceToNow(new Date(date), { addSuffix: true })
}

const handleNotificationClick = (notification) => {
    if (!notification.read_at) {
        emit('mark-as-read', notification.id)
    }

    if (notification.data?.link) {
        router.visit(notification.data.link)
        emit('close')
    }
}
</script>

<style scoped>
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
