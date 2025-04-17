<template>
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <!-- If message exists -->
            <div v-if="properties.length">
                <SectionHeading subheading="Properties" title="Our Properties" />
                <div class="flex flex-wrap -mx-4">
                    <div v-for="property in properties" :key="property.id" class="w-full px-4 mb-10"
                        :class="getColumnClass(columns)">
                        <PropertyCard :property="property" />
                    </div>
                </div>
                <!-- Pagination -->
                <Pagination :currentPage="currentPage" :totalPages="totalPages" @change="handlePageChange" />
            </div>

            <!-- No Properties / Message -->
            <div v-else class="text-center">
                <div class="text-pink-500 p-5 rounded shadow-sm">
                    {{ message || 'No properties found.' }}
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import PropertyCard from './PropertyCard.vue'
import Pagination from '@/Components/Public/Common/Pagination.vue'
import SectionHeading from '@/Components/Public/Common/SectionHeading.vue'

const props = defineProps({
    properties: Array,
    message: String,
    currentPage: Number,
    totalPages: Number,
    columns: {
        type: Number,
        default: 3,
    },
})

const emit = defineEmits(['updatePage'])

function handlePageChange(page) {
    emit('updatePage', page)
}

const getColumnClass = (cols) => {
    switch (cols) {
        case 2:
            return 'md:w-1/2';
        case 3:
            return 'md:w-1/2 lg:w-1/3';
        case 4:
            return 'md:w-1/2 lg:w-1/3 xl:w-1/4';
        default:
            return 'md:w-1/2 lg:w-1/3'; // fallback
    }
};
</script>
