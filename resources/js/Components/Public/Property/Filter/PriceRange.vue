<template>
    <div class="space-y-3">
        <label class="block text-sm font-medium text-gray-700">Price Range</label>

        <!-- Rent Price Range -->
        <div v-if="type === 'rent' || !type" class="space-y-2">
            <div class="flex items-center space-x-2">
                <input type="number" :value="rentMin" @input="$emit('update:rentMin', $event.target.value)"
                    placeholder="Min Rent"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50" />
                <span class="text-gray-500">to</span>
                <input type="number" :value="rentMax" @input="$emit('update:rentMax', $event.target.value)"
                    placeholder="Max Rent"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50" />
            </div>
        </div>

        <!-- Purchase Price Range -->
        <div v-if="type === 'buy' || !type" class="space-y-2">
            <div class="flex items-center space-x-2">
                <input type="number" :value="purchaseMin" @input="$emit('update:purchaseMin', $event.target.value)"
                    placeholder="Min Purchase Price"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50" />
                <span class="text-gray-500">to</span>
                <input type="number" :value="purchaseMax" @input="$emit('update:purchaseMax', $event.target.value)"
                    placeholder="Max Purchase Price"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50" />
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    rentMin: {
        type: [Number, String],
        default: null
    },
    rentMax: {
        type: [Number, String],
        default: null
    },
    purchaseMin: {
        type: [Number, String],
        default: null
    },
    purchaseMax: {
        type: [Number, String],
        default: null
    },
    type: {
        type: String,
        default: ''
    }
})

defineEmits(['update:rentMin', 'update:rentMax', 'update:purchaseMin', 'update:purchaseMax'])
</script>
