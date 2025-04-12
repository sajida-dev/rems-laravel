<template>
    <div class="flex justify-center mt-8 block-27">
        <ul class="flex flex-wrap justify-center gap-2 p-0 m-0">
            <!-- Previous -->
            <li>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-full border text-center transition-colors"
                    :class="currentPage === 1 ? 'text-pink-400 border-gray-300 cursor-not-allowed' : 'text-pink-500 border-gray-300 hover:bg-pink-100'"
                    :disabled="currentPage === 1" @click="$emit('change', currentPage - 1)">
                    &lt;
                </button>
            </li>

            <!-- Pages -->
            <li v-for="page in totalPages" :key="page">
                <button @click="$emit('change', page)"
                    class="w-10 h-10 flex items-center justify-center rounded-full border transition-all" :class="[
                        page === currentPage
                            ? 'bg-pink-500 text-white border-transparent'
                            : 'text-pink-500 border-gray-300 hover:bg-pink-100'
                    ]">
                    {{ page }}
                </button>
            </li>

            <!-- Next -->
            <li>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-full border text-center transition-colors"
                    :class="currentPage === totalPages ? 'text-pink-400 border-gray-300 cursor-not-allowed' : 'text-pink-500 border-gray-300 hover:bg-pink-100'"
                    :disabled="currentPage === totalPages" @click="$emit('change', currentPage + 1)">
                    &gt;
                </button>
            </li>
        </ul>
    </div>
</template>

<script setup>
defineProps({
    currentPage: Number,
    totalPages: Number
})
</script>
