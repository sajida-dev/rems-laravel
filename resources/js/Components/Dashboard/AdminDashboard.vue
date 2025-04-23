<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
    <Card
      :title="'Agents'"
      :count="agentsCount"
      :icon="'fas fa-users'"
      :bgColor="'bg-primary'"
    />
    <Card
      :title="'End Users'"
      :count="endUsersCount"
      :icon="'fas fa-user-check'"
      :bgColor="'bg-info'"
    />
    <Card
      :title="'Properties'"
      :count="propertiesCount"
      :icon="'fas fa-home'"
      :bgColor="'bg-success'"
    />
    <Card
      :title="'Orders'"
      :count="ordersCount"
      :icon="'far fa-check-circle'"
      :bgColor="'bg-secondary'"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Card from '@Components/Dashboard/Common/Card.vue'

// Replace these values with dynamic data from the backend or props.
const agentsCount = ref(120);
const endUsersCount = ref(200);
const propertiesCount = ref(50);
const ordersCount = ref(75);
</script>