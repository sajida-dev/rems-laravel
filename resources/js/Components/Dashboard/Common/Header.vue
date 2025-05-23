<template>
    <header class="bg-white shadow px-4 py-4 flex lg:justify-end justify-between items-center">
        <button class="lg:hidden text-gray-800 text-2xl" @click="$emit('toggle-sidebar')">
            <i class="fas fa-bars"></i>
        </button>

        <div class="flex items-center space-x-4 sm:space-x-6">
            <div ref="emailRef" class="relative">
                <button @click.stop="toggle('email')" class="focus:outline-none relative">
                    <i class="fa fa-envelope text-xl"></i>
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
                    <span v-if="notifications.length"
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-semibold px-1.5 py-0.5 rounded-full">
                        {{ notifications.length }}
                    </span>
                </button>

                <transition name="fade">
                    <div v-if="open === 'notification'" class="dropdown-wrapper">
                        <NotificationDropdown :notifications="notifications" @close="open = null" />
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
import MessageDropdown from './MessageDropdown.vue'
import NotificationDropdown from './NotificationDropdown.vue'
import UserDropdown from './UserDropdown.vue'

const props = defineProps({
    user: { type: Object, required: true },
    messages: { type: Array, default: () => [] },
    notifications: { type: Array, default: () => [] },
})

const open = ref(null)
const emailRef = ref(null)
const notifRef = ref(null)
const userRef = ref(null)

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

onMounted(() => document.addEventListener('click', onClickOutside))
onUnmounted(() => document.removeEventListener('click', onClickOutside))
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
