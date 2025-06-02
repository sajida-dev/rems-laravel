<template>
    <div class="flex h-screen bg-gray-100">
        <Sidebar :open="sidebarOpen" @close="sidebarOpen = false" :user="user" :pendingCount="pendingCount" />
        <div v-if="sidebarOpen" class="fixed inset-0 bg-black bg-opacity-40 z-10 lg:hidden"
            @click="sidebarOpen = false"></div>

        <div class="flex-1 flex flex-col">
            <Header @toggle-sidebar="sidebarOpen = !sidebarOpen" :user="user" />

            <main class="flex-1 overflow-y-auto p-6">
                <slot />
            </main>
            <Footer />
        </div>

    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import { ref, inject, onMounted, provide } from 'vue';
import Sidebar from '@Components/Dashboard/Common/Sidebar.vue'
import Footer from '@Components/Dashboard/Common/Footer.vue'
import Header from '@Components/Dashboard/Common/Header.vue'
import axios from 'axios'
import { Link } from '@inertiajs/vue3'
import { format } from 'date-fns'

const { props } = usePage()
const user = props.auth?.user
const sidebarOpen = ref(false)
const pendingCount = ref(props.pendingCount || 0)
const unreadCount = ref(0)
const showMessageNotifications = ref(false)
const messageNotifications = ref({
    messages: [],
    total_unread: 0
})

const updatePendingCount = (newCount) => {
    pendingCount.value = newCount;
}

provide('updatePendingCount', updatePendingCount);

onMounted(() => {
    if (user.role === 'admin') {
        fetchPendingCount()
    }
    fetchMessageNotifications()
    // Set up polling for message notifications
    setInterval(fetchMessageNotifications, 10000) // Poll every 30 seconds
})

onMounted(async () => {
    try {
        const response = await axios.get(route('messages.notifications'))
        unreadCount.value = response.data.messages.total
        messageNotifications.value = response.data
    } catch (error) {
        console.error('Error fetching unread messages:', error)
    }
})

function fetchPendingCount() {
    axios.get('/agents/pending-count')
        .then(response => {
            pendingCount.value = response.data.count
        })
        .catch(error => {
            console.error('Failed to fetch pending count:', error)
        })
}

const formatDate = (date) => {
    const messageDate = new Date(date)
    const today = new Date()
    const yesterday = new Date(today)
    yesterday.setDate(yesterday.getDate() - 1)

    if (messageDate.toDateString() === today.toDateString()) {
        return format(messageDate, 'h:mm a')
    } else if (messageDate.toDateString() === yesterday.toDateString()) {
        return `Yesterday ${format(messageDate, 'h:mm a')}`
    } else if (messageDate.getFullYear() === today.getFullYear()) {
        return format(messageDate, 'MMM d, h:mm a')
    } else {
        return format(messageDate, 'MMM d, yyyy h:mm a')
    }
}

const fetchMessageNotifications = async () => {
    try {
        const response = await axios.get(route('messages.notifications'))
        messageNotifications.value = response.data
    } catch (error) {
        console.error('Error fetching message notifications:', error)
    }
}

const header = inject('layoutHeader', {
    title: 'Dashbaord',
    mainPage: 'Pages',
    page: ''
});

</script>
