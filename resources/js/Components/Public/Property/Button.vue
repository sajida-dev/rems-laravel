<template>
    <button v-if="property.status === 'available'" @click="$emit('buy')"
        class="py-2.5 px-5 mx-auto no-underline text-base rounded transition-all font-semibold bg-[rgb(255,97,221)] text-white border-0 hover:scale-[1.03] hover:border hover:border-[rgb(255,97,221)] hover:bg-transparent hover:text-[rgb(255,97,221)]  duration-500 flex items-center">
        <i class="mr-2 fas fa-shopping-cart"></i>
        Buy Now
    </button>
    <span v-else class="text-lg font-semibold text-gray-500">Sold out</span>
</template>

<script>
export default {
    name: "Button",
}
</script>
<script setup>
defineProps({
    property: {
        type: Object,
        required: true
    }
});
</script>