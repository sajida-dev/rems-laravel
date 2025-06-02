<template>

    <Head title="Messages" />
    <DashboardLayout>
        <div class="h-[calc(100vh-4rem)] flex flex-col md:flex-row overflow-hidden">
            <!-- Left Sidebar - Chat List -->
            <div class="w-full md:w-70 lg:w-72 border-r border-gray-200 bg-white flex flex-col h-full overflow-hidden">
                <!-- Header with New Chat Button -->
                <div class="p-3 border-b border-gray-200 flex justify-between items-center flex-shrink-0">
                    <h2 class="text-lg font-semibold">Messages</h2>
                    <button @click="showNewChatModal = true"
                        class="bg-pink-500 text-white px-3 py-1.5 rounded-lg hover:bg-pink-600 transition-colors text-sm">
                        <i class="fas fa-plus mr-1"></i>New Chat
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="p-3 border-b border-gray-200 flex-shrink-0">
                    <div class="relative">
                        <input type="text" v-model="searchQuery" placeholder="Search messages..."
                            class="w-full pl-8 pr-3 py-1.5 rounded-lg border border-gray-300 focus:outline-none focus:border-pink-500 text-sm" />
                        <i class="fas fa-search absolute left-2.5 top-2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Chat List -->
                <div class="flex-1 overflow-y-auto">
                    <div v-if="loading" class="flex justify-center items-center h-full">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-pink-500"></div>
                    </div>
                    <div v-else-if="chats.length === 0"
                        class="flex justify-center items-center h-full text-gray-500 text-sm">
                        No conversations yet
                    </div>
                    <div v-else class="divide-y divide-gray-200">
                        <button v-for="chat in chats" :key="chat.id" @click.stop="selectChat(chat)"
                            class="w-full p-2 hover:bg-gray-50 transition-colors duration-200 flex items-center cursor-pointer"
                            :class="{ 'bg-gray-50': selectedChat?.id === chat.id }">
                            <!-- Avatar -->
                            <div class="flex-shrink-0 relative">
                                <img :src="'/storage/' + chat.profile_photo_path" :alt="chat.name"
                                    class="h-10 w-10 rounded-full object-cover" />

                                <!-- Online Status Dot -->
                                <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white"
                                    :class="chat.is_online ? 'bg-green-500' : 'bg-gray-400'"></div>
                            </div>

                            <!-- Chat Info -->
                            <div class="ml-2 flex-1 min-w-0">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-sm font-medium text-gray-900 truncate">{{ chat.name }}</h3>
                                    <span v-if="chat.last_message" class="text-xs whitespace-nowrap flex-shrink-0 ml-2"
                                        :class="chat.unread_count > 0 ? 'text-pink-500 font-medium' : 'text-gray-500'">
                                        {{ formatDate(chat.last_message.created_at) }}
                                    </span>
                                </div>
                                <div class="mt-0.5 flex items-start">
                                    <div v-if="chat.last_message?.is_sent_by_me" class="flex-shrink-0 mr-1 mt-0.5">
                                        <i class="fas fa-check text-xs"
                                            :class="chat.last_message.read_at ? 'text-blue-500' : 'text-gray-300'"></i>
                                        <i class="fas fa-check text-xs -ml-1"
                                            :class="chat.last_message.read_at ? 'text-blue-500' : 'text-gray-300'"></i>
                                    </div>
                                    <p class="text-xs text-gray-500 truncate text-left flex-1 min-w-0">
                                        <span v-if="chat.last_message"
                                            class="text-gray-600 flex items-center justify-between gap-1">
                                            <span class="truncate break-all">{{ chat.last_message.content }}</span>
                                            <div v-if="chat.unread_count > 0"
                                                class="flex-shrink-0 h-4 w-4 rounded-full bg-pink-500 text-white text-xs flex items-center justify-center">
                                                {{ chat.unread_count }}
                                            </div>
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
            <div class="flex-1 flex flex-col bg-gray-50 h-full overflow-hidden">
                <template v-if="selectedChat">
                    <!-- Chat Header -->
                    <div class="p-3 border-b border-gray-200 bg-white flex items-center flex-shrink-0">
                        <div class="relative">
                            <img :src="'/storage/' + selectedChat.profile_photo_path" :alt="selectedChat.name"
                                class="h-10 w-10 rounded-full object-cover" />
                            <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white"
                                :class="selectedChat.is_online ? 'bg-green-500' : 'bg-gray-400'"></div>
                        </div>
                        <div class="ml-2">
                            <h2 class="text-sm font-medium text-gray-900">{{ selectedChat.name }}</h2>
                            <p class="text-xs" :class="selectedChat.is_online ? 'text-green-500' : 'text-gray-500'">
                                {{ selectedChat.is_online ? 'Online' : selectedChat.last_seen }}
                            </p>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="flex-1 overflow-y-auto p-3" ref="messagesContainer">
                        <div v-if="messagesLoading" class="flex justify-center items-center h-full">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-pink-500"></div>
                        </div>
                        <div v-else class="space-y-4">
                            <div v-for="(messages, date) in groupedMessages" :key="date" class="space-y-2">
                                <!-- Date Header -->
                                <div class="flex justify-center">
                                    <span class="px-3 py-1 text-xs font-medium text-gray-500 bg-gray-100 rounded-full">
                                        {{ date }}
                                    </span>
                                </div>
                                <!-- Messages for this date -->
                                <div v-for="message in messages" :key="message.id" :class="[
                                    'flex',
                                    message.sender_id === user.id ? 'justify-end' : 'justify-start'
                                ]">
                                    <div :class="[
                                        'message-bubble rounded-lg p-2 max-w-[85%]',
                                        message.sender_id === user.id
                                            ? 'bg-pink-500 text-white rounded-br-none'
                                            : 'bg-gray-100 text-gray-900 rounded-bl-none'
                                    ]">
                                        <!-- Message Content -->
                                        <p v-if="message.content"
                                            class="text-sm break-all whitespace-pre-wrap overflow-wrap-anywhere">
                                            {{ message.content }}
                                        </p>

                                        <!-- Attachments -->
                                        <div v-if="message.attachments && message.attachments.length > 0"
                                            class="mt-2 space-y-2">
                                            <div v-for="(attachment, index) in message.attachments" :key="index"
                                                class="flex flex-col gap-2 bg-white bg-opacity-10 rounded p-2">
                                                <!-- File Preview -->
                                                <div v-if="getFilePreview(attachment)" class="w-full">
                                                    <a :href="attachment?.url" target="_blank" class="block">
                                                        <component :is="getFilePreview(attachment).component"
                                                            v-bind="getFilePreview(attachment).props"
                                                            @load="onFileLoad" />
                                                    </a>
                                                </div>
                                                <!-- File Info -->
                                                <div class="flex items-center gap-2">
                                                    <i :class="[
                                                        getFileIcon(attachment?.type),
                                                        message.sender_id === user.id ? 'text-pink-100' : 'text-gray-500'
                                                    ]">
                                                    </i>
                                                    <a :href="attachment?.url" target="_blank"
                                                        class="text-sm truncate hover:underline"
                                                        :class="message.sender_id === user.id ? 'text-pink-100' : 'text-gray-700'">
                                                        {{ attachment?.name || 'Unnamed file' }}
                                                    </a>
                                                    <span class="text-xs text-gray-500">
                                                        {{ formatFileSize(attachment?.size) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-end mt-1 space-x-1">
                                            <p class="text-xs flex-shrink-0"
                                                :class="message.sender_id === user.id ? 'text-pink-100' : 'text-gray-500'">
                                                {{ formatDate(message.created_at) }}
                                            </p>
                                            <div v-if="message.sender_id === user.id"
                                                class="flex items-center flex-shrink-0">
                                                <i class="fas fa-check text-xs"
                                                    :class="message.read_at ? 'text-blue-300' : 'text-gray-300'"></i>
                                                <i class="fas fa-check text-xs -ml-1"
                                                    :class="message.read_at ? 'text-blue-300' : 'text-gray-300'"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div class="p-2 border-t border-gray-200 bg-white flex-shrink-0">
                        <form @submit.prevent="sendMessage" class="flex items-end space-x-2">
                            <div class="relative attachment-menu">
                                <button type="button" @click="openAttachmentMenu"
                                    class="text-gray-500 hover:text-gray-700 flex-shrink-0 p-2">
                                    <i class="fas fa-paperclip text-lg"></i>
                                </button>
                                <!-- Attachment Menu -->
                                <div v-if="showAttachmentMenu"
                                    class="absolute bottom-full left-0 mb-2 bg-white rounded-lg shadow-lg border border-gray-200 p-2 w-48 z-50">
                                    <input type="file" ref="fileInput" @change="handleFileSelect" class="hidden"
                                        multiple accept="image/*,.pdf,.doc,.docx,.txt">
                                    <button type="button" @click="triggerFileInput"
                                        class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                        <i class="fas fa-file mr-2"></i>Attach Files
                                    </button>
                                </div>
                            </div>
                            <div class="flex-1 relative">
                                <textarea v-model="newMessage" placeholder="Type a message..."
                                    class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:border-pink-500 resize-none min-h-[36px] max-h-[100px]"
                                    :disabled="!selectedChat" @input="autoResize"
                                    @keydown.enter.prevent="handleEnterKey" rows="1"></textarea>
                            </div>
                            <button type="submit"
                                class="text-pink-500 hover:text-pink-700 disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0 p-2"
                                :disabled="!selectedChat || (!newMessage.trim() && !selectedFiles.length)">
                                <i class="fas fa-paper-plane text-lg"></i>
                            </button>
                        </form>
                        <!-- Selected Files Preview -->
                        <div v-if="selectedFiles.length > 0" class="mt-2 flex flex-wrap gap-2">
                            <div v-for="(file, index) in selectedFiles" :key="index"
                                class="relative bg-gray-100 rounded-lg p-2 flex items-center gap-2">
                                <i :class="getFileIcon(file.type)" class="text-gray-500"></i>
                                <span class="text-sm text-gray-700 truncate max-w-[150px]">{{ file.name }}</span>
                                <button @click="removeFile(index)" class="text-gray-500 hover:text-red-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- No Chat Selected -->
                <div v-else class="flex-1 flex items-center justify-center bg-gray-50">
                    <div class="text-center">
                        <i class="fas fa-comments text-4xl text-gray-400 mb-2"></i>
                        <h3 class="text-sm font-medium text-gray-900">Select a conversation</h3>
                        <p class="text-xs text-gray-500">Choose a chat to start messaging</p>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, nextTick, watch, onUnmounted, computed } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { formatDistanceToNow, format } from 'date-fns'
import Echo from 'laravel-echo'
import axios from 'axios'

const props = defineProps({
    user: { type: Object, required: true },
    initialChats: { type: Array, default: () => [] },
    initialSelectedUser: { type: Object, default: null }
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
const totalUnreadCount = ref(0)
const showNotifications = ref(false)
const notifications = ref([])
const showAttachmentMenu = ref(false)
const fileInput = ref(null)
const selectedFiles = ref([])
let searchTimeout = null
let chatUpdateInterval = null
let notificationInterval = null

const handleClickOutside = (event) => {
    const attachmentMenu = document.querySelector('.attachment-menu')
    if (showAttachmentMenu.value && attachmentMenu && !attachmentMenu.contains(event.target)) {
        showAttachmentMenu.value = false
    }
}

const formatDate = (date) => {
    const messageDate = new Date(date)
    const today = new Date()
    const yesterday = new Date(today)
    yesterday.setDate(yesterday.getDate() - 1)

    // If message is from today, show time only
    if (messageDate.toDateString() === today.toDateString()) {
        return format(messageDate, 'h:mm a') // e.g., "6:30 PM"
    }
    // If message is from yesterday, show "Yesterday" and time
    else if (messageDate.toDateString() === yesterday.toDateString()) {
        return `Yesterday ${format(messageDate, 'h:mm a')}`
    }
    // If message is from this year, show month, day and time
    else if (messageDate.getFullYear() === today.getFullYear()) {
        return format(messageDate, 'MMM d, h:mm a') // e.g., "Mar 15, 6:30 PM"
    }
    // If message is from previous years, show full date and time
    else {
        return format(messageDate, 'MMM d, yyyy h:mm a') // e.g., "Mar 15, 2023 6:30 PM"
    }
}

const selectChat = async (chat) => {
    try {
        if (!chat || !chat.id) {
            console.error('Invalid chat object:', chat)
            return
        }

        console.log('Selecting chat:', chat)

        // Set loading state and selected chat immediately
        messagesLoading.value = true
        selectedChat.value = chat
        messages.value = []

        try {
            // Fetch messages for the chat
            console.log('Fetching messages for chat:', chat.id)
            const response = await axios.get(route('messages.show', chat.id))
            console.log('Messages response:', response.data)

            if (response.data.messages) {
                messages.value = response.data.messages
            }

            // Mark messages as read
            await axios.post(route('messages.mark-chat-read', chat.id))

            // Update unread count in the chat list
            const chatIndex = chats.value.findIndex(c => c.id === chat.id)
            if (chatIndex !== -1) {
                chats.value[chatIndex].unread_count = 0
            }

            // Update URL without full page reload
            router.visit(route('messages.index', { user: chat.id }), {
                preserveState: true,
                preserveScroll: true,
                only: ['initialSelectedUser']
            })

            // Scroll to bottom after messages are loaded
            await nextTick()
            scrollToBottom()
        } catch (error) {
            console.error('Error fetching messages:', error)
            if (error.response) {
                console.error('Error response data:', error.response.data)
                console.error('Error response status:', error.response.status)
            }
            throw error // Re-throw to be caught by outer try-catch
        }
    } catch (error) {
        console.error('Error in selectChat:', error)
        console.error('Error details:', {
            message: error.message,
            response: error.response?.data,
            status: error.response?.status
        })
        // Show error message to user
        alert('Failed to load messages: ' + (error.response?.data?.error || error.message || 'Unknown error'))
    } finally {
        messagesLoading.value = false
    }
}

const sendMessage = async () => {
    if ((!newMessage.value.trim() && !selectedFiles.value.length) || !selectedChat.value) return

    const formData = new FormData()
    formData.append('content', newMessage.value)
    selectedFiles.value.forEach(file => {
        formData.append('attachments[]', file)
    })

    // Optimistically update UI first
    const tempMessage = {
        id: Date.now(),
        content: newMessage.value,
        sender_id: props.user.id,
        recipient_id: selectedChat.value.id,
        created_at: new Date().toISOString(),
        read_at: null,
        is_sent_by_me: true,
        attachments: selectedFiles.value.map(file => ({
            name: file.name,
            type: file.type,
            size: file.size
        }))
    }

    // Clear input and files immediately
    const messageContent = newMessage.value
    newMessage.value = ''
    selectedFiles.value = []

    // Reset textarea height
    const textarea = document.querySelector('textarea')
    if (textarea) {
        textarea.style.height = 'auto'
    }

    // Optimistically update messages
    messages.value.push(tempMessage)
    nextTick(() => scrollToBottom())

    // Optimistically update chat list
    const chatIndex = chats.value.findIndex(c => c.id === selectedChat.value.id)
    if (chatIndex !== -1) {
        const chat = chats.value[chatIndex]
        chat.last_message = {
            content: messageContent,
            created_at: tempMessage.created_at,
            sender_id: props.user.id,
            is_sent_by_me: true,
            read_at: null
        }
        // Move chat to top
        chats.value.splice(chatIndex, 1)
        chats.value.unshift(chat)
    }

    try {
        // Send message to server
        const response = await axios.post(route('messages.store', selectedChat.value.id), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

        const message = response.data.message

        // Update the temporary message with real data
        const messageIndex = messages.value.findIndex(m => m.id === tempMessage.id)
        if (messageIndex !== -1) {
            messages.value[messageIndex] = message
        }

        // Update chat list with real message data
        if (chatIndex !== -1) {
            const chat = chats.value[0] // Chat is now at the top
            chat.last_message = {
                content: message.content,
                created_at: message.created_at,
                sender_id: message.sender_id,
                is_sent_by_me: true,
                read_at: message.read_at
            }
        }
    } catch (error) {
        console.error('Error sending message:', error)
        // Remove failed message
        messages.value = messages.value.filter(m => m.id !== tempMessage.id)
        // Restore input
        newMessage.value = messageContent
        // Restore files
        selectedFiles.value = tempMessage.attachments
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

const openAttachmentMenu = (event) => {
    event.stopPropagation()
    showAttachmentMenu.value = !showAttachmentMenu.value
}

const triggerFileInput = (event) => {
    event.stopPropagation()
    fileInput.value.click()
    showAttachmentMenu.value = false
}

const handleFileSelect = (event) => {
    const files = Array.from(event.target.files)
    selectedFiles.value = [...selectedFiles.value, ...files]
    event.target.value = null // Reset input
}

const removeFile = (index) => {
    selectedFiles.value.splice(index, 1)
}

const getFileIcon = (type) => {
    if (!type) return 'fas fa-file'

    if (isImageFile(type)) return 'fas fa-image'
    if (isPdfFile(type)) return 'fas fa-file-pdf'
    if (isVideoFile(type)) return 'fas fa-file-video'
    if (isAudioFile(type)) return 'fas fa-file-audio'
    if (isTextFile(type)) return 'fas fa-file-alt'
    if (type.includes('word')) return 'fas fa-file-word'
    if (type.includes('excel') || type.includes('sheet')) return 'fas fa-file-excel'
    if (type.includes('powerpoint') || type.includes('presentation')) return 'fas fa-file-powerpoint'
    if (type.includes('zip') || type.includes('archive')) return 'fas fa-file-archive'

    return 'fas fa-file'
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
    try {
        // Close the modal first
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

        // Create new chat object
        const newChat = {
            id: user.id,
            name: user.name,
            profile_photo_path: user.profile_photo_path,
            last_message: null,
            unread_count: 0,
            is_online: false,
            last_seen: 'Offline'
        }

        // Add new chat to the list
        chats.value.unshift(newChat)

        // Select the new chat
        await selectChat(newChat)

        // Update URL without full page reload
        router.visit(route('messages.index', { user: user.id }), {
            preserveState: true,
            preserveScroll: true,
            only: ['initialSelectedUser']
        })
    } catch (error) {
        console.error('Error starting new chat:', error)
        searchError.value = 'Failed to start new chat. Please try again.'
        // Show error in UI
        showNewChatModal.value = true
    }
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

// Add this after the messages ref declaration
watch(messages, (newMessages) => {
    console.log('Messages updated:', newMessages)
}, { deep: true })

// Function to fetch latest chats with proper sorting
const fetchLatestChats = async () => {
    try {
        const response = await axios.get(route('messages.latest-chats'), {
            params: {
                last_chat_id: chats.value[0]?.id || 0,
                limit: 20,
                cache: true,
                sort_by: 'last_message' // Sort by last message time
            }
        })

        if (response.data.chats) {
            // Store the current selected chat ID and its data
            const currentSelectedId = selectedChat.value?.id
            const currentSelectedData = selectedChat.value

            // Sort chats by last message time
            const sortedChats = response.data.chats.sort((a, b) => {
                const timeA = a.last_message?.created_at || 0
                const timeB = b.last_message?.created_at || 0
                return new Date(timeB) - new Date(timeA)
            })

            // Only update chats that have changed
            const updatedChats = sortedChats.map(newChat => {
                const existingChat = chats.value.find(c => c.id === newChat.id)
                if (existingChat && JSON.stringify(existingChat) === JSON.stringify(newChat)) {
                    return existingChat // Keep existing chat if no changes
                }
                return newChat // Use new chat data if changed
            })

            // Update chats while preserving order
            chats.value = updatedChats
            totalUnreadCount.value = response.data.total_unread || 0

            // Restore the selected chat if it exists
            if (currentSelectedId) {
                const existingChat = chats.value.find(c => c.id === currentSelectedId)
                if (existingChat) {
                    selectedChat.value = existingChat
                } else if (currentSelectedData) {
                    // If chat was removed, keep the current view
                    selectedChat.value = currentSelectedData
                }
            }
        }
    } catch (error) {
        console.error('Error fetching latest chats:', error)
    }
}

// Optimized notification fetching with caching and pagination
const fetchNotifications = async () => {
    try {
        const response = await axios.get(route('messages.notifications'), {
            params: {
                last_notification_id: notifications.value[0]?.id || 0,
                limit: 20,
                cache: true,
                include_read: false // Only fetch unread notifications
            }
        })

        if (response.data.messages) {
            // Use Set for faster duplicate checking
            const existingIds = new Set(notifications.value.map(n => n.id))

            // Only add new notifications
            const newNotifications = response.data.messages.filter(
                newNotif => !existingIds.has(newNotif.id)
            )

            if (newNotifications.length > 0) {
                notifications.value = [...newNotifications, ...notifications.value]
            }
        }
        totalUnreadCount.value = response.data.total_unread
    } catch (error) {
        console.error('Error fetching notifications:', error)
    }
}

// Optimized toggle notifications
const toggleNotifications = async () => {
    showNotifications.value = !showNotifications.value
    if (showNotifications.value && notifications.value.length === 0) {
        await fetchNotifications()
    }
}

// Watch for route changes with optimized chat selection
watch(() => props.initialSelectedUser, (newUser) => {
    console.log('Initial selected user changed:', newUser) // Debug log
    if (newUser) {
        const chat = chats.value.find(c => c.id === newUser.id)
        if (chat) {
            selectChat(chat)
        } else {
            // Create new chat object with minimal required data
            const newChat = {
                id: newUser.id,
                name: newUser.name,
                profile_photo_path: newUser.profile_photo_path,
                last_message: null,
                unread_count: 0,
                is_online: false,
                last_seen: 'Offline'
            }
            chats.value.unshift(newChat)
            selectChat(newChat)
        }
    }
}, { immediate: true })

// Update onMounted
onMounted(() => {
    // Initialize Laravel Echo
    const echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
        wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
        wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
        wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    })

    // Start polling for chat updates every 30 seconds (reduced frequency)
    chatUpdateInterval = setInterval(fetchLatestChats, 30000)

    // Listen for user status changes
    echo.private('user-status')
        .listen('UserStatusChanged', (e) => {
            const chatIndex = chats.value.findIndex(c => c.id === e.user_id)
            if (chatIndex !== -1) {
                chats.value[chatIndex].is_online = e.is_online
                chats.value[chatIndex].last_seen = e.last_seen
            }
        })

    // Listen for new messages with proper sorting
    echo.private(`messages.${props.user.id}`)
        .listen('NewMessage', (e) => {
            const message = e.message

            // If message is for current chat, add it to messages
            if (selectedChat.value &&
                (message.sender_id === selectedChat.value.id ||
                    message.recipient_id === selectedChat.value.id)) {
                messages.value.push(message)
                nextTick(() => scrollToBottom())
            }

            // Update chat list immediately with proper sorting
            const chatIndex = chats.value.findIndex(c =>
                c.id === message.sender_id || c.id === message.recipient_id
            )

            if (chatIndex !== -1) {
                // Update existing chat
                const chat = chats.value[chatIndex]
                chat.last_message = {
                    content: message.content,
                    created_at: message.created_at,
                    sender_id: message.sender_id,
                    is_sent_by_me: message.sender_id === props.user.id,
                    read_at: message.read_at
                }
                chat.unread_count = message.unread_count
                totalUnreadCount.value = (totalUnreadCount.value || 0) + 1

                // Remove chat from current position
                chats.value.splice(chatIndex, 1)

                // Find the correct position to insert based on last_message time
                const insertIndex = chats.value.findIndex(c => {
                    const currentTime = new Date(c.last_message?.created_at || 0)
                    const newTime = new Date(message.created_at)
                    return currentTime < newTime
                })

                // Insert at the correct position or at the beginning if no position found
                if (insertIndex === -1) {
                    chats.value.unshift(chat)
                } else {
                    chats.value.splice(insertIndex, 0, chat)
                }
            } else {
                // Add new chat at the beginning
                const newChat = {
                    id: message.sender_id === props.user.id ? message.recipient_id : message.sender_id,
                    name: message.sender_id === props.user.id ? message.recipient.name : message.sender.name,
                    profile_photo_path: message.sender_id === props.user.id ? message.recipient.profile_photo_path : message.sender.profile_photo_path,
                    last_message: {
                        content: message.content,
                        created_at: message.created_at,
                        sender_id: message.sender_id,
                        is_sent_by_me: message.sender_id === props.user.id,
                        read_at: message.read_at
                    },
                    unread_count: message.unread_count
                }
                chats.value.unshift(newChat)
                totalUnreadCount.value = (totalUnreadCount.value || 0) + 1
            }
        })

    // Listen for general notifications
    echo.private(`notifications.${props.user.id}`)
        .listen('NewNotification', (e) => {
            const notification = e.notification
            notifications.value.unshift(notification)
            totalUnreadCount.value = (totalUnreadCount.value || 0) + 1
        })

    // Initialize loading state
    loading.value = false

    // Fetch initial notifications
    fetchNotifications()

    // Add click outside handler for attachment menu
    document.addEventListener('click', handleClickOutside)
})

// Update onUnmounted
onUnmounted(() => {
    if (chatUpdateInterval) {
        clearInterval(chatUpdateInterval)
    }
    // Remove click outside handler
    document.removeEventListener('click', handleClickOutside)
})

// Add this new function for auto-resizing textarea
const autoResize = (event) => {
    const textarea = event.target
    textarea.style.height = 'auto'
    const newHeight = Math.min(textarea.scrollHeight, 100) // Max height of 100px
    textarea.style.height = newHeight + 'px'
}

// Add a watch for selectedChat to ensure it persists
watch(selectedChat, (newChat) => {
    console.log('Selected chat changed:', newChat) // Debug log
    if (newChat) {
        // Ensure the chat exists in the chat list
        const chatExists = chats.value.some(chat => chat.id === newChat.id)
        if (!chatExists) {
            chats.value.unshift(newChat)
        }
    }
}, { deep: true })

// Add this new function in the script section
const handleEnterKey = (event) => {
    if (event.ctrlKey) {
        // Allow new line with Ctrl+Enter
        return
    }

    if (newMessage.value.trim()) {
        sendMessage()
    }
}

// Add this computed property for grouped messages
const groupedMessages = computed(() => {
    const groups = {}
    messages.value.forEach(message => {
        const date = new Date(message.created_at)
        const today = new Date()
        const yesterday = new Date(today)
        yesterday.setDate(yesterday.getDate() - 1)

        let groupKey
        if (date.toDateString() === today.toDateString()) {
            groupKey = 'Today'
        } else if (date.toDateString() === yesterday.toDateString()) {
            groupKey = 'Yesterday'
        } else if (date.getFullYear() === today.getFullYear()) {
            groupKey = format(date, 'MMMM d')
        } else {
            groupKey = format(date, 'MMMM d, yyyy')
        }

        if (!groups[groupKey]) {
            groups[groupKey] = []
        }
        groups[groupKey].push(message)
    })
    return groups
})

// Add these functions to handle file previews
const isImageFile = (type) => {
    return type && type.startsWith('image/')
}

const isPdfFile = (type) => {
    return type && type.includes('pdf')
}

const isVideoFile = (type) => {
    return type && type.startsWith('video/')
}

const isAudioFile = (type) => {
    return type && type.startsWith('audio/')
}

const isTextFile = (type) => {
    return type && (type.includes('text/') || type.includes('application/json') || type.includes('application/javascript'))
}

const getFilePreview = (attachment) => {
    if (!attachment?.type) return null

    if (isImageFile(attachment.type)) {
        return {
            type: 'image',
            component: 'img',
            props: {
                src: attachment.url,
                alt: attachment.name || 'Image attachment',
                class: 'max-w-full h-auto rounded-lg cursor-pointer hover:opacity-90 transition-opacity',
                style: 'max-height: 200px; object-fit: contain;'
            }
        }
    }

    if (isPdfFile(attachment.type)) {
        return {
            type: 'pdf',
            component: 'iframe',
            props: {
                src: attachment.url,
                class: 'w-full h-64 rounded-lg border-0',
                style: 'max-height: 400px;'
            }
        }
    }

    if (isVideoFile(attachment.type)) {
        return {
            type: 'video',
            component: 'video',
            props: {
                src: attachment.url,
                controls: true,
                class: 'max-w-full rounded-lg',
                style: 'max-height: 300px;'
            }
        }
    }

    if (isAudioFile(attachment.type)) {
        return {
            type: 'audio',
            component: 'audio',
            props: {
                src: attachment.url,
                controls: true,
                class: 'w-full'
            }
        }
    }

    if (isTextFile(attachment.type)) {
        return {
            type: 'text',
            component: 'div',
            props: {
                class: 'bg-white bg-opacity-10 p-2 rounded-lg max-h-40 overflow-y-auto'
            }
        }
    }

    return null
}

const formatFileSize = (bytes) => {
    if (!bytes) return '0 B'
    const k = 1024
    const sizes = ['B', 'KB', 'MB', 'GB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const onFileLoad = (event) => {
    event.target.classList.add('loaded')
}
</script>

<style scoped>
/* Existing styles */
.overflow-y-auto::-webkit-scrollbar {
    width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 2px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #555;
}

textarea {
    line-height: 1.4;
    overflow-y: auto;
}

textarea:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(236, 72, 153, 0.2);
}

/* Message bubble styles */
.message-bubble {
    word-break: break-word;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.message-bubble.justify-end {
    align-items: flex-end;
}

.overflow-wrap-anywhere {
    overflow-wrap: anywhere;
    word-break: break-word;
}

/* Image styles */
.message-bubble img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    transition: transform 0.2s ease-in-out;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    opacity: 0;
}

.message-bubble img:hover {
    transform: scale(1.02);
}

.message-bubble img.loaded {
    opacity: 1;
    transition: opacity 0.3s ease-in-out;
}

/* Ensure proper spacing for image attachments */
.message-bubble .mt-2 {
    margin-top: 0.5rem;
}

/* Ensure proper text truncation */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.break-all {
    word-break: break-all;
    overflow-wrap: break-word;
}

/* Improve chat list item spacing */
.chat-list-item {
    padding: 0.5rem;
    transition: background-color 0.2s;
}

.chat-list-item:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

/* Ensure proper alignment of status indicators */
.status-indicators {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Add these styles */
.message-bubble iframe,
.message-bubble video,
.message-bubble audio {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.message-bubble iframe.loaded,
.message-bubble video.loaded,
.message-bubble audio.loaded {
    opacity: 1;
}

/* Ensure proper spacing for file previews */
.message-bubble .mt-2 {
    margin-top: 0.5rem;
}

/* Add a subtle shadow to previews */
.message-bubble img,
.message-bubble iframe,
.message-bubble video {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Ensure proper alignment of file info */
.file-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0;
}

/* Add hover effect for file links */
.file-link:hover {
    text-decoration: underline;
}

/* Style for text file previews */
.text-preview {
    font-family: monospace;
    white-space: pre-wrap;
    word-break: break-word;
    background-color: rgba(255, 255, 255, 0.1);
    padding: 0.5rem;
    border-radius: 0.25rem;
    max-height: 200px;
    overflow-y: auto;
}
</style>