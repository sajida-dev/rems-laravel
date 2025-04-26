<template lang="">
    <Head title="All Agents" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
   <div class="">
    <h2 class="text-2xl font-semibold mb-4">Agents</h2>

    <ServerSideDataTable
      :columns="columns"
      :rows="agents.data"
      :selectable="false"
      :expandable="false"
      :filterable="true"
      :perPage="agents.per_page"
      :virtualScroll="false"
      createRoute="/agents/create"
      createLabel="Add Agents"
      :hasRowActions="true"
      :pagination="{
        total: agents.total,
        perPage: agents.per_page,
        currentPage: agents.current_page,
        lastPage: agents.last_page
      }"
      @update="loadData"
    >

    <template #row-actions="{ row }">
  <RowActions
    :row="row"
    :editRoute="row => route('agents.edit', row.id)"
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
    title: 'Agents',
    mainPage: 'Pages',
    page: 'Create',
};
provide('layoutHeader', header)
const props = defineProps({
    agents: Object,
})

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'agency', label: 'Agency' },
    { key: 'licence', label: 'Licence' },
    { key: 'experience', label: 'Experience' },
    { key: 'status', label: 'Status' },
]

const deleteData = (row) => {

    router.delete(route('agents.destroy', row.id), {
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


const tableState = reactive({
    filters: { global: '' },
    sortBy: 'id',
    sortDesc: false,
    page: 1,
    perPage: 10,
})



const loadData = (options = {}) => {
    router.get(route('agents.index'), {
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