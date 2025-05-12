<template>
    <div class="flex h-screen bg-gray-100">
        <Sidebar :open="sidebarOpen" @close="sidebarOpen = false" :user="user" :pendingCount="pendingCount" />
        <div v-if="sidebarOpen" class="fixed inset-0 bg-black bg-opacity-40 z-10 lg:hidden"
            @click="sidebarOpen = false"></div>

        <div class="flex-1 flex flex-col">
            <Header @toggle-sidebar="sidebarOpen = !sidebarOpen" :user="user" />

            <PageHeader :title="header.title" :mainPage="header.mainPage" :page="header.page" />
            <main class="flex-1 overflow-y-auto p-6">
                <slot />
            </main>
            <Footer />
        </div>

    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import { ref, inject, onMounted } from 'vue';
import Sidebar from '@Components/Dashboard/Common/Sidebar.vue'
import Footer from '@Components/Dashboard/Common/Footer.vue'
import Header from '@Components/Dashboard/Common/Header.vue'
import PageHeader from '@Components/Dashboard/Common/PageHeader.vue'
import axios from 'axios'

const { props } = usePage()
const user = props.auth?.user
const sidebarOpen = ref(false)
let pendingCount = ref(0)

onMounted(() => {
    if (user.role === 'admin') {
        fetchPendingCount()
    }
})
function fetchPendingCount() {
    axios.get('/agents/pending-count')
        .then(response => {
            pendingCount.value = response.data.count
        })
        .catch(error => {
            console.error('Failed to fetch pending count:', error)
        })
}


const header = inject('layoutHeader', {
    title: 'Dashbaord',
    mainPage: 'Pages',
    page: ''
});



</script>
