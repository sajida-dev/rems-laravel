<template>
    <div>
        <label class="block text-sm font-medium mb-1">Amenities</label>
        <div class="flex flex-wrap gap-2">
            <label v-for="amenity in amenities" :key="amenity.id" class="inline-flex items-center space-x-2">
                <input type="checkbox" class="form-checkbox text-primary px-2 py-1 text-sm h-9 rounded-md"
                    :value="amenity.id" :checked="modelValue.includes(amenity.id)"
                    @change="toggleAmenity(amenity.id)" />
                <span class="text-sm">{{ amenity.name }}</span>
            </label>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    modelValue: Array,
    amenities: Array,
})
const emit = defineEmits(['update:modelValue'])

const toggleAmenity = (id) => {
    const newValue = props.modelValue.includes(id)
        ? props.modelValue.filter((val) => val !== id)
        : [...props.modelValue, id]

    emit('update:modelValue', newValue)
}
</script>
