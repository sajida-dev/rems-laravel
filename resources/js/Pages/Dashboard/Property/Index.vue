<template lang="">
    <Head title="All Properties" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
   <div class="">
    <h2 class="text-2xl font-semibold mb-4">Properties</h2>

    <ServerSideDataTable
      :columns="columns"
      :rows="properties.data"
      :selectable="false"
      :expandable="false"
      :filterable="true"
      :perPage="properties.per_page"
      :virtualScroll="false"
      createRoute="/properties/create"
      createLabel="Add Property"
      :hasRowActions="true"
      :pagination="{
        total: properties.total,
        perPage: properties.per_page,
        currentPage: properties.current_page,
        lastPage: properties.last_page
      }"
      @update="loadData"
    >

    <template #row-actions="{ row }">
  <RowActions
    :row="row"
    :editRoute="row => route('properties.edit', row.id)"
    :deleteHandler="deleteData"
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
    title: 'Properties',
    mainPage: 'Pages',
    page: 'Create',
};
provide('layoutHeader', header)
const props = defineProps({
    properties: Object,
})

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'title', label: 'Title' },
    { key: 'agent', label: 'Agent' },
    { key: 'location', label: 'Location' },
    { key: 'rent_price', label: 'Rent Price' },
    { key: 'purchase_price', label: 'Purchase Price' },
    { key: 'build_year', label: 'Build Year' },
    { key: 'lot_area', label: 'Lot Area' },
    { key: 'floor_area', label: 'Floor Area' },
    { key: 'status', label: 'Status' },
]

const deleteData = (row) => {

    router.delete(route('properties.destroy', row.id), {
        preserveScroll: true,
        onSuccess: (response) => {
            toast.success(response.props.flash.success)
            loadData({
                filters: { global: '' },
                sortBy: 'id',
                sortDesc: false,
                page: 1,
                perPage: 10,
            })
        },
        onError: (errors) => {
            toast.error('Something went wrong!')
        },
    })
}


const tableState = reactive({
    filters: { global: '' },
    sortBy: 'id',
    sortDesc: false,
    page: 1,
    perPage: 10,
})


const loadData = (options = {}) => {
    router.get(route('properties.index'), {
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