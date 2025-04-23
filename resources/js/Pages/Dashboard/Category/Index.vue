<template lang="">
    <Head title="All Categories" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
   <div class="">
    <h2 class="text-2xl font-semibold mb-4">Categories</h2>

    <ServerSideDataTable
      :columns="columns"
      :rows="categories.data"
      :selectable="false"
      :expandable="false"
      :filterable="true"
      :perPage="categories.per_page"
      :virtualScroll="false"
      @update="loadCategories"
    >
      <!-- Example: custom render for dates -->
      <template #cell-created_at="{ row }">
        {{ new Date(row.created_at).toLocaleDateString() }}
      </template>
<template #cell-updated_at="{ row }">
        {{ new Date(row.updated_at).toLocaleDateString() }}
      </template>
</ServerSideDataTable>
</div>
</div>
</template>
<script setup>
import { router } from '@inertiajs/vue3'
import ServerSideDataTable from '@/Components/Dashboard/Common/ServerSideDataTable.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3'
import { provide } from 'vue'
defineOptions({ layout: DashboardLayout })

const header = {
    title: 'Category',
    mainPage: 'Pages',
    page: 'Create',
};
provide('layoutHeader', header)
const props = defineProps({
    categories: Object, // Inertia prop from controller
})

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'description', label: 'Description' },
    { key: 'action', label: 'Action' },

]

// fired whenever DataTable’s search, sort, page, perPage change
const loadCategories = ({ filters, sortBy, sortDesc, page, perPage }) => {
    router.get(
        route('dashboard.categories.index'), // your named route
        {
            global: filters.global,
            sortBy,
            sortDesc,
            page,
            perPage,
        },
        {
            preserveState: true,
            replace: true,
        }
    )
}
</script>