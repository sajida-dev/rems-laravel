<template>

    <Head title="All Applications" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
        <h2 class="text-2xl font-semibold mb-4">Applications</h2>

        <ServerSideDataTable :columns="columns" :rows="formattedApplications" :selectable="false" :expandable="false"
            :filterable="true" :virtualScroll="false" :hasRowActions="true" :pagination="{
                total: applications.total,
                perPage: applications.per_page,
                currentPage: applications.current_page,
                lastPage: applications.last_page
            }" @update="loadData">
            <template #row-actions="{ row }">
                <div v-if="row.status === 'pending'">
                    <button @click="confirmApplication(row.id)"
                        class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                        Confirm
                    </button>
                </div>
            </template>
        </ServerSideDataTable>
    </div>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3'
import ServerSideDataTable from '@/Components/Dashboard/Common/ServerSideDataTable.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { provide, computed, reactive } from 'vue'
import { toast } from 'vue3-toastify'

defineOptions({ layout: DashboardLayout })

const header = {
    title: 'Applications',
    mainPage: 'Pages',
    page: 'List',
}
provide('layoutHeader', header)

const props = defineProps({
    applications: {
        type: Object,
        required: true
    },
    flash: Object,
})
console.log('props.applications', props.applications)

const formattedApplications = computed(() =>
    props?.applications?.data?.map(app => ({
        id: app.id,
        user: app.user?.name ?? 'N/A',
        property: app.property?.title ?? 'N/A',
        type: app.type.charAt(0).toUpperCase() + app.type.slice(1),
        status: app.status.charAt(0).toUpperCase() + app.status.slice(1),
    }))
)

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'user', label: 'User Name' },
    { key: 'property', label: 'Property Title' },
    { key: 'type', label: 'Type' },
    { key: 'status', label: 'Status' },
]

const confirmApplication = (id) => {
    router.post(route('application.update', id), {}, {
        preserveScroll: true,
        onSuccess: () => toast.success('Application approved'),
        onError: () => toast.error('Failed to confirm'),
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
    router.get(route('application.update'), {
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
</script>
