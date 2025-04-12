<!-- components/WorkflowItem.vue -->
<template>
    <div class="w-full lg:w-1/2 flex items-center justify-center ftco-animate mb-5 lg:mb-0">
        <div class="w-full h-full">
            <div class="py-4 text-center flex flex-col items-center justify-center h-full">
                <div class="mb-3 flex items-center justify-center w-[90px] h-[90px] bg-[#e86ed0] rounded-full">
                    <span class="text-white text-[30px]">{{ number }}</span>
                </div>
                <h3 class="text-[24px] font-medium text-white mb-2">{{ title }}</h3>
                <p class="text-white/80 m-0 max-w-xs">{{ description }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    number: String,
    title: String,
    description: String
})
</script>
