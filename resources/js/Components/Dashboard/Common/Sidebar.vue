<template>
    <aside
        class="bg-gray-900 text-white w-64 fixed inset-y-0 left-0 z-30 transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 flex flex-col h-full"
        :class="{ '-translate-x-full': !open }">

        <div class="flex items-center justify-between p-4 border-b border-gray-800">
            <ApplicationLogo />
            <button class="lg:hidden" @click="$emit('close')">
                <i class="fas fa-times text-white text-lg"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto">
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
                    <span v-if="item?.badgeCount > 0"
                        class="ml-2 bg-red-600 text-white text-xs rounded-full px-2 py-0.5">
                        {{ item?.badgeCount }}
                    </span>
                    </Link>
                </template>
            </nav>
        </div>

        <div class="border-t border-gray-800 bg-gray-900">
            <div class="p-4">
                <div class="flex items-center space-x-3">
                    <img :src="'/storage' + '/' + user.profile_photo_path" alt="User Avatar"
                        class="h-12 w-12 rounded-full object-cover cursor-pointer" @click="toggleUserMenu" />
                    <div>
                        <p class="text-sm font-semibold text-white">{{ user.name }}</p>
                        <p class="text-xs text-gray-400">{{ user.role || 'User' }}</p>
                    </div>
                </div>

                <transition name="fade">
                    <div v-if="userMenuOpen" class="mt-4 bg-gray-900 space-y-2 max-h-[200px] overflow-y-auto">
                        <Link href="/settings" class="flex items-center p-2 rounded hover:bg-gray-800">
                        <i class="fas fa-cogs w-5"></i><span class="ml-3">Settings</span>
                        </Link>
                        <Link href="/user/profile" class="flex items-center p-2 rounded hover:bg-gray-800">
                        <i class="fas fa-user w-5"></i><span class="ml-3">Profile</span>
                        </Link>
                        <Link :href="route('logout')" method="post"
                            class="flex items-center p-2 rounded hover:bg-gray-800">
                        <i class="fas fa-sign-out-alt w-5"></i><span class="ml-3">Logout</span>
                        </Link>
                    </div>
                </transition>
            </div>
        </div>
    </aside>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import ApplicationLogo from './ApplicationLogo.vue'


const { open, user, pendingCount } = defineProps(['open', 'user', 'pendingCount'])
defineEmits(['close'])
const userMenuOpen = ref(false)

function toggleUserMenu() {
    userMenuOpen.value = !userMenuOpen.value
}


const menu = computed(() => {
    const commonItems = [
        { title: 'Profile', href: route('profile.show'), icon: 'fas fa-user' },
        { title: 'Hiring Requests', href: route('hiring-requests.index'), icon: 'fas fa-briefcase' },
        { title: 'Applications', href: route('application.index'), icon: 'fas fa-file-signature' },
        { title: 'Payments', href: route('payment.index'), icon: 'fas fa-credit-card' },
    ];
    if (user.role === 'admin') {
        return [
            {
                label: 'Management',
                items: [
                    ...commonItems,
                    { title: 'Categories', href: route('categories.index'), icon: 'fas fa-th-large' },
                    { title: 'Amenities', href: route('amenities.index'), icon: 'fas fa-cogs' },
                    { title: 'End-users', href: route('end-users.index'), icon: 'fas fa-users' },
                    {
                        title: 'Agents', href: route('agents.index'), icon: 'fas fa-user-tie',
                        showPendingCount: true, badgeCount: pendingCount
                    },
                    { title: 'Properties', href: route('properties.index'), icon: 'fas fa-building' },
                ],
            },
            {
                label: 'Bookmarks',
                items: [
                    { title: 'All Bookmarks', href: route('bookmarks.admin.index'), icon: 'fas fa-bookmark' },
                ],
            },
        ];
    } else if (user.role === 'agent') {
        return [
            {
                label: 'Agent Panel',
                items: [
                    { title: 'My Properties', href: route('properties.index'), icon: 'fas fa-building' },
                    ...commonItems,
                    { title: 'Messages', href: route('messages.index'), icon: 'fas fa-envelope' },
                ],
            },
            {
                label: 'Bookmarks',
                items: [
                    { title: 'Property Bookmarks', href: route('bookmarks.admin.index'), icon: 'fas fa-bookmark' },
                ],
            },
        ];
    } else {
        return [
            {
                label: 'User Panel',
                items: [
                    ...commonItems,
                    { title: 'My Bookmarks', href: route('bookmarks.index'), icon: 'fas fa-heart' },
                    { title: 'Messages', href: route('messages.index'), icon: 'fas fa-envelope' },
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

/* Custom scrollbar styles */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #1f2937;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #4b5563;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}

/* Ensure the sidebar takes full height on mobile */
@media (max-width: 1024px) {
    aside {
        height: 100vh;
    }
}
</style>
