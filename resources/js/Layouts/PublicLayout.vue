<template>
    <div>
        <PageLoader :loading="loading" />
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
import PageLoader from '@/Components/Public/Common/PageLoader.vue'
import { ref } from 'vue'

const loading = ref(false)
const props = defineProps({
    isHomePage: {
        type: Boolean,
        default: false
    }
})

</script>
