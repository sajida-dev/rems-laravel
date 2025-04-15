<template>
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <!-- If message exists -->
            <div v-if="message && properties.length">
                <SectionHeading subheading="Properties" title="Our Properties" />
                <!-- Agent Cards -->
                <div class="flex flex-wrap -mx-4">
                    <div v-for="property in properties" :key="property.id" class="w-full md:w-1/2 lg:w-1/3 px-4 mb-10">
                        <PropertyCard :property="property" />
                    </div>
                </div>
                <!-- Pagination -->
                <Pagination :currentPage="currentPage" :totalPages="totalPages" @change="handlePageChange" />
            </div>

            <!-- No Properties / Message -->
            <div v-else class="text-center">
                <div class="text-pink-500 p-5 rounded shadow-sm">
                    {{ message }}
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
    totalPages: Number
})

const emit = defineEmits(['updatePage'])

function handlePageChange(page) {
    emit('updatePage', page)
}
</script>
