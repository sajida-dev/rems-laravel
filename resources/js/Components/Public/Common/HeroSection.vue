<template>
    <section class="overflow-hidden pb-7 lg:min-h-[600px] md:min-h-[750px] min-h-[500px]">
        <section
            class="relative w-full lg:min-h-[600px] md:min-h-[850px] min-h-[500px] bg-cover  bg-center flex items-center "
            :style="{ backgroundImage: `url('/${bgImgUrl}')` }">
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-b from-white via-white/30 to-transparent z-10"></div>

            <!-- Skewed White Bottom -->
            <div class="absolute bottom-0 left-0 w-1/2 h-[90px] bg-white skew-y-[7deg] origin-bottom-left z-20"></div>
            <div class="absolute bottom-0 right-0 w-1/2 h-[90px] bg-white -skew-y-[7deg] origin-bottom-right z-20">
            </div>

            <!-- Content -->
            <div class="relative z-30 w-full max-w-5xl mx-auto px-4 text-center pt-12 md:pt-0">
                <!-- Page Layout -->
                <template v-if="variant === 'page'">
                    <div v-if="breadcrumbs?.length"
                        class="text-sm uppercase font-medium tracking-wide text-gray-700 mb-4">
                        <p class="breadcrumbs space-x-2">
                            <template v-for="(crumb, index) in breadcrumbs" :key="index">
                                <span v-if="crumb.link">
                                    <a :href="crumb.link" class="text-gray-600 hover:text-pink-500 transition">
                                        {{ crumb.label }}
                                        <i class="fas fa-angle-right text-xs mx-1"></i>
                                    </a>
                                </span>
                                <span v-else>{{ crumb.label }}</span>
                            </template>
                        </p>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-normal text-black mb-6 whitespace-pre-line leading-tight">
                        {{ heading }}
                    </h1>
                </template>

                <!-- Home Layout -->
                <template v-else>
                    <h1
                        class="text-4xl md:text-6xl lg:text-7xl lg:mx-36 my-0 font-normal leading-tight text-black mb-4">
                        {{ heading }}
                    </h1>
                    <p v-if="subheading"
                        class="text-lg md:text-xl text-gray-700 mb-6 leading-relaxed max-w-3xl mx-auto">
                        {{ subheading }}
                    </p>
                </template>

                <!-- Search Form -->
                <form v-if="showSearchForm" :action="formAction" method="POST"
                    class="w-full max-w-3xl mx-auto mt-4 lg:mb-2 mb-9">
                    <div class="relative">
                        <input type="text" name="search" :placeholder="searchPlaceholder"
                            class="w-full h-[70px] rounded-full px-6 pr-[70px] shadow-lg border-none focus:ring-2 focus:ring-pink-400" />
                        <button type="submit"
                            class="absolute top-1/2 right-0 transform -translate-y-1/2 w-[70px] h-[70px] rounded-br-full rounded-tr-full rounded-bl-full bg-pink-400 flex items-center justify-center">
                            <span class="text-white text-2xl">
                                <i class="fas fa-search"></i>
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Scroll Mouse Icon -->
            <div v-if="variant === 'home'"
                class="absolute lg:bottom-[-20px] bottom-[-3px] left-0 right-0 z-30 flex justify-center">
                <a href="#scrollTarget"
                    class="w-[80px] h-[80px] bg-pink-400 border border-pink-400 rounded-full flex items-center justify-center  text-white text-3xl">
                    <i class="fas fa-angle-down animate-bounce"></i>
                </a>
            </div>
        </section>
    </section>
</template>

<script setup>
defineProps({
    variant: {
        type: String,
        default: 'home', // 'home' or 'page'
    },
    heading: {
        type: String,
        required: true,
    },
    subheading: {
        type: String,
        default: '',
    },
    breadcrumbs: {
        type: Array,
        default: () => [],
    },
    showSearchForm: {
        type: Boolean,
        default: false,
    },
    searchPlaceholder: {
        type: String,
        default: 'Search location, price, or area',
    },
    formAction: {
        type: String,
        default: '#',
    },
});
const bgImgUrl = "frontend/images/bg_1.jpg";
</script>
