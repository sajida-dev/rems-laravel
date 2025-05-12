<template>
    <div>
        <label class="block text-sm font-medium mb-1">Keyword</label>
        <input type="text" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"
            class="form-input focus:outline-none border-gray-300 focus:border-gray-100 text-xs focus:border-none w-full px-2 py-1 h-9 rounded-md"
            placeholder="e.g., Downtown, 3BHK" />
    </div>
</template>

<script setup>
defineProps(['modelValue'])
defineEmits(['update:modelValue'])
</script>
