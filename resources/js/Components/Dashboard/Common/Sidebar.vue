<template>
    <aside
        class="bg-gray-900 text-white w-64 fixed inset-y-0 left-0 z-30 transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0"
        :class="{ '-translate-x-full': !open }">

        <div class="flex items-center justify-between p-4 border-b border-gray-800">
            <ApplicationLogo />
            <button class="lg:hidden" @click="$emit('close')">
                <i class="fas fa-times text-white text-lg"></i>
            </button>
        </div>

        <nav class="mt-4 px-4">
            <Link href="/dashboard" class="flex items-center p-2 rounded hover:bg-gray-800">
            <i class="fas fa-home w-5"></i><span class="ml-3">Dashboard</span>
            </Link>

            <template v-for="(section, index) in menu" :key="index">
                <div v-if="section.label" class="text-gray-400 text-xs uppercase mt-6 mb-2">
                    {{ section.label }}
                </div>
                <Link v-for="item in section.items" :key="item.title" :href="item.href"
                    class="flex items-center p-2 rounded hover:bg-gray-800">
                <i :class="`${item.icon} w-5`"></i><span class="ml-3">{{ item.title }}</span>
                </Link>
            </template>
        </nav>

        <div class="absolute bottom-0 w-full p-4 border-t border-gray-800">
            <div class="flex items-center space-x-3">
                <img :src="'/storage' + '/' + user.profile_photo_path" alt="User Avatar"
                    class="h-12 w-12 rounded-full object-cover cursor-pointer" @click="toggleUserMenu" />
                <div>
                    <p class="text-sm font-semibold text-white">{{ user.name }}</p>
                    <p class="text-xs text-gray-400">{{ user.role || 'User' }}</p>
                </div>
            </div>

            <transition name="fade">
                <div v-if="userMenuOpen" class="mt-4 space-y-2">
                    <Link href="/settings" class="flex items-center p-2 rounded hover:bg-gray-800">
                    <i class="fas fa-cogs w-5"></i><span class="ml-3">Settings</span>
                    </Link>
                    <Link href="/user/profile" class="flex items-center p-2 rounded hover:bg-gray-800">
                    <i class="fas fa-user w-5"></i><span class="ml-3">Profile</span>
                    </Link>
                    <Link href="/logout" class="flex items-center p-2 rounded hover:bg-gray-800">
                    <i class="fas fa-sign-out-alt w-5"></i><span class="ml-3">Logout</span>
                    </Link>
                </div>
            </transition>
        </div>
    </aside>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import ApplicationLogo from './ApplicationLogo.vue'

const { user } = defineProps(['open', 'user'])
defineEmits(['close'])

const userMenuOpen = ref(false)
function toggleUserMenu() {
    userMenuOpen.value = !userMenuOpen.value
}

// Role-based sidebar menu
const menu = computed(() => {
    const commonItems = [
        { title: 'Profile', href: '/user/profile', icon: 'fas fa-user' },
        { title: 'Messages', href: '/messages', icon: 'fas fa-envelope' },
    ];

    if (user.role === 'admin') {
        return [
            {
                label: 'Management',
                items: [
                    { title: 'Categories', href: '/categories', icon: 'fas fa-th-large' },
                    { title: 'Amenities', href: '/amenities', icon: 'fas fa-cogs' },
                    { title: 'End-users', href: '/end-users', icon: 'fas fa-users' },
                    { title: 'Agents', href: '/agents', icon: 'fas fa-user-tie' },
                    { title: 'Properties', href: '/properties', icon: 'fas fa-building' },
                ],
            },
        ];
    } else if (user.role === 'agent') {
        return [
            {
                label: 'Agent Panel',
                items: [
                    { title: 'My Properties', href: '/properties', icon: 'fas fa-building' },
                    ...commonItems,
                    { title: 'Hiring Requests', href: '/hiring-requests', icon: 'fas fa-briefcase' },
                    { title: 'Payments', href: '/payments', icon: 'fas fa-credit-card' },

                ],
            },
        ];
    } else {
        return [
            {
                label: 'User Panel',
                items: [
                    ...commonItems,
                    { title: 'Hire an Agent', href: '/hire-agent', icon: 'fas fa-handshake' },
                    { title: 'Payments', href: '/payments', icon: 'fas fa-credit-card' },

                ],
            },
        ];
    }
});
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
