<template>
    <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-50">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Messages</h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="max-h-96 overflow-y-auto">
            <div v-if="sortedMessages.length === 0" class="p-4 text-center text-gray-500">
                No new messages
            </div>
            <div v-else class="divide-y divide-gray-200">
                <Link v-for="message in sortedMessages" :key="message.id" :href="message.link"
                    class="block p-4 hover:bg-gray-50 transition-colors duration-200"
                    @click="handleMessageClick(message)">
                <div class="flex items-start space-x-3">
                    <!-- Avatar -->
                    <div class="relative">
                        <img :src="'/storage/' + message.profile_photo_path" :alt="message.name"
                            class="h-10 w-10 rounded-full object-cover" />
                        <!-- Online Status -->
                        <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white"
                            :class="message.is_online ? 'bg-green-500' : 'bg-gray-400'"></div>
                    </div>

                    <!-- Message Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <p class="text-sm font-medium text-gray-900">{{ message.name }}</p>
                            <span class="text-xs text-gray-500">{{ formatDate(message.last_message.created_at) }}</span>
                        </div>
                        <div class="flex items-center gap-2 justify-between">
                            <p class="mt-1 text-sm text-gray-500 truncate">{{ message.last_message.content }}</p>
                            <div v-if="message.unread_count > 0"
                                class="flex-shrink-0 h-5 w-5 rounded-full bg-pink-500 text-white text-xs flex items-center justify-center">
                                {{ message.unread_count }}
                            </div>
                        </div>
                    </div>
                </div>
                </Link>
            </div>
        </div>

        <div class="p-4 border-t border-gray-200">
            <Link :href="route('messages.index')"
                class="block text-center text-sm font-medium text-pink-600 hover:text-pink-500">
            View all messages
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { format } from 'date-fns'
import axios from 'axios'
import { ref, computed, watch } from 'vue'

const props = defineProps({
    messages: {
        type: Array,
        required: true
    },
    maxMessages: {
        type: Number,
        default: 20
    }
})

const emit = defineEmits(['close'])

const messageCache = ref(new Map())

const sortedMessages = computed(() => {
    const now = Date.now()
    for (const [key, value] of messageCache.value.entries()) {
        if (now - value.timestamp > 60000) {
            messageCache.value.delete(key)
        }
    }

    const sorted = [...props.messages].sort((a, b) => {
        const timeA = new Date(a.last_message.created_at)
        const timeB = new Date(b.last_message.created_at)
        return timeB - timeA
    })

    messageCache.value.set('sorted', {
        data: sorted,
        timestamp: now
    })

    return sorted.slice(0, props.maxMessages)
})

watch(() => props.messages, () => {
    messageCache.value.clear()
}, { deep: true })

const formatDate = (date) => {
    const cacheKey = date
    const cached = messageCache.value.get(cacheKey)
    if (cached) {
        return cached.data
    }

    const messageDate = new Date(date)
    const today = new Date()
    const yesterday = new Date(today)
    yesterday.setDate(yesterday.getDate() - 1)

    let formatted
    if (messageDate.toDateString() === today.toDateString()) {
        formatted = format(messageDate, 'h:mm a')
    } else if (messageDate.toDateString() === yesterday.toDateString()) {
        formatted = `Yesterday ${format(messageDate, 'h:mm a')}`
    } else if (messageDate.getFullYear() === today.getFullYear()) {
        formatted = format(messageDate, 'MMM d, h:mm a')
    } else {
        formatted = format(messageDate, 'MMM d, yyyy h:mm a')
    }

    messageCache.value.set(cacheKey, {
        data: formatted,
        timestamp: Date.now()
    })

    return formatted
}

const handleMessageClick = async (message) => {
    try {
        await axios.post(route('messages.mark-chat-read', message.id))
        emit('close')
    } catch (error) {
        console.error('Error marking messages as read:', error)
    }
}

const handleNewMessage = (message) => {
    // Update messages array
    const existingIndex = props.messages.findIndex(m => m.id === message.id)
    if (existingIndex === -1) {
        const updatedMessages = [message, ...props.messages]
        emit('update:messages', updatedMessages)
        if (message.unread_count !== undefined) {
            emit('update:totalUnread', message.unread_count)
        }
    }
    // Update total unread count
    if (message.unread_count !== undefined) {
        emit('update:totalUnread', message.unread_count)
    }
}

let messageUpdateTimeout

const handleNewMessageWebSocket = (message) => {
    clearTimeout(messageUpdateTimeout)
    messageUpdateTimeout = setTimeout(() => {
        handleNewMessage(message)
    }, 100)
}
</script>

<style scoped>
.max-h-96 {
    max-height: 24rem;
}

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
