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
      :pagination="{
        total: categories.total,
        perPage: categories.per_page,
        currentPage: categories.current_page,
        lastPage: categories.last_page
      }"
      @update="loadData"
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
import { toast } from 'vue3-toastify'
import { reactive } from 'vue'

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

const tableState = reactive({
  filters: { global: '' },
  sortBy: 'id',
  sortDesc: false,
  page: 1,
  perPage: 10,
})


const deleteCategory = (row) => {

  router.delete(route('categories.destroy', row.id), {
    preserveScroll: true,
    onSuccess: (response) => {
      toast.success(response.props.flash.success)
      loadData()
    },
    onError: (errors) => {
      toast.error('Something went wrong!')
    },

  })
}


const loadData = (options = {}) => {
  router.get(route('categories.index'), {
    global: tableState.filters.global,
    sortBy: tableState.sortBy,
    sortDesc: tableState.sortDesc,
    page: options.page ?? tableState.page,
    perPage: tableState.perPage,
  }, {
    preserveState: true,
    replace: true,
  })
}
const updateTableState = ({ filters, sortBy, sortDesc, page, perPage }) => {
  tableState.filters = filters
  tableState.sortBy = sortBy
  tableState.sortDesc = sortDesc
  tableState.page = page
  tableState.perPage = perPage

  loadData()
}

</script>