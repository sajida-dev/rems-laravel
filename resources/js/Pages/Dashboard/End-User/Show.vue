<template>

    <Head :title="endUser.name" />
    <div class="bg-white p-5 rounded flex flex-col md:flex-row">

        <div class="w-3/4 flex flex-col">
            <h2 class="text-3xl text-center text-pink-500 py-3" v-html="endUser.name"></h2>
            <h2 class="text-2xl text-center">Transactions</h2>
            <div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo odio vel facere repellendus cum vero esse
                nam. Nisi, illum fuga quam molestiae sed eaque? Maxime aliquid ea odit illum atque?</div>
        </div>
        <div class="w-1/4 text-center ">
            <div class="flex justify-center">
                <img :src="endUser.profile_photo_path" alt="Avatar"
                    class="w-64 h-64 rounded-full shadow-md mb-4 cursor-pointer" />

            </div>
            <h2 class="text-xl">Full Name: {{ endUser.name }}</h2>
            <p>Email: {{ endUser.email }}</p>
            <p>Contact: {{ endUser.contact }}</p>
        </div>
    </div>
</template>
<script setup>
import { Head } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
defineOptions({ layout: DashboardLayout })
defineProps({
    endUser: Object
})
</script>
<style></style>