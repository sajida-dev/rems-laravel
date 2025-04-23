<template>
    <transition name="fade">
        <ul v-if="messages.length !== null"
            class="dropdown-menu messages-notif-box w-80 bg-white rounded-md shadow-lg z-50"
            @click.away="$emit('close')">
            <li class="px-4 py-2 border-b font-semibold text-gray-700 flex justify-between">
                Messages
                <button class="text-xs text-blue-600 hover:underline">Mark all as read</button>
            </li>
            <li class="max-h-72 overflow-y-auto">
                <div class="divide-y">
                    <div v-for="(msg, i) in messages" :key="i" class="flex px-4 py-3 hover:bg-gray-100">
                        <img :src="msg.img" alt="Profile" class="w-10 h-10 rounded-full mr-3 object-cover" />
                        <div class="text-sm">
                            <span class="font-semibold">{{ msg.subject }}</span>
                            <div class="text-gray-600">{{ msg.message }}</div>
                            <div class="text-xs text-gray-400">{{ msg.time }}</div>
                        </div>
                    </div>
                    <div v-if="messages.length === 0" class="px-4 py-2 text-gray-500">
                        No messages
                    </div>
                </div>
            </li>
            <li class="text-center border-t py-2">
                <button class="text-sm text-blue-600 hover:underline">
                    See all messages <i class="fa fa-angle-right ml-1"></i>
                </button>
            </li>
        </ul>
    </transition>
</template>

<script setup>
const props = defineProps({
    messages: {
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
