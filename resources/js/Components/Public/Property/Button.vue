<template>
    <div v-if="property.status === 'available'" class="flex flex-col sm:flex-row gap-4 justify-center">
        <!-- Buy Now Button -->
        <button @click="$emit('buy')"
            class="py-2.5 px-5 text-base rounded font-semibold bg-[rgb(255,97,221)] text-white border-0 hover:scale-[1.03] hover:border hover:border-[rgb(255,97,221)] hover:bg-transparent hover:text-[rgb(255,97,221)] duration-500 flex items-center">
            <i class="mr-2 fas fa-shopping-cart"></i>
            Apply For Buy
        </button>

        <!-- Rent Now Button -->
        <button @click="$emit('rent')"
            class="py-2.5 px-5 text-base rounded font-semibold bg-blue-500 text-white border-0 hover:scale-[1.03] hover:border hover:border-blue-500 hover:bg-transparent hover:text-blue-500 duration-500 flex items-center">
            <i class="mr-2 fas fa-home"></i>
            Apply For Rent
        </button>
    </div>

    <!-- If not available -->
    <span v-else class="text-lg font-semibold text-gray-500">Sold out</span>
</template>

<script setup>
defineProps({
    property: {
        type: Object,
        required: true
    }
});
</script>
