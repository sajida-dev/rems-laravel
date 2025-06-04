<template lang="">
    <Head title="Payments" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
        <h2 class="text-2xl font-semibold mb-4">Payments</h2>

        <StatsCards :cards="statsCards" />

        <ServerSideDataTable
            v-if="payments?.data"
            :columns="columns"
            :rows="payments.data"
            :selectable="false"
            :expandable="true"
            :filterable="true"
            :perPage="payments?.per_page || 10"
            :virtualScroll="false"
            :hasRowActions="true"
            :viewRoute="row => route('payment.show', row.id)"
            :pagination="{
                total: payments?.total || 0,
                perPage: payments?.per_page || 10,
                currentPage: payments?.current_page || 1,
                lastPage: payments?.last_page || 1
            }"
            @update="loadData"
        >
            <!-- Payment Status Badge -->
            <template #cell-status="{ value }">
                <div class="flex items-center space-x-2">
                    <span :class="[
                        'px-2 py-1 rounded-full text-xs font-medium',
                        {
                            'bg-green-100 text-green-800': value === 'completed',
                            'bg-yellow-100 text-yellow-800': value === 'pending',
                            'bg-red-100 text-red-800': value === 'failed',
                            'bg-blue-100 text-blue-800': value === 'processing'
                        }
                    ]">
                        <i :class="getStatusIcon(value)" class="mr-1"></i>
                        {{ value.charAt(0).toUpperCase() + value.slice(1) }}
                    </span>
                </div>
            </template>

<!-- Payment Amount -->
<template #cell-amount="{ value }">
                <span class="font-medium">${{ value.toFixed(2) }}</span>
            </template>

<!-- Payment Date -->
<template #cell-created_at="{ value }">
                {{ formatDate(value) }}
            </template>

