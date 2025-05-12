<template>
    <div>
        <label class="block text-sm font-medium mb-1">{{ label }}</label>
        <select v-model="model" class="form-select w-full text-xs rounded border-gray-300">
            <option :value="null">Any</option>
            <option :value="true">Yes</option>
            <option :value="false">No</option>
        </select>
    </div>
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps({
    modelValue: [Boolean, String, Number, null],
    label: String
})
const emit = defineEmits(['update:modelValue'])
const model = computed({
    get: () => props.modelValue,
    set: value => emit('update:modelValue', value)
})
</script>
