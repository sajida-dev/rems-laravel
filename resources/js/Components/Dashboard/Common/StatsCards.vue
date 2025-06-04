<template>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div v-for="(card, index) in cards" :key="index" :class="[
            'p-4 rounded-lg',
            'bg-white',
            'border border-gray-200 shadow'
        ]">
            <h3 :class="['text-lg font-semibold', 'text-gray-500']">
                {{ card.title }}
            </h3>
            <p :class="['text-2xl font-bold', card.valueColor || 'text-gray-600']">
                {{ card.value }}
            </p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    cards: {
        type: Array,
        required: true,
        validator: (cards) => {
            return cards.every(card =>
                typeof card.title === 'string' &&
                typeof card.value !== 'undefined'
            );
        }
    }
});
</script>