<template>
    <ul class="dropdown-menu dropdown-user animated fadeIn">
        <div class="dropdown-user-scroll scrollbar-outer">
            <li>
                <div class="user-box flex items-center p-4">
                    <div class="avatar-lg mr-4">
                        <img :src="user.profile_photo_url" alt="Profile Image"
                            class="avatar-img rounded-full w-16 h-16 object-cover" />
                    </div>
                    <div class="u-text">
                        <h4 class="text-base font-semibold">{{ user.name }}</h4>
                        <p class="text-muted text-sm">{{ user.email }}</p>
                        <Link :href="route('profile.show')" class="btn btn-xs btn-secondary btn-sm mt-2">
                        View Profile
                        </Link>
                    </div>
                </div>
            </li>
            <li>
                <div class="dropdown-divider border-t my-2"></div>
                <Link class="dropdown-item block px-4 py-2" :href="route('profile.show')">
                My Profile
                </Link>
                <form @submit.prevent="logout">
                    <button type="submit" class="dropdown-item block px-4 py-2 text-left w-full">
                        Logout
                    </button>
                </form>
            </li>
        </div>
    </ul>
</template>

<script setup>
import { usePage, router, Link } from '@inertiajs/vue3'

const { props } = usePage()
const user = props.auth.user

function logout() {
    router.post(route('logout'))
}
</script>
