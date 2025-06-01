<template>
    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Messages</h3>
            <button v-if="messages.length > 0" @click="markAllAsRead" class="text-sm text-blue-600 hover:text-blue-800">
                Mark all as read
            </button>
        </div>

        <div v-if="messages.length === 0" class="text-center text-gray-500 py-4">
            No messages
        </div>

        <div v-else class="space-y-2 max-h-[400px] overflow-y-auto">
            <div v-for="message in messages" :key="message.id" class="p-3 rounded-lg hover:bg-gray-50 cursor-pointer"
                :class="{ 'bg-blue-50': !message.read_at }" @click="handleMessageClick(message)">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <img :src="'/storage' + '/' + message.sender.profile_photo_path" :alt="message.sender.name"
                            class="h-8 w-8 rounded-full object-cover" />
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">{{ message.sender.name }}</p>
                            <p class="text-xs text-gray-400">{{ formatTime(message.created_at) }}</p>
                        </div>
                        <p class="text-sm text-gray-500 truncate">{{ message.content }}</p>
                    </div>
                    <div v-if="!message.read_at" class="ml-2">
                        <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
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

    // Navigate to the message thread
    router.visit(route('messages.show', message.id))
    emit('close')
}

const markAllAsRead = () => {
    router.post(route('messages.mark-all-as-read'), {}, {
        preserveScroll: true,
        onSuccess: () => {
            // Update local state
            props.messages.forEach(message => {
                message.read_at = new Date().toISOString()
            })
        }
    })
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
