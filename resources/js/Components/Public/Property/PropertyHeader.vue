<!-- <template>
    <div>
        <h1 class="text-3xl font-semibold text-gray-800 mb-2">{{ title }}</h1>
        <p class="text-gray-600 flex items-center">
            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i> {{ location }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    title: String,
    location: String,
})
</script> -->



<template>
    <div>
        <!-- Header Image -->
        <div class="w-full h-[600px] mb-10 bg-cover bg-center"
            :style="{ backgroundImage: 'url(' + property.image_url + ')' }"></div>
        <!-- Header Text -->
        <div class="text-center flex flex-col space-y-7">
            <span class="text-xs uppercase font-semibold tracking-[2px] text-black/40 block">
                {{ property.location }}
            </span>
            <h2 class="text-5xl text-gray-700 font-bold mt-2">{{ property.title }}</h2>
            <div class="mt-4">
                <!-- <button v-if="property.status === 'available'" @click="$emit('buy')"
                    class="py-2.5 px-5 mx-auto no-underline text-base rounded transition-all font-semibold bg-[rgb(255,97,221)] text-white border-0 hover:scale-[1.03] hover:border hover:border-[rgb(255,97,221)] hover:bg-transparent hover:text-[rgb(255,97,221)]  duration-500 flex items-center">
                    <i class="mr-2 fas fa-shopping-cart"></i> Buy Now
                </button> -->
                <Button :property="property" />


            </div>
        </div>
    </div>
</template>

<script setup>
import Button from '@Components/Public/Property/Button.vue';
defineProps({
    property: {
        type: Object,
        required: true,
    },
})
</script>

<script>
export default {
    name: "Property Header",
    components: {
        Button,
    }
}
</script>
