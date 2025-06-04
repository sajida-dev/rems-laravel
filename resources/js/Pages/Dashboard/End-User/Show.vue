<template>

    <Head :title="`User Details - ${user.name}`" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold mb-4">User Details</h2>

            <!-- User Info Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-full overflow-hidden">
                        <img v-if="user.profile_photo_path" :src="'/storage/' + user.profile_photo_path"
                            :alt="user.name" class="w-full h-full object-cover" />
                        <div v-else class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-user text-2xl text-gray-500"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold">{{ user.name }}</h3>
                        <p class="text-gray-600">{{ user.email }}</p>
                        <p class="text-sm text-gray-500">Joined {{ formatDate(user.created_at) }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats Overview -->
            <StatsCards :cards="statsCards" />

            <!-- Tabs -->
            <div class="mt-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id" :class="[
                            'py-4 px-1 border-b-2 font-medium text-sm',
                            activeTab === tab.id
                                ? 'border-pink-500 text-pink-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                        ]">
                            {{ tab.name }}
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="mt-6">
                    <!-- Applications Tab -->
                    <div v-if="activeTab === 'applications'">
                        <ServerSideDataTable v-if="applications" :columns="applicationColumns" :rows="applications.data"
                            :selectable="false" :expandable="true" :filterable="true" :perPage="applications.per_page"
                            :virtualScroll="false" :hasRowActions="true" :pagination="{
                                total: applications.total,
                                perPage: applications.per_page,
                                currentPage: applications.current_page,
                                lastPage: applications.last_page
                            }" @update="loadApplications">
                            <template #cell-status="{ value }">
                                <span :class="getStatusClass(value)">
                                    {{ value.charAt(0).toUpperCase() + value.slice(1) }}
                                </span>
                            </template>
                        </ServerSideDataTable>
                        <div v-else class="text-center py-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mx-auto"></div>
                            <p class="mt-2 text-gray-600">Loading applications...</p>
                        </div>
                    </div>

                    <!-- Hiring Requests Tab -->
                    <div v-if="activeTab === 'hiring-requests'">
                        <ServerSideDataTable v-if="hiringRequests" :columns="hiringRequestColumns"
                            :rows="hiringRequests.data" :selectable="false" :expandable="true" :filterable="true"
                            :perPage="hiringRequests.per_page" :virtualScroll="false" :hasRowActions="true" :pagination="{
                                total: hiringRequests.total,
                                perPage: hiringRequests.per_page,
                                currentPage: hiringRequests.current_page,
                                lastPage: hiringRequests.last_page
                            }" @update="loadHiringRequests">
                            <template #cell-status="{ value }">
                                <span :class="getStatusClass(value)">
                                    {{ value.charAt(0).toUpperCase() + value.slice(1) }}
                                </span>
                            </template>
                        </ServerSideDataTable>
                        <div v-else class="text-center py-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mx-auto"></div>
                            <p class="mt-2 text-gray-600">Loading hiring requests...</p>
                        </div>
                    </div>

                    <!-- Transactions Tab -->
                    <div v-if="activeTab === 'transactions'">
                        <ServerSideDataTable v-if="transactions" :columns="transactionColumns" :rows="transactions.data"
                            :selectable="false" :expandable="true" :filterable="true" :perPage="transactions.per_page"
                            :virtualScroll="false" :hasRowActions="true" :pagination="{
                                total: transactions.total,
                                perPage: transactions.per_page,
                                currentPage: transactions.current_page,
                                lastPage: transactions.last_page
                            }" @update="loadTransactions">
                            <template #cell-amount="{ value }">
                                ${{ value.toFixed(2) }}
                            </template>
                            <template #cell-status="{ value }">
                                <span :class="getStatusClass(value)">
                                    {{ value.charAt(0).toUpperCase() + value.slice(1) }}
                                </span>
                            </template>
                        </ServerSideDataTable>
                        <div v-else class="text-center py-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mx-auto"></div>
                            <p class="mt-2 text-gray-600">Loading transactions...</p>
                        </div>
                    </div>

                    <!-- Bookmarks Tab -->
                    <div v-if="activeTab === 'bookmarks'">
                        <ServerSideDataTable v-if="bookmarks" :columns="bookmarkColumns" :rows="bookmarks.data"
                            :selectable="false" :expandable="true" :filterable="true" :perPage="bookmarks.per_page"
                            :virtualScroll="false" :hasRowActions="true" :pagination="{
                                total: bookmarks.total,
                                perPage: bookmarks.per_page,
                                currentPage: bookmarks.current_page,
                                lastPage: bookmarks.last_page
                            }" @update="loadBookmarks" />
                        <div v-else class="text-center py-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mx-auto"></div>
                            <p class="mt-2 text-gray-600">Loading bookmarks...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import { format } from 'date-fns'
