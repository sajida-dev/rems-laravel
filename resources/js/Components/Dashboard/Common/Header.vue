<template>
    <header class="bg-white shadow px-4 py-4 flex lg:justify-end justify-between items-center">
        <button class="lg:hidden text-gray-800 text-2xl" @click="$emit('toggle-sidebar')">
            <i class="fas fa-bars"></i>
        </button>

        <div class="flex items-center space-x-4 sm:space-x-6">
            <div ref="emailRef" class="relative">
                <button @click.stop="toggle('email')" class="focus:outline-none relative">
                    <i class="fa fa-envelope text-xl"></i>
                    <span v-if="totalUnread > 0"
                        class="absolute -top-2 -right-2 bg-pink-500 text-white text-xs font-semibold px-1.5 py-0.5 rounded-full min-w-[1.25rem] flex items-center justify-center">
                        {{ totalUnread }}
                    </span>
                </button>

                <transition name="fade">
                    <div v-if="open === 'email'" class="dropdown-wrapper">
                        <MessageDropdown :messages="messages" @close="open = null" />
                    </div>
                </transition>
            </div>

            <div ref="notifRef" class="relative">
                <button @click.stop="toggle('notification')" class="focus:outline-none relative">
                    <i class="fa fa-bell text-xl"></i>
                    <span v-if="unreadCount"
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-semibold px-1.5 py-0.5 rounded-full min-w-[1.25rem] flex items-center justify-center">
                        {{ unreadCount }}
                    </span>
                </button>

                <transition name="fade">
                    <div v-if="open === 'notification'" class="dropdown-wrapper">
                        <NotificationDropdown :notifications="notifications" @close="open = null"
                            @mark-as-read="markAsRead" @mark-all-as-read="markAllAsRead" />
                    </div>
                </transition>
            </div>

            <div ref="userRef" class="relative">
                <button @click.stop="toggle('user')" class="focus:outline-none">
                    <img :src="'/storage' + '/' + user.profile_photo_path" :alt="user.name"
                        class="h-10 w-10 rounded-full object-cover" />
                </button>

                <transition name="fade">
                    <div v-if="open === 'user'" class="dropdown-wrapper">
                        <UserDropdown :user="user" @close="open = null" />
                    </div>
                </transition>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import Echo from 'laravel-echo'
import MessageDropdown from './MessageDropdown.vue'
import NotificationDropdown from './NotificationDropdown.vue'
import UserDropdown from './UserDropdown.vue'
import { useNotifications } from '@/composables/useNotifications'
import axios from 'axios'

const props = defineProps({
    user: { type: Object, required: true }
})

const open = ref(null)
const emailRef = ref(null)
const notifRef = ref(null)
const userRef = ref(null)
const messages = ref([])
const totalUnread = ref(0)
let echo = null
let messageUpdateTimeout = null

const {
    notifications,
    unreadCount,
    markAsRead,
    markAllAsRead
} = useNotifications(props.user.id)

function toggle(menu) {
    open.value = open.value === menu ? null : menu
}

function onClickOutside(e) {
    if (
        !emailRef.value?.contains(e.target) &&
        !notifRef.value?.contains(e.target) &&
        !userRef.value?.contains(e.target)
    ) {
        open.value = null
    }
}

const fetchUnreadMessages = async () => {
    try {
        const response = await axios.get(route('messages.notifications'), {
            params: {
                last_message_id: messages.value[0]?.id || 0,
                limit: 20,
                cache: true,
                include_read: false
            }
        })

        if (response.data.messages) {
            const existingIds = new Set(messages.value.map(m => m.id))
            const newMessages = response.data.messages.filter(
                newMsg => !existingIds.has(newMsg.id)
            )

            if (newMessages.length > 0) {
                messages.value = [...newMessages, ...messages.value]
            }
        }
        totalUnread.value = response.data.total_unread
    } catch (error) {
        console.error('Error fetching unread messages:', error)
    }
}

// Handle new message from WebSocket
const handleNewMessage = (message) => {
    // Update messages array
    const existingIndex = messages.value.findIndex(m => m.id === message.id)
    if (existingIndex === -1) {
        messages.value.unshift(message)
        totalUnread.value++
    }

    // Update total unread count
    if (message.unread_count !== undefined) {
        totalUnread.value = message.unread_count
    }
}

onMounted(() => {
    document.addEventListener('click', onClickOutside)

    // Initial fetch
    fetchUnreadMessages()

    // Set up Echo listener for new messages
    echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
        wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
        wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
        wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    })

    // Listen for new messages
    echo.private(`messages.${props.user.id}`)
        .listen('NewMessage', (e) => {
            // Clear any existing timeout
            if (messageUpdateTimeout) {
                clearTimeout(messageUpdateTimeout)
            }

            // Set new timeout to batch updates
            messageUpdateTimeout = setTimeout(() => {
                handleNewMessage(e.message)
            }, 100) // Reduced debounce time since we're not polling
        })

    // Listen for message read status updates
    echo.private(`messages.${props.user.id}`)
        .listen('MessageRead', (e) => {
            const messageIndex = messages.value.findIndex(m => m.id === e.message_id)
            if (messageIndex !== -1) {
                messages.value[messageIndex].read_at = e.read_at
                if (e.unread_count !== undefined) {
                    totalUnread.value = e.unread_count
                }
            }
        })
})

onUnmounted(() => {
    document.removeEventListener('click', onClickOutside)
    if (messageUpdateTimeout) {
        clearTimeout(messageUpdateTimeout)
    }
    if (echo) {
        echo.leave(`messages.${props.user.id}`)
    }
})
</script>

<style scoped>
.dropdown-wrapper {
    @apply fixed sm:absolute sm:right-0 sm:left-auto sm:top-full sm:w-72 w-[90%] left-1/2 transform -translate-x-1/2 sm:translate-x-0 z-50 mt-2;
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-height: 75vh;
    overflow-y: auto;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

@media (min-width: 1024px) {
    .dropdown-wrapper {
        max-height: none;
        overflow-y: visible;
    }
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
