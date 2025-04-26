<template>

    <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-sm w-full text-center">
            <h2 class="text-lg font-semibold mb-4">{{ title }}</h2>
            <p class="text-gray-600 mb-6 max-h-64" v-html="message"></p>
            <div class="flex justify-center gap-4">
                <button @click="cancel" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
                <button @click="confirm"
                    class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
    visible: Boolean,
    title: String,
    message: String
})

const emit = defineEmits(['confirm', 'cancel'])

function confirm() {
    emit('confirm')
}
function cancel() {
    emit('cancel')
}
</script>
