<template>
  <div class="group relative cursor-pointer overflow-hidden bg-white px-4 pt-6 pb-4 shadow-md ring-1 ring-gray-900/5 
    transition-all duration-500 hover:-translate-y-1 hover:shadow-xl rounded-xl w-full">
    <span
      class="absolute top-8 left-1/2 -translate-x-1/2 z-0 h-16 w-16 rounded-full bg-pink-500 transition-all duration-500 group-hover:scale-[10]"></span>

    <div class="relative z-10 flex flex-col items-center text-center">
      <span
        class="grid h-20 w-20 place-items-center rounded-full bg-pink-500 transition-all duration-500 group-hover:bg-pink-400">
        <i :class="icon" class="text-white text-3xl"></i>
      </span>

      <div class="mt-4 text-base text-gray-600 transition-all duration-500 group-hover:text-white/90">
        <p>{{ title }}</p>
      </div>

      <h4 class="mt-2 text-2xl font-semibold text-gray-900 group-hover:text-white">
        {{ formattedCount }}
      </h4>
    </div>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue';

const props = defineProps({
  title: String,
  count: Number,
  icon: String,
  bgColor: String,
});

const formattedCount = computed(() => {
  return new Intl.NumberFormat().format(props.count);
});
</script>
