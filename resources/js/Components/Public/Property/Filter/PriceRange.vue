<template>
    <div class="grid grid-cols-2 gap-2">
        <div>
            <label class="block text-sm font-medium mb-1">Min Price ($)</label>
            <input type="number" :value="min" @input="$emit('update:min', $event.target.value)"
                class="form-input w-full px-2 py-1 text-sm h-9 rounded-md" placeholder="0" />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Max Price ($)</label>
            <input type="number" :value="max" @input="$emit('update:max', $event.target.value)"
                class="form-input w-full px-2 py-1 text-sm h-9 rounded-md" placeholder="500000" />
        </div>
    </div>
</template>

<script setup>
defineProps({
    min: [String, Number],
    max: [String, Number],
})
defineEmits(['update:min', 'update:max'])
</script>
