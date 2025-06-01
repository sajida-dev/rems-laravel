<template>

    <Head title="Hiring Requests" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
        <div>
            <h2 class="text-2xl font-semibold mb-4">Hiring Requests</h2>

            <ServerSideDataTable :columns="columns" :rows="updatedHiringRequests" :selectable="false"
                :expandable="false" :filterable="true" :perPage="hiringRequests?.per_page" :virtualScroll="false"
                :hasRowActions="true" :pagination="{
                    total: hiringRequests?.total,
                    perPage: hiringRequests?.per_page,
                    currentPage: hiringRequests?.current_page,
                    lastPage: hiringRequests?.last_page
                }" @update="loadData">
                <template #row-actions="{ row }">
                    <RowActions :row="row" :viewRoute="row => route('hiring-requests.show', row.id)" />
                </template>
            </ServerSideDataTable>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import RowActions from '@/Components/Dashboard/Common/RowActions.vue'
import ServerSideDataTable from '@/Components/Dashboard/Common/ServerSideDataTable.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Head } from '@inertiajs/vue3'
import { provide, reactive, computed } from 'vue'
import { toast } from 'vue3-toastify'

defineOptions({ layout: DashboardLayout })

const header = {
    title: 'Hiring Requests',
    mainPage: 'Pages',
    page: 'Index',
}
provide('layoutHeader', header)

const props = defineProps({
    hiringRequests: Object,
})
const updatedHiringRequests = computed(() =>
    props.hiringRequests.data.map(request => ({
        ...request,
        user_name: request.user?.name || 'N/A',
        agent_name: request.agent?.user?.name || 'N/A',
    }))
);
const columns = [
    { key: 'id', label: 'ID' },
    { key: 'user_name', label: 'User' },
    { key: 'agent_name', label: 'Agent' },
    { key: 'request_type', label: 'Type' },
    { key: 'location', label: 'Location' },
    { key: 'min_budget', label: 'Min Budget' },
    { key: 'max_budget', label: 'Max Budget' },
    { key: 'bedrooms', label: 'Bedrooms' },
    { key: 'status', label: 'Status' },
]

const deleteData = (row) => {
    router.delete(route('hiring-requests.destroy', row.id), {
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
        onError: () => {
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
    router.get(route('hiring-requests.index'), {
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
