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

            <div class="text-gray-400 text-xs uppercase mt-6 mb-2">Management</div>

            <Link href="/categories" class="flex items-center p-2 rounded hover:bg-gray-800">
            <i class="fas fa-th-large w-5"></i><span class="ml-3">Categories</span>
            </Link>

            <!-- Amenities -->
            <Link href="/amenities" class="flex items-center p-2 rounded hover:bg-gray-800">
            <i class="fas fa-cogs w-5"></i><span class="ml-3">Amenities</span>
            </Link>

            <!-- End-users -->
            <Link href="/end-users" class="flex items-center p-2 rounded hover:bg-gray-800">
            <i class="fas fa-users w-5"></i><span class="ml-3">End-users</span>
            </Link>

            <!-- Agents -->
            <Link href="/agents" class="flex items-center p-2 rounded hover:bg-gray-800">
            <i class="fas fa-user-tie w-5"></i><span class="ml-3">Agents</span>
            </Link>

            <!-- Properties -->
            <Link href="/properties" class="flex items-center p-2 rounded hover:bg-gray-800">
            <i class="fas fa-building w-5"></i><span class="ml-3">Properties</span>
            </Link>
        </nav>

        <div class="absolute bottom-0 w-full p-4 border-t border-gray-800">
            <div class="flex items-center space-x-3">
                <img :src="'/backend/assets/img/profile.jpg'" alt="User Avatar"
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

                    <Link href="/profile" class="flex items-center p-2 rounded hover:bg-gray-800">
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
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import ApplicationLogo from './ApplicationLogo.vue'

defineProps(['open', 'user'])

defineEmits(['close'])

const userMenuOpen = ref(false)

function toggleUserMenu() {
    userMenuOpen.value = !userMenuOpen.value
}
</script>

<style scoped>
/* Fade transition for user menu links */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>