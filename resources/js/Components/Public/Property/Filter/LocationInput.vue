<template>
    <div>
        <label class="block text-sm font-medium mb-1">Location</label>
        <input type="text" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"
            class="form-input w-full px-2 py-1 text-xs h-9 border-gray-300 focus:border-gray-100 rounded-md"
            placeholder="Enter location" />
    </div>
</template>

<script setup>
defineProps(['modelValue'])
defineEmits(['update:modelValue'])
</script>
