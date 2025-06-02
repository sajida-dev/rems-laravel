<template>

    <Head title="Messages" />
    <DashboardLayout>
        <div class="h-[calc(100vh-4rem)] flex">
            <!-- Left Sidebar - Chat List -->
            <div class="w-1/3 border-r border-gray-200 bg-white flex flex-col h-full">
                <!-- Header with New Chat Button -->
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Messages</h2>
                    <button @click="showNewChatModal = true"
                        class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>New Chat
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="p-4 border-b border-gray-200">
                    <div class="relative">
                        <input type="text" v-model="searchQuery" placeholder="Search messages..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-pink-500" />
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Chat List -->
                <div class="flex-1 overflow-y-auto">
                    <div v-if="loading" class="flex justify-center items-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-pink-500"></div>
                    </div>
                    <div v-else-if="chats.length === 0" class="flex justify-center items-center h-full text-gray-500">
                        No conversations yet
                    </div>
                    <div v-else class="divide-y divide-gray-200">
                        <button v-for="chat in chats" :key="chat.id" @click="selectChat(chat)"
                            class="w-full p-3 hover:bg-gray-50 transition-colors duration-200 flex items-center"
                            :class="{ 'bg-gray-50': selectedChat?.id === chat.id }">
                            <!-- Avatar -->
                            <div class="flex-shrink-0 relative">
                                <img :src="'/storage/' + chat.profile_photo_path" :alt="chat.name"
                                    class="h-10 w-10 rounded-full object-cover" />
                                <div v-if="chat.unread_count > 0"
                                    class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-pink-500 text-white text-xs flex items-center justify-center">
                                    {{ chat.unread_count }}
                                </div>
                                <!-- Online Status Dot -->
                                <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white"
                                    :class="chat.is_online ? 'bg-green-500' : 'bg-gray-400'"></div>
                            </div>

                            <!-- Chat Info -->
                            <div class="ml-3 flex-1 min-w-0">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-sm font-medium text-gray-900 truncate">{{ chat.name }}</h3>
                                    <span v-if="chat.last_message" class="text-xs text-gray-500 whitespace-nowrap ml-2">
                                        {{ formatDate(chat.last_message.created_at) }}
                                    </span>
                                </div>
                                <div class="mt-0.5 flex items-start">
                                    <!-- Message Status Icons -->
                                    <div v-if="chat.last_message?.is_sent_by_me" class="flex-shrink-0 mr-2 mt-0.5">
                                        <i class="fas fa-check text-xs"
                                            :class="chat.last_message.read_at ? 'text-blue-300' : 'text-gray-300'"></i>
                                        <i class="fas fa-check text-xs -ml-1"
                                            :class="chat.last_message.read_at ? 'text-blue-300' : 'text-gray-300'"></i>
                                    </div>
                                    <!-- Message Content -->
                                    <p class="text-sm text-gray-500 truncate text-left flex-1">
                                        <span v-if="chat.last_message" class="text-gray-600">
                                            {{ chat.last_message.content }}
                                        </span>
                                        <span v-else>No messages yet</span>
                                    </p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- New Chat Modal -->
            <div v-if="showNewChatModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 w-96">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">New Chat</h3>
                        <button @click="showNewChatModal = false" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="mb-4">
                        <input type="text" v-model="userSearch" placeholder="Search users..."
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-pink-500" />
                    </div>
                    <div class="max-h-60 overflow-y-auto">
                        <div v-if="searching" class="flex justify-center py-4">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-pink-500"></div>
                        </div>
                        <div v-else-if="searchError" class="text-center py-4 text-red-500">
                            {{ searchError }}
                        </div>
                        <div v-else-if="searchResults.length === 0" class="text-center py-4 text-gray-500">
                            No users found
                        </div>
                        <div v-else class="space-y-2">
                            <button v-for="user in searchResults" :key="user.id" @click="startNewChat(user)"
                                class="w-full p-3 hover:bg-gray-50 rounded-lg flex items-center">
                                <img :src="'/storage/' + user.profile_photo_path" :alt="user.name"
                                    class="h-10 w-10 rounded-full object-cover" />
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">{{ user.name }}</h4>
                                    <p class="text-xs text-gray-500">{{ user.email }}</p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Chat Window -->
            <div class="flex-1 flex flex-col bg-gray-50 h-full">
                <template v-if="selectedChat">
                    <!-- Chat Header -->
                    <div class="p-3 border-b border-gray-200 bg-white flex items-center">
                        <div class="relative">
                            <img :src="'/storage/' + selectedChat.profile_photo_path" :alt="selectedChat.name"
                                class="h-10 w-10 rounded-full object-cover" />
                            <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white"
                                :class="selectedChat.is_online ? 'bg-green-500' : 'bg-gray-400'"></div>
                        </div>
                        <div class="ml-3">
                            <h2 class="text-lg font-medium text-gray-900">{{ selectedChat.name }}</h2>
                            <p class="text-sm" :class="selectedChat.is_online ? 'text-green-500' : 'text-gray-500'">
                                {{ selectedChat.is_online ? 'Online' : selectedChat.last_seen }}
                            </p>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="flex-1 overflow-y-auto p-4" ref="messagesContainer">
                        <div v-if="messagesLoading" class="flex justify-center items-center h-full">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                        </div>
                        <div v-else class="space-y-3">
                            <div v-for="message in messages" :key="message.id" :class="[
                                'flex',
                                message.sender_id === user.id ? 'justify-end' : 'justify-start'
                            ]">
                                <div :class="[
                                    'max-w-[70%] rounded-lg p-2.5',
                                    message.sender_id === user.id
                                        ? 'bg-pink-500 text-white rounded-br-none'
                                        : 'bg-gray-100 text-gray-900 rounded-bl-none'
                                ]">
                                    <p class="text-sm break-words">{{ message.content }}</p>
                                    <div class="flex items-center justify-end mt-1 space-x-2">
                                        <p class="text-xs"
                                            :class="message.sender_id === user.id ? 'text-pink-100' : 'text-gray-500'">
                                            {{ formatDate(message.created_at) }}
                                        </p>
                                        <!-- Message Status Indicators -->
                                        <div v-if="message.sender_id === user.id" class="flex items-center">
                                            <!-- Single tick for sent -->
                                            <i class="fas fa-check text-xs"
                                                :class="message.read_at ? 'text-blue-300' : 'text-gray-300'"></i>
                                            <!-- Double tick for delivered/read -->
                                            <i class="fas fa-check text-xs -ml-1"
                                                :class="message.read_at ? 'text-blue-300' : 'text-gray-300'"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div class="p-3 border-t border-gray-200 bg-white">
                        <form @submit.prevent="sendMessage" class="flex items-center space-x-3">
                            <button type="button" @click="openAttachmentMenu" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-paperclip text-xl"></i>
                            </button>
                            <input type="text" v-model="newMessage" placeholder="Type a message..."
                                class="flex-1 rounded-full border border-gray-300 px-4 py-2 focus:outline-none focus:border-pink-500"
                                :disabled="!selectedChat" />
                            <button type="submit"
                                class="text-pink-500 hover:text-pink-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="!selectedChat || !newMessage.trim()">
                                <i class="fas fa-paper-plane text-xl"></i>
                            </button>
                        </form>
                    </div>
                </template>

                <!-- No Chat Selected -->
                <div v-else class="flex-1 flex items-center justify-center bg-gray-50">
                    <div class="text-center">
                        <i class="fas fa-comments text-6xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900">Select a conversation</h3>
                        <p class="text-gray-500">Choose a chat to start messaging</p>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { formatDistanceToNow } from 'date-fns'
