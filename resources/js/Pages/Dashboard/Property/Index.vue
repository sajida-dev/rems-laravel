<template lang="">
    <Head title="All Properties" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
   <div class="">
    <h2 class="text-2xl font-semibold mb-4">Properties</h2>

    <ServerSideDataTable
      :columns="columns"
      :rows="formattedProperties"
      :selectable="false"
      :expandable="false"
      :filterable="true"
      :perPage="properties.per_page"
      :virtualScroll="true"
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
      :isProperties="true"
    >

    <template #row-actions="{ row }">
  <RowActions
    :row="row"
    :viewRoute="row => route('properties.show', row.id)"
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
import { reactive, computed } from 'vue'

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


const formattedProperties = computed(() =>
    props.properties.data.map(properties => ({
        ...properties,
        status: properties.status.charAt(0).toUpperCase() + properties.status.slice(1),
        category_name: properties.category?.name ?? '-',
        agent_name: properties.agent.user?.name ?? '-',
        rent_price: properties.rent_price ?? 'Nill',
        purchase_price: properties.purchase_price ?? 'Nill',
    }))
)

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'title', label: 'Title' },
    { key: 'category_name', label: 'Category' },
    { key: 'agent_name', label: 'Agent' },
    { key: 'location', label: 'Location' },
    { key: 'rent_price', label: 'Rent Price' },
    { key: 'purchase_price', label: 'Purchase Price' },
    // { key: 'bedrooms', label: 'Bedrooms' },
    // { key: 'bathrooms', label: 'Bathrooms' },
    { key: 'lot_area', label: 'Lot Area' },
    //{ key: 'floor_area', label: 'Floor Area' },
    { key: 'year_built', label: 'Year Built' },
    // { key: 'is_water', label: 'Water Available' },
    // { key: 'stories', label: 'Stories' },
    // { key: 'is_new_roofing', label: 'New Roofing' },
    // { key: 'garage', label: 'Garage' },
    // { key: 'is_luggage', label: 'Luggage Space' },
    { key: 'status', label: 'Status' },
    // { key: 'latitude', label: 'Latitude' },
    // { key: 'longitude', label: 'Longitude' },
];

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