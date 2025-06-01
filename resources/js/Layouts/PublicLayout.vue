<template>
    <div>

        <Header />
        <main class="">
            <PageLoader :loading="loading" />
            <slot />
        </main>
        <Footer />
    </div>
</template>

<script setup>
import Header from '@/Components/Public/Common/Header.vue'
import Footer from '@/Components/Public/Common/Footer.vue'
import PageLoader from '@/Components/Public/Common/PageLoader.vue'
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const loading = ref(false)
const { props } = usePage()

// Watch for page visits to show/hide loader
const page = usePage()
page.on('start', () => {
    loading.value = true
})
page.on('finish', () => {
    loading.value = false
})

defineProps({
    isHomePage: {
        type: Boolean,
        default: false
    }
})
</script>
