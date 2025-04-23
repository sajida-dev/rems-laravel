<template>
    <div class="fixed top-24 right-10 z-[1050] space-y-2" v-if="visible">
        <transition name="fade-slide">
            <div v-if="message"
                class="alert alert-success bg-green-500 text-white px-4 py-2 rounded shadow animate__animated animate__fadeInDown">
                {{ message }}
            </div>
        </transition>

        <transition name="fade-slide">
            <div v-if="error"
                class="alert alert-danger bg-red-500 text-white px-4 py-2 rounded shadow animate__animated animate__fadeInDown">
                {{ error }}
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, watchEffect } from 'vue'
import { usePage } from '@inertiajs/vue3'

const { props } = usePage()

const message = ref(props.flash.msg)
const error = ref(props.flash.error)
const visible = ref(!!message.value || !!error.value)

watchEffect(() => {
    if (visible.value) {
        setTimeout(() => (visible.value = false), 3000)
    }
})
</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.4s ease;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
