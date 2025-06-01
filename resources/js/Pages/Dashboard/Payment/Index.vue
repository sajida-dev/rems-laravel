<template lang="">
    <Head title="All End Users" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
   <div class="">
    <h2 class="text-2xl font-semibold mb-4">End Users</h2>

    <ServerSideDataTable
      :columns="columns"
      :rows="endUsers.data"
      :selectable="false"
      :expandable="false"
      :filterable="true"
      :perPage="endUsers.per_page"
      :virtualScroll="false"
      createRoute="/end-users/create"
      createLabel="Add End Users"
      :hasRowActions="true"
      :pagination="{
        total: endUsers.total,
        perPage: endUsers.per_page,
        currentPage: endUsers.current_page,
        lastPage: endUsers.last_page
      }"
      @update="loadData"
    >

    <template #row-actions="{ row }">
  <RowActions
    :row="row"
    :viewRoute="row => route('end-users.show', row.id)"
    :editRoute="row => route('end-users.edit', row.id)"
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
    title: 'End Users',
    mainPage: 'Pages',
    page: 'Create',
};
provide('layoutHeader', header)
const props = defineProps({
    endUsers: Object,
})

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'contact', label: 'Contact No' },
    { key: 'role', label: 'Role' },
]

const deleteData = (row) => {

    router.delete(route('end-users.destroy', row.id), {
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
    router.get(route('end-users.index'), {
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