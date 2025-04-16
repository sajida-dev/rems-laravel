<template>
    <div class="mt-12">
        <!-- Tab Navigation -->
        <div class="flex justify-center mb-8">
            <ul class="flex gap-4 border-b" role="tablist">
                <li>
                    <button :class="tab === 'features' ? activeTabClass : tabClass" @click="tab = 'features'">
                        Features
                    </button>
                </li>
                <li>
                    <button :class="tab === 'description' ? activeTabClass : tabClass" @click="tab = 'description'">
                        Description
                    </button>
                </li>
                <li>
                    <button :class="tab === 'images' ? activeTabClass : tabClass" @click="tab = 'images'">
                        Gallery
                    </button>
                </li>
            </ul>
        </div>
        <!-- Tab Content -->
        <div>
            <PropertyFeatures v-if="tab === 'features'" :property="property" />
            <PropertyDescription v-else-if="tab === 'description'" :description="property.description" />
            <PropertyImages v-else-if="tab === 'images'" :images="property.images" />
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import PropertyFeatures from './PropertyFeatures.vue'
import PropertyDescription from './PropertyDescription.vue'
import PropertyImages from './PropertyImages.vue'

defineProps({
    property: {
        type: Object,
        required: true,
    },
})

const tab = ref('features')

const tabClass =
    'px-4 py-2 text-gray-700 hover:text-pink-500 border-b-2 border-transparent focus:outline-none'
const activeTabClass =
    'px-4 py-2 text-pink-400 border-b-2 border-pink-400 focus:outline-none'
</script>