import Echo from 'laravel-echo'
import axios from 'axios'

const props = defineProps({
    user: Object,
    initialChats: Array
})

const searchQuery = ref('')
const loading = ref(false)
const messagesLoading = ref(false)
const chats = ref(props.initialChats || [])
const selectedChat = ref(null)
const messages = ref([])
const newMessage = ref('')
const messagesContainer = ref(null)
const showNewChatModal = ref(false)
const userSearch = ref('')
const searching = ref(false)
const searchResults = ref([])
const searchError = ref(null)
let searchTimeout = null
let echo = null

const formatDate = (date) => {
    return formatDistanceToNow(new Date(date), { addSuffix: true })
}

const selectChat = async (chat) => {
    selectedChat.value = chat
    messagesLoading.value = true
    try {
        // Fetch messages
        const response = await fetch(`/messages/${chat.id}`)
        const data = await response.json()
        messages.value = data.messages

        // Mark messages as read
        await fetch(`/messages/${chat.id}/mark-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })

        // Update unread count in the chat list
        const chatIndex = chats.value.findIndex(c => c.id === chat.id)
        if (chatIndex !== -1) {
            chats.value[chatIndex].unread_count = 0
        }

        await nextTick()
        scrollToBottom()
    } catch (error) {
        console.error('Error fetching messages:', error)
    } finally {
        messagesLoading.value = false
    }
}

const sendMessage = async () => {
    if (!newMessage.value.trim() || !selectedChat.value) return

    try {
        const response = await fetch(`/messages/${selectedChat.value.id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ content: newMessage.value })
        })

        const data = await response.json()
        messages.value.push(data.message)

        // Update the chat list with new message
        const chatIndex = chats.value.findIndex(c => c.id === selectedChat.value.id)
        if (chatIndex !== -1) {
            chats.value[chatIndex].last_message = {
                content: data.message.content,
                created_at: data.message.created_at,
                sender_id: data.message.sender_id,
                is_sent_by_me: true
            }
            // Move this chat to the top
            const chat = chats.value.splice(chatIndex, 1)[0]
            chats.value.unshift(chat)
        }

        newMessage.value = ''
        await nextTick()
        scrollToBottom()
    } catch (error) {
        console.error('Error sending message:', error)
    }
}

