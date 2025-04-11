<template>
    <div class="flex min-h-screen">
        <Sidebar />
        <div class="flex-1 flex flex-col">
            <Header />
            <PageHeader />
            <main class="flex-1 p-4">
                <slot />
            </main>
            <Footer />
        </div>
    </div>
</template>

<script setup>
import Sidebar from '@/Components/Admin/Sidebar.vue'
import Header from '@/Components/Admin/Header.vue'
import PageHeader from '@/Components/Admin/PageHeader.vue'
import Footer from '@/Components/Admin/Footer.vue'
</script>

<template>
    <div class="flex">
        <Sidebar>
            <template #logo>
                <ApplicationLogo class="w-20 h-20" />
            </template>
        </Sidebar>

        <div class="main-content">
            <Header />
            <PageHeader :title="pageTitle" />
            <slot />
            <Footer />
        </div>
    </div>
</template>

<script setup>
import Sidebar from '@/Components/Sidebar.vue'
import Header from '@/Components/Header.vue'
import PageHeader from '@/Components/PageHeader.vue'
import Footer from '@/Components/Footer.vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'

defineProps({
  pageTitle: String
})
</script>
