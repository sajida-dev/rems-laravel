<template>
    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Messages</h3>
            <Link :href="route('messages.index')" class="text-sm text-blue-600 hover:text-blue-800">
            View all
            </Link>
        </div>

        <div v-if="messages.length === 0" class="text-center text-gray-500 py-4">
            No new messages
        </div>

        <div v-else class="space-y-3 max-h-[400px] overflow-y-auto">
            <Link v-for="message in messages" :key="message.id"
                :href="route('messages.index', { user: message.sender_id })"
                class="block p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors"
                :class="{ 'bg-blue-50': !message.read_at }" @click="handleMessageClick(message)">
            <div class="flex items-start space-x-3">
                <!-- Avatar with unread indicator -->
                <div class="relative flex-shrink-0">
                    <img :src="'/storage/' + message.sender.profile_photo_path" :alt="message.sender.name"
                        class="h-10 w-10 rounded-full object-cover border-2"
                        :class="{ 'border-blue-500': !message.read_at, 'border-transparent': message.read_at }" />
                    <div v-if="message.unread_count > 0"
                        class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-pink-500 text-white text-xs flex items-center justify-center">
                        {{ message.unread_count }}
                    </div>
                </div>

                <!-- Message Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-900">{{ message.sender.name }}</p>
                        <p class="text-xs text-gray-500 whitespace-nowrap ml-2">
                            {{ formatTime(message.created_at) }}
                        </p>
                    </div>
                    <p class="text-sm text-gray-600 truncate mt-1">
                        {{ message.content }}
                    </p>
                </div>
            </div>
            </Link>
        </div>
    </div>
</template>

<script setup>
import { router, Link } from '@inertiajs/vue3'
import { formatDistanceToNow } from 'date-fns'

const props = defineProps({
    messages: {
        type: Array,
        required: true
    }
})

const emit = defineEmits(['close'])

const formatTime = (date) => {
    return formatDistanceToNow(new Date(date), { addSuffix: true })
}

const handleMessageClick = (message) => {
    if (!message.read_at) {
        router.post(route('messages.mark-as-read', message.id), {}, {
            preserveScroll: true,
            onSuccess: () => {
                // Update local state
                message.read_at = new Date().toISOString()
            }
        })
    }
    emit('close')
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
