<template>
    <div class="relative" ref="dropdownRef">
        <label class="block text-sm font-medium text-gray-700  mb-1">{{ title }}</label>

        <!-- Selected tags -->
        <div class="flex flex-wrap gap-2 border border-gray-300 rounded-lg px-3 py-2 min-h-[34px] bg-white "
            @click="toggleDropdown">
            <span v-for="item in selectedItems" :key="item.id"
                class="flex items-center px-2 py-1 text-sm bg-gray-100 border border-gray-300 rounded-full ">
                {{ item.name }}
                <button @click.stop="remove(item.id)" class="ml-2 text-gray-500 hover:text-red-500">&times;</button>
            </span>

            <span class="ml-auto text-sm text-gray-400" v-if="!selectedItems.length">Click to select</span>
        </div>

        <!-- Dropdown menu -->
        <div v-show="open"
            class="absolute z-50 mt-1 w-full max-h-60 overflow-auto bg-white border border-gray-200 rounded-lg shadow-lg ">
            <div v-for="item in list" :key="item.id" @click="toggleItem(item.id)" :class="[
                'px-4 py-2 cursor-pointer text-sm hover:bg-gray-100 ',
                isSelected(item.id) ? 'bg-blue-50 ' : ''
            ]">
                {{ item.name }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { onClickOutside } from '@vueuse/core'

const props = defineProps({
    list: {
        Array,
        default: () => []
    },
    modelValue: {
        type: Array,
        default: () => []
    },
    title: {
        String,
        default: "Specializations"
    }
})

const emit = defineEmits(['update:modelValue'])

const open = ref(false)
const selected = ref([...props.modelValue])

const toggleDropdown = () => (open.value = !open.value)

const toggleItem = (id) => {
    const index = selected.value.indexOf(id)
    if (index === -1) selected.value.push(id)
    else selected.value.splice(index, 1)
    emit('update:modelValue', [...selected.value])
}

const remove = (id) => {
    selected.value = selected.value.filter(item => item !== id)
    emit('update:modelValue', [...selected.value])
}

const isSelected = (id) => selected.value.includes(id)

const selectedItems = computed(() => {
    return props.list.filter(item => selected.value.includes(item.id))
})

const dropdownRef = ref(null)
onClickOutside(dropdownRef, () => {
    open.value = false
})
</script>

<style scoped>
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 4px;
}
</style>