const scrollToBottom = () => {
    if (messagesContainer.value) {
        const scrollOptions = {
            top: messagesContainer.value.scrollHeight,
            behavior: 'smooth'
        }
        messagesContainer.value.scrollTo(scrollOptions)
    }
}

const openAttachmentMenu = () => {
    // Implement attachment menu
}

const searchUsers = async () => {
    if (userSearch.value.length < 1) {
        searchResults.value = []
        searchError.value = null
        return
    }

    searching.value = true
    searchError.value = null

    try {
        const response = await axios.get(route('messages.search-users'), {
            params: { query: userSearch.value }
        })
        console.log('response', response)
        if (response.data.success) {
            searchResults.value = response.data.users || []
            searchError.value = null
        } else {
            searchResults.value = []
            searchError.value = response.data.message || 'Failed to search users'
        }
    } catch (error) {
        console.error('Error searching users:', error)
        searchResults.value = []
        searchError.value = error.response?.data?.message || 'An error occurred while searching'
    } finally {
        searching.value = false
    }
}

const startNewChat = async (user) => {
    showNewChatModal.value = false
    userSearch.value = ''
    searchResults.value = []
    searchError.value = null

    // Check if chat already exists
    const existingChat = chats.value.find(chat => chat.id === user.id)
    if (existingChat) {
        selectChat(existingChat)
        return
    }

    // Add new chat to the list
    const newChat = {
        id: user.id,
        name: user.name,
        profile_photo_path: user.profile_photo_path,
        last_message: null,
        unread_count: 0
    }

    chats.value.unshift(newChat)
    selectChat(newChat)
}

// Watch for changes in userSearch with debounce
watch(userSearch, () => {
    if (searchTimeout) {
        clearTimeout(searchTimeout)
    }
    searchTimeout = setTimeout(searchUsers, 300)
})

// Clear search results and errors when modal is closed
watch(showNewChatModal, (newValue) => {
    if (!newValue) {
        userSearch.value = ''
        searchResults.value = []
        searchError.value = null
    }
})

// Watch for chat selection to scroll to bottom
watch(selectedChat, () => {
    scrollToBottom()
})

onMounted(() => {
    // Initialize Laravel Echo
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
        .listen('NewMessage', (data) => {
            // Update messages if in the same chat
            if (selectedChat.value?.id === data.message.sender_id) {
                messages.value.push(data.message)
                nextTick(() => {
                    scrollToBottom()
                })
            }

            // Update chat list
            const chatIndex = chats.value.findIndex(c => c.id === data.message.sender_id)
            if (chatIndex !== -1) {
                chats.value[chatIndex].last_message = {
                    content: data.message.content,
                    created_at: data.message.created_at,
                    sender_id: data.message.sender_id,
                    is_sent_by_me: false
                }
                // Move this chat to the top
                const chat = chats.value.splice(chatIndex, 1)[0]
                chats.value.unshift(chat)
            }
        })
})
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