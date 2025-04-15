<template>
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <!-- If message exists -->
            <div v-if="message && agents.length">
                <SectionHeading subheading="Agents" title="Our Agents" />
                <!-- Agent Cards -->
                <div class="flex flex-wrap -mx-4">
                    <div v-for="agent in agents" :key="agent.id" class="w-full md:w-1/2 lg:w-1/4 px-4 mb-10">
                        <AgentCard :agent="agent" />
                    </div>
                </div>
                <!-- Pagination -->
                <Pagination :currentPage="currentPage" :totalPages="totalPages" @change="handlePageChange" />
            </div>

            <!-- No Agents / Message -->
            <div v-else class="text-center">
                <div class="bg-blue-100 text-blue-700 p-5 rounded shadow-sm">
                    {{ message }}
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import AgentCard from './AgentCard.vue'
import Pagination from '@/Components/Public/Common/Pagination.vue'
import SectionHeading from '@/Components/Public/Common/SectionHeading.vue'

const props = defineProps({
    agents: Array,
    message: String,
    currentPage: Number,
    totalPages: Number
})

const emit = defineEmits(['updatePage'])

function handlePageChange(page) {
    emit('updatePage', page)
}
</script>
