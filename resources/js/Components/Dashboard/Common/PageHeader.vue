<template>
    <div class="flex flex-row justify-start mx-10 mt-5 space-x-5 page-header mb-6">
        <h4 class="text-2xl font-semibold text-gray-800 ">
            {{ title }}
        </h4>

        <nav class="mt-2 text-sm md:block hidden" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center text-gray-500  space-x-2">
                <li>
                    <a href="#"
                        class="text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center">
                        <i class="icon-home mr-1"></i>
                        <span class="hidden sm:inline">Home</span>
                    </a>
                </li>

                <li><span class="mx-1 text-gray-400">/</span></li>

                <li>
                    <a href="#" class="hover:text-gray-900  transition-colors duration-200">
                        {{ mainPage }}
                    </a>
                </li>

                <li><span class="mx-1 text-gray-400">/</span></li>

                <li class="text-gray-700  font-medium">
                    {{ page }}
                </li>
            </ol>
        </nav>
    </div>
</template>

<script setup>
defineProps({
    title: String,
    mainPage: String,
    page: String,
});
</script>