<!-- Expandable Row Content -->
<template #expanded-row="{ row }">
                <div class="p-4 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Transaction Details</h4>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Transaction ID:</span> {{ row.transaction?.id }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Property:</span> {{ row.transaction?.property?.title }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Date:</span> {{ formatDate(row.created_at) }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Payment Details</h4>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Method:</span> {{ row.payment_method }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Reference:</span> {{ row.payment_reference }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Amount:</span> ${{ row.amount.toFixed(2) }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Additional Information</h4>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Status:</span>
                                    <span :class="getStatusClass(row.status)">{{ row.status }}</span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Last Updated:</span> {{ formatDate(row.updated_at) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

<!-- Add Payment Method Icon -->
<template #cell-payment_method="{ value }">
                <div class="flex items-center space-x-2">
                    <i :class="getPaymentMethodIcon(value)" class="text-gray-500"></i>
                    <span>{{ value }}</span>
                </div>
            </template>

<template #row-actions="{ row }">
                <RowActions
                    :row="row"
                    :viewRoute="row => route('payment.show', row.id)"
                />
            </template>
</ServerSideDataTable>

<!-- Loading State -->
<div v-else class="flex justify-center items-center py-8">
    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-pink-500"></div>
</div>
</div>
</template>
<script setup>
import { router } from '@inertiajs/vue3'
import RowActions from '@/Components/Dashboard/Common/RowActions.vue'
import ServerSideDataTable from '@/Components/Dashboard/Common/ServerSideDataTable.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3'
import { provide, reactive, ref, computed } from 'vue'
import { toast } from 'vue3-toastify'
import { format } from 'date-fns'
import StatsCards from '@/Components/Dashboard/Common/StatsCards.vue'

defineOptions({ layout: DashboardLayout })

const header = {
    title: 'Payments',
    mainPage: 'Dashboard',
    page: 'Payments',
};
provide('layoutHeader', header)
const props = defineProps({
    payments: {
        type: Object,
        default: () => ({
            data: [],
            total: 0,
            per_page: 10,
            current_page: 1,
            last_page: 1
        })
    }
})

const columns = [
    { key: 'id', label: 'ID', sortable: true },
    { key: 'transaction_id', label: 'Transaction ID', sortable: true },
    { key: 'amount', label: 'Amount', sortable: true },
    { key: 'payment_method', label: 'Payment Method', sortable: true },
    { key: 'status', label: 'Status', sortable: true },
    { key: 'created_at', label: 'Date', sortable: true },
]

const formatDate = (date) => {
    return format(new Date(date), 'MMM d, yyyy h:mm a')
}

const tableState = reactive({
    filters: { global: '' },
    sortBy: 'id',
    sortDesc: false,
    page: 1,
    perPage: 10,
})

const loadData = (options = {}) => {
    try {
        router.get(route('payment.index'), {
            global: tableState.filters.global,
            sortBy: tableState.sortBy,
            sortDesc: tableState.sortDesc,
            page: options.page ?? tableState.page,
            perPage: tableState.perPage,
        }, {
            preserveState: true,
            replace: true,
            onError: (errors) => {
                toast.error('Failed to load payments. Please try again.')
            }
        })
    } catch (error) {
        console.error('Error loading data:', error)
        toast.error('Failed to load payments. Please try again.')
    }
}
const updateTableState = ({ filters, sortBy, sortDesc, page, perPage }) => {
    tableState.filters = filters
    tableState.sortBy = sortBy
    tableState.sortDesc = sortDesc
    tableState.page = page
    tableState.perPage = perPage

    loadData()
}

const searchQuery = ref('')
const selectedStatus = ref('all')
const dateRange = ref({
    start: null,
    end: null
})

const statusOptions = [
    { value: 'all', label: 'All Status' },
    { value: 'completed', label: 'Completed' },
    { value: 'pending', label: 'Pending' },
    { value: 'failed', label: 'Failed' },
    { value: 'processing', label: 'Processing' }
]

const getTotalAmount = computed(() => {
    if (!props.payments?.data) return 0
    return props.payments.data.reduce((sum, payment) => {
        return sum + (payment.status === 'completed' ? payment.amount : 0)
    }, 0)
})

const getStatusCount = computed(() => {
    const counts = {
        completed: 0,
        pending: 0,
        failed: 0,
        processing: 0
    }

    if (!props.payments?.data) return counts

    props.payments.data.forEach(payment => {
        if (payment.status) {
            counts[payment.status]++
        }
    })
    return counts
})

const getPaymentMethodIcon = (method) => {
    const icons = {
        'credit_card': 'fas fa-credit-card',
        'bank_transfer': 'fas fa-university',
        'paypal': 'fab fa-paypal',
        'cash': 'fas fa-money-bill',
        'default': 'fas fa-money-bill'
    }
    return icons[method.toLowerCase()] || icons.default
}

const getStatusIcon = (status) => {
    const icons = {
        'completed': 'fas fa-check-circle',
        'pending': 'fas fa-clock',
        'failed': 'fas fa-times-circle',
        'processing': 'fas fa-spinner fa-spin'
    }
    return icons[status] || 'fas fa-circle'
}

const getStatusClass = (status) => {
    const classes = {
        'completed': 'text-green-600',
        'pending': 'text-yellow-600',
        'failed': 'text-red-600',
        'processing': 'text-blue-600'
    }
    return classes[status] || 'text-gray-600'
}

const handleSearch = () => {
    tableState.filters.global = searchQuery.value
    loadData()
}

const handleStatusChange = () => {
    if (selectedStatus.value === 'all') {
        tableState.filters.status = null
    } else {
        tableState.filters.status = selectedStatus.value
    }
    loadData()
}

const statsCards = computed(() => [
    {
        title: 'Total Completed',
        value: `$${getTotalAmount.value.toFixed(2)}`,
        valueColor: 'text-green-600'
    },
    {
        title: 'Completed',
        value: getStatusCount.value.completed,
        valueColor: 'text-green-600'
    },
    {
        title: 'Pending',
        value: getStatusCount.value.pending,
        valueColor: 'text-yellow-600'
    },
    {
        title: 'Failed',
        value: getStatusCount.value.failed,
        valueColor: 'text-red-600'
    }
]);
</script>

<style scoped>
/* Add any custom styles here */
</style>