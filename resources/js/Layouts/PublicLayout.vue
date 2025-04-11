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

console.log('header for rems')
const props = defineProps({
    isHomePage: {
        type: Boolean,
        default: false
    }
})
</script>
