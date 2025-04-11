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
import PageLoader from '@/components/Public/Common/PageLoader.vue'

console.log('header for rems')
const props = defineProps({
    isHomePage: {
        type: Boolean,
        default: false
    }
})

// const loading = ref(false)

// onMounted(() => {
//     router.on('start', () => {
//         loading.value = true
//     })

//     router.on('finish', () => {
//         loading.value = false
//     })
// })
</script>
