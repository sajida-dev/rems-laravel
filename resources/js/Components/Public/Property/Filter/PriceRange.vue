<template>
    <div class="grid grid-cols-2 gap-2">
        <div>
            <label class="block text-xs font-medium mb-1">Rent Min Price ($)</label>
            <input type="number" :value="rentMin" @input="$emit('update:rentMin', $event.target.value)"
                class="form-input w-full px-2 py-1 text-xs border-gray-300 focus:border-gray-100 h-9 rounded-md"
                placeholder="0" />
        </div>
        <div>
            <label class="block text-xs font-medium mb-1">Rent Max Price ($)</label>
            <input type="number" :value="rentMax" @input="$emit('update:rentMax', $event.target.value)"
                class="form-input w-full px-2 py-1 text-xs border-gray-300 focus:border-gray-100 h-9 rounded-md"
                placeholder="500000" />
        </div>
    </div>
    <div class="grid grid-cols-2 gap-2">
        <div>
            <label class="block text-xs font-medium mb-1">Purchase Min Price ($)</label>
            <input type="number" :value="purchaseMin" @input="$emit('update:purchaseMin', $event.target.value)"
                class="form-input w-full px-2 py-1 text-xs border-gray-300 focus:border-gray-100 h-9 rounded-md"
                placeholder="0" />
        </div>
        <div>
            <label class="block text-xs font-medium mb-1">Purchase Max Price ($)</label>
            <input type="number" :value="purchaseMax" @input="$emit('update:purchaseMax', $event.target.value)"
                class="form-input w-full px-2 py-1 text-xs border-gray-300 focus:border-gray-100 h-9 rounded-md"
                placeholder="500000" />
        </div>
    </div>
</template>

<script setup>
defineProps({
    rentMin: [String, Number],
    rentMax: [String, Number],
    purchaseMin: [String, Number],
    purchaseMax: [String, Number],
})
defineEmits(
    [
        'update:rentMin',
        'update:rentMax',
        'update:purchaseMin',
        'update:purchaseMax',
    ]
)
</script>
