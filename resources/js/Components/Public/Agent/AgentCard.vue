<template>
    <div class="w-full mb-10">
        <Link :href="`/agent/${agent.id}-${slugify(agent.user.name)}`" class="relative w-full z-0">
        <!-- Agent Image -->
        <div class="relative overflow-hidden border-3 border-gray-200">
            <img :src="profileImage" alt="Agent Image"
                class="transition-transform duration-500 h-[350px] object-cover transform hover:scale-110 w-full" />
        </div>

        <!-- Description -->
        <div
            class="relative w-[85%] md:w-[80%] bg-white p-5 -mt-[50px] ml-5 shadow-[0px_5px_21px_-14px_rgba(0,0,0,0.14)]">
            <h3 class="text-[20px] font-medium leading-tight mb-2">
                <Link :href="`/agent/${agent.id}-${slugify(agent.user.name)}`" class="text-black hover:text-[#e86ed0]">
                {{ agent.user.name }}
                </Link>
            </h3>
            <p class="text-[16px] m-0">
                <span class="text-gray-500">Listing</span>
                <span class="text-black ml-1">— {{ agent.property_count }} Properties</span>
            </p>
        </div>
        </Link>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3';

const { agent } = defineProps({
    agent: {
        type: Object,
        required: true,
    },
})

function slugify(name) {
    return name.toLowerCase().replace(/\s+/g, '-')
}
const profileImage = computed(() =>
    agent.user?.profile_photo_path ? `/storage/${agent.user.profile_photo_path}` : 'https://cdn.pixabay.com/photo/2023/02/18/11/00/icon-7797704_640.png'
)
</script>
