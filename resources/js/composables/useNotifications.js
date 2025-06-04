import { ref, onMounted, onUnmounted } from 'vue'
import Echo from 'laravel-echo'
import { router } from '@inertiajs/vue3'

export function useNotifications(userId) {
    const notifications = ref([])
    const unreadCount = ref(0)
    const loading = ref(false)
    const error = ref(null)
    let echo = null

    const fetchNotifications = async () => {
        loading.value = true
        error.value = null
        try {
            const response = await fetch('/notifications', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }

            const data = await response.json()
            notifications.value = data.notifications
            unreadCount.value = data.unread_count
        } catch (err) {
            console.error('Error fetching notifications:', err)
            error.value = err.message
        } finally {
            loading.value = false
        }
    }

    const markAsRead = async (id) => {
        try {
            const response = await fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }

            // Update local state
            const notification = notifications.value.find(n => n.id === id)
            if (notification && !notification.read_at) {
                notification.read_at = new Date().toISOString()
                unreadCount.value = Math.max(0, unreadCount.value - 1)
            }
        } catch (err) {
            console.error('Error marking notification as read:', err)
        }
    }

    const markAllAsRead = async () => {
        try {
            const response = await fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }

            // Update local state
            notifications.value.forEach(n => n.read_at = new Date().toISOString())
            unreadCount.value = 0
        } catch (err) {
            console.error('Error marking all notifications as read:', err)
        }
    }

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

        // Listen for notifications
        echo.private(`notifications.${userId}`)
            .listen('NewNotification', (data) => {
                notifications.value.unshift(data.notification)
                unreadCount.value++
            })

        // Initial fetch
        fetchNotifications()

        // Fetch notifications every 30 seconds as backup
        const interval = setInterval(fetchNotifications, 30000)

        // Cleanup
        onUnmounted(() => {
            clearInterval(interval)
            if (echo) {
                echo.leave(`notifications.${userId}`)
            }
        })
    })

    return {
        notifications,
        unreadCount,
        loading,
        error,
        fetchNotifications,
        markAsRead,
        markAllAsRead
    }
} 