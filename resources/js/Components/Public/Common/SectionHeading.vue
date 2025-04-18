<template>
    <div class="flex justify-center mb-12">
        <div class="text-center heading-section ftco-animate">
            <div class="flex items-center my-8 w-64 mx-auto">
                <hr class="flex-grow border-t-2 border-pink-300">
                <span class="subheading mx-4 text-base text-pink-700 uppercase tracking-widest">
                    {{ subheading }}
                </span>
                <hr class="flex-grow border-t-2 border-pink-300">
            </div>
            <h2 class="text-5xl  my-2">{{ title }}</h2>
        </div>
    </div>
</template>

<script setup>
defineProps({
    subheading: {
        type: String,
        required: true
    },
    title: {
        type: String,
        required: true
    }
})
</script>
