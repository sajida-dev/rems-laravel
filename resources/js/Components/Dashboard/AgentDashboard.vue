<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
    <Card
      :title="'Total'"
      :count="totalPropertiesCount"
      :icon="'fas fa-home'"
      :bgColor="'bg-primary'"
    />
    <Card
      :title="'Available'"
      :count="availablePropertiesCount"
      :icon="'fas fa-check-circle'"
      :bgColor="'bg-info'"
    />
    <Card
      :title="'Sold'"
      :count="soldPropertiesCount"
      :icon="'fas fa-clipboard-check'"
      :bgColor="'bg-success'"
    />
    <Card
      :title="'Rented'"
      :count="rentedPropertiesCount"
      :icon="'fas fa-key'"
      :bgColor="'bg-secondary'"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Card from '@Components/Dashboard/Common/Card.vue'

// Replace these values with dynamic data from the backend or props.
const totalPropertiesCount = ref(150);
const availablePropertiesCount = ref(100);
const soldPropertiesCount = ref(30);
const rentedPropertiesCount = ref(20);
</script>
