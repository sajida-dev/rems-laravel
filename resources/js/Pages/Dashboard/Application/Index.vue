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
                <div v-if="row.status === 'Pending' && (user.role == 'admin' || user.role == 'agent')"
                    class="flex space-x-2">
                    <button @click="handleApplication(row.id, 'approved')"
                        :disabled="hasApprovedApplication(row.property_id)" :class="[
                            'px-3 py-1 rounded',
                            hasApprovedApplication(row.property_id)
                                ? 'bg-gray-400 cursor-not-allowed'
                                : 'bg-green-600 hover:bg-green-700 text-white'
                        ]"
                        :title="hasApprovedApplication(row.property_id) ? 'Another application is already approved for this property' : 'Approve'">
                        <i class="fas fa-check"></i> Approve
                    </button>
                    <button @click="handleApplication(row.id, 'rejected')"
                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700" title="Reject">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
                <div v-else-if="row.status === 'Approved' && user.role == 'user'">
                    <button @click="goToPaymentForm(row.property_id, row.type)"
                        class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700" title="Confirm Payment">
                        <i class="fas fa-credit-card"></i> Confirm Payment
                    </button>
                </div>
                <span v-else>{{ row.status }}</span>
            </template>
        </ServerSideDataTable>
    </div>
</template>

<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import ServerSideDataTable from '@/Components/Dashboard/Common/ServerSideDataTable.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { provide, computed, reactive } from 'vue'
import { toast } from 'vue3-toastify'

defineOptions({ layout: DashboardLayout })
const page = usePage();
const user = page.props.auth.user;
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

const hasApprovedApplication = (propertyId) => {
    return props.applications.data.some(app =>
        app.property_id === propertyId &&
        app.status.toLowerCase() === 'approved'
    );
}

const goToPaymentForm = (propertyId, type) => {
    if (!propertyId || !type) {
        toast.error('Missing required payment data.');
        return;
    }

    router.post(
        route('payment.create'),
        {
            property_id: propertyId,
            type: type.toLowerCase(),
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Payment form loaded. Please complete your payment.');
            },
            onError: (errors) => {
                toast.error('Failed to load payment form.');
            },
        }
    );
};

const formattedApplications = computed(() =>
    props?.applications?.data?.map(app => ({
        id: app.id,
        property_id: app.property_id,
        user: app.user?.name ?? 'N/A',
        agent: app?.property?.agent?.user?.name ?? 'N/A',
        property: app.property?.title ?? 'N/A',
        type: app.type.charAt(0).toUpperCase() + app.type.slice(1),
        status: app.status.charAt(0).toUpperCase() + app.status.slice(1),
    }))
)
const columns = [
    { key: 'id', label: 'ID' },
    { key: 'user', label: 'User Name' },
    { key: 'agent', label: 'Agent Name' },
    { key: 'property', label: 'Property Title' },
    { key: 'type', label: 'Type' },
    { key: 'status', label: 'Status' },
]

const handleApplication = (id, status) => {
    router.post(route('application.update', id), { status }, {
        preserveScroll: true,
        onSuccess: (response) => {
            toast.success(response.props.flash.success)
        },
        onError: () => toast.error('Failed to update application'),
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
    router.get(route('application.index'), {
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
