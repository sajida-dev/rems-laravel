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
      createRoute="/categories/create"
      createLabel="Add Category"
      :hasRowActions="true"
      @update="loadCategories"
    >

    <template #row-actions="{ row }">
  <RowActions
    :row="row"
    :editRoute="row => route('categories.edit', row.id)"
    :deleteHandler="deleteCategory"
  />
</template>
</ServerSideDataTable>

</div>
</div>
</template>
<script setup>
import { router } from '@inertiajs/vue3'
import RowActions from '@/Components/Dashboard/Common/RowActions.vue'
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
  categories: Object,
})

const columns = [
  { key: 'id', label: 'ID' },
  { key: 'name', label: 'Name' },
  { key: 'description', label: 'Description' },

]

const deleteCategory = (row) => {

  router.delete(route('categories.destroy', row.id), {
    preserveScroll: true,
    onSuccess: () => {
      // loadCategories()
      loadCategories({
        filters: { global: '' },
        sortBy: 'id',
        sortDesc: false,
        page: 1,
        perPage: 10,
      })
    }
  })
}

// const confirmDelete = () => {
//   router.delete(route('categories.destroy', selectedRow.value.id), {
//     preserveScroll: true,
//     onSuccess: () => {
//       loadCategories()
//     }
//   })
//   showConfirm.value = false
// }

const loadCategories = ({ filters, sortBy, sortDesc, page, perPage }) => {
  router.get(
    route('categories.index'),
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