<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
    <Card :title="'My Total Properties'" :count="totalPropertiesCount" :icon="'fas fa-home'" :bgColor="'bg-primary'" />
    <Card :title="'Available Properties'" :count="availablePropertiesCount" :icon="'fas fa-check-circle'"
      :bgColor="'bg-info'" />
    <Card :title="'Sold Properties'" :count="soldPropertiesCount" :icon="'fas fa-clipboard-check'"
      :bgColor="'bg-success'" />
    <Card :title="'Rented Properties'" :count="rentedPropertiesCount" :icon="'fas fa-key'" :bgColor="'bg-secondary'" />
  </div>
  <UserInstructions />
</template>

<script setup>
import { ref } from 'vue';
import Card from '@Components/Dashboard/Common/Card.vue'
import UserInstructions from './Common/UserInstructions.vue';

const { stats } = defineProps({
  stats: {
    type: Object,
    required: true
  }
})

const totalPropertiesCount = ref(stats.totalProperties);
const availablePropertiesCount = ref(stats.availableProperties);
const soldPropertiesCount = ref(stats.soldProperties);
const rentedPropertiesCount = ref(stats.rentedProperties);
</script>
