<template>
    <div>
        <label class="block text-sm font-medium mb-1">Bedrooms</label>
        <select :value="modelValue" @change="$emit('update:modelValue', $event.target.value)"
            class="form-select w-full px-2 py-1 text-sm h-9 rounded-md">
            <option value="">Any</option>
            <option value="1">1+</option>
            <option value="2">2+</option>
            <option value="3">3+</option>
            <option value="4">4+</option>
        </select>
    </div>
</template>

<script setup>
defineProps(['modelValue'])
defineEmits(['update:modelValue'])
</script>
