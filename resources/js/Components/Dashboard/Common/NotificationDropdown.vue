<template>
    <transition name="fade">
        <div v-if="notifications.length !== null" class="dropdown-menu w-80 bg-white rounded shadow-lg z-50"
            @click.away="$emit('close')">
            <div class="border-b px-4 py-2 font-semibold text-gray-800">
                You have {{ notifications.length }} new notification
                <span v-if="notifications.length !== 1">s</span>
            </div>
            <div class="max-h-64 overflow-y-auto">
                <div v-for="(n, i) in notifications" :key="i"
                    class="flex items-start gap-3 px-4 py-3 hover:bg-gray-100">
                    <div v-if="n.icon" :class="['text-white rounded p-1', n.bg]">
                        <i :class="['fa', n.icon]"></i>
                    </div>
                    <img v-else-if="n.image" :src="n.image" alt="profile" class="w-8 h-8 rounded-full object-cover" />
                    <div class="flex-1 text-sm">
                        <p class="text-gray-700">{{ n.text }}</p>
                        <p class="text-xs text-gray-500">{{ n.time }}</p>
                    </div>
                </div>
                <div v-if="notifications.length === 0" class="px-4 py-2 text-gray-500">
                    No notifications
                </div>
            </div>
            <div class="border-t text-center px-4 py-2">
                <a href="#" class="text-sm text-blue-600 hover:underline">
                    See all notifications <i class="fa fa-angle-right ml-1"></i>
                </a>
            </div>
        </div>
    </transition>
</template>

<script setup>
const props = defineProps({
    notifications: {
        type: Array,
        default: () => []
    }
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
