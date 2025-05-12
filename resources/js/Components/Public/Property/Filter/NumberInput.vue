<template>
    <div>
        <label class="block text-sm font-medium mb-1">{{ label }}</label>
        <input type="number" v-model="model"
            class="form-input w-full text-xs rounded border-gray-300 focus:border-gray-100" />
    </div>
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps({
    modelValue: [Number, String],
    label: String
})
const emit = defineEmits(['update:modelValue'])
const model = computed({
    get: () => props.modelValue,
    set: value => emit('update:modelValue', value)
})
</script>