import ServerSideDataTable from '@/Components/Dashboard/Common/ServerSideDataTable.vue'
import StatsCards from '@/Components/Dashboard/Common/StatsCards.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
    user: Object,
    applications: Object,
    hiringRequests: Object,
    transactions: Object,
    bookmarks: Object,
    counts: Object
})

const activeTab = ref('applications')

const tabs = [
    { id: 'applications', name: 'Applications' },
    { id: 'hiring-requests', name: 'Hiring Requests' },
    { id: 'transactions', name: 'Transactions' },
    { id: 'bookmarks', name: 'Bookmarks' }
]

const formatDate = (date) => {
    return format(new Date(date), 'MMM d, yyyy')
}

const statsCards = computed(() => [
    {
        title: 'Total Applications',
        value: props.counts.applications,
        valueColor: 'text-blue-600'
    },
    {
        title: 'Hiring Requests',
        value: props.counts.hiringRequests,
        valueColor: 'text-green-600'
    },
    {
        title: 'Transactions',
        value: props.counts.transactions,
        valueColor: 'text-purple-600'
    },
    {
        title: 'Bookmarks',
        value: props.counts.bookmarks,
        valueColor: 'text-yellow-600'
    }
])

const applicationColumns = [
    { key: 'id', label: 'ID' },
    { key: 'property.title', label: 'Property' },
    { key: 'type', label: 'Type' },
    { key: 'status', label: 'Status' },
    { key: 'created_at', label: 'Date' }
]

const hiringRequestColumns = [
    { key: 'id', label: 'ID' },
    { key: 'request_type', label: 'Type' },
    { key: 'location', label: 'Location' },
    { key: 'status', label: 'Status' },
    { key: 'created_at', label: 'Date' }
]

const transactionColumns = [
    { key: 'id', label: 'ID' },
    { key: 'amount', label: 'Amount' },
    { key: 'payment_method', label: 'Method' },
    { key: 'status', label: 'Status' },
    { key: 'created_at', label: 'Date' }
]

const bookmarkColumns = [
    { key: 'id', label: 'ID' },
    { key: 'property.title', label: 'Property' },
    { key: 'property.price', label: 'Price' },
    { key: 'created_at', label: 'Date' }
]

const getStatusClass = (status) => {
    const classes = {
        'pending': 'text-yellow-600',
        'approved': 'text-green-600',
        'rejected': 'text-red-600',
        'completed': 'text-green-600',
        'failed': 'text-red-600',
        'processing': 'text-blue-600'
    }
    return classes[status] || 'text-gray-600'
}

const loadApplications = (options = {}) => {
    router.get(route('end-users.show', props.user.id), {
        ...options,
        tab: 'applications',
        preserveState: true,
        preserveScroll: true,
        only: ['applications']
    })
}

const loadHiringRequests = (options = {}) => {
    router.get(route('end-users.show', props.user.id), {
        ...options,
        tab: 'hiring-requests',
        preserveState: true,
        preserveScroll: true,
        only: ['hiringRequests']
    })
}

const loadTransactions = (options = {}) => {
    router.get(route('end-users.show', props.user.id), {
        ...options,
        tab: 'transactions',
        preserveState: true,
        preserveScroll: true,
        only: ['transactions']
    })
}

const loadBookmarks = (options = {}) => {
    router.get(route('end-users.show', props.user.id), {
        ...options,
        tab: 'bookmarks',
        preserveState: true,
        preserveScroll: true,
        only: ['bookmarks']
    })
}

// Watch for tab changes to load data
watch(activeTab, (newTab) => {
    switch (newTab) {
        case 'applications':
            loadApplications()
            break
        case 'hiring-requests':
            loadHiringRequests()
            break
        case 'transactions':
            loadTransactions()
            break
        case 'bookmarks':
            loadBookmarks()
            break
    }
})
</script>
<style></style>