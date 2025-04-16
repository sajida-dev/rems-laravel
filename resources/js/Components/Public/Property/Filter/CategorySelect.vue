<template>
    <div>
        <label class="block text-sm font-medium mb-1">Category</label>
        <select :value="modelValue" @change="$emit('update:modelValue', $event.target.value)"
            class="form-select w-full px-2 py-1 text-sm h-9 rounded-md">
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
            </option>
        </select>
    </div>
</template>

<script setup>
defineProps({
    modelValue: [String, Number],
    categories: Array,
})
defineEmits(['update:modelValue'])
</script>
