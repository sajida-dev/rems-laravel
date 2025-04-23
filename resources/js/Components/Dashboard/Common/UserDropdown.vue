<template>
    <transition name="fade">
        <ul v-if="true"
            class="dropdown-menu dropdown-user min-w-fit w-72 overflow-x-scroll bg-white rounded shadow-lg z-50"
            @click.away="$emit('close')">
            <li class="p-4 flex items-center">
                <img :src="user.profile_photo_url" alt="Profile Image"
                    class="w-12 h-12 rounded-full object-cover mr-3" />
                <div>
                    <h4 class="font-semibold">{{ user.name }}</h4>
                    <p class="text-sm text-gray-500">{{ user.email }}</p>
                    <Link :href="route('profile.show')" class="text-xs text-blue-600 hover:underline">
                    View Profile
                    </Link>
                </div>
            </li>
            <li>
                <hr />
            </li>
            <li>
                <Link :href="route('profile.show')" class="block px-4 py-2 hover:bg-gray-100">
                My Profile
                </Link>
            </li>
            <li>
                <button @click="logout" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                    Logout
                </button>
            </li>
        </ul>
    </transition>
</template>

<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'

const { props } = usePage()
const user = props.auth.user

function logout() {
    router.post(route('logout'))
}
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
