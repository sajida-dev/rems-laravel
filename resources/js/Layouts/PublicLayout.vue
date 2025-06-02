<template>
    <div>
        <Header />
        <main class="">
            <slot />
        </main>
        <Footer />
    </div>
</template>

<script setup>
import Header from '@/Components/Public/Common/Header.vue'
import Footer from '@/Components/Public/Common/Footer.vue'
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const loading = ref(false)
const { props } = usePage()


defineProps({
    isHomePage: {
        type: Boolean,
        default: false
    }
})
</script>
