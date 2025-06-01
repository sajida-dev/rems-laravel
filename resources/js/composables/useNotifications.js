import { ref, onMounted, onUnmounted } from 'vue'
import Echo from 'laravel-echo'

export function useNotifications(userId) {
    const notifications = ref([])
    const unreadCount = ref(0)
    let echo = null

    const fetchNotifications = async () => {
        try {
            const response = await fetch('/notifications')
            const data = await response.json()
            notifications.value = data.notifications
            unreadCount.value = data.unread_count
        } catch (error) {
            console.error('Error fetching notifications:', error)
        }
    }

    const markAsRead = async (id) => {
        try {
            await fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
            await fetchNotifications()
        } catch (error) {
            console.error('Error marking notification as read:', error)
        }
    }

    const markAllAsRead = async () => {
        try {
            await fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
            await fetchNotifications()
        } catch (error) {
            console.error('Error marking all notifications as read:', error)
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
            .listen('.NewNotification', (data) => {
                notifications.value.unshift(data.notification)
                unreadCount.value++
            })

        // Initial fetch
        fetchNotifications()

        // Fetch notifications every 30 seconds
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
        markAsRead,
        markAllAsRead,
        fetchNotifications
    }
} 