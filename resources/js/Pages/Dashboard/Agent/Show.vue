<template>

    <Head :title="`Agent Details - ${agent.user.name}`" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold mb-4">Agent Details</h2>

            <!-- Agent Info Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-3 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-48 h-48 rounded-full overflow-hidden">
                        <img v-if="agent.user.profile_photo_path" :src="'/storage/' + agent.user.profile_photo_path"
                            :alt="agent.user.name" class="w-full h-full object-cover" />
                        <div v-else class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-user text-2xl text-gray-500"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold">{{ agent.user.name }}</h3>
                        <p class="text-gray-600">{{ agent.user.email }}</p>
                        <p class="text-sm text-gray-500">Agency: {{ agent.agency }}</p>
                        <p class="text-sm text-gray-500">Experience: {{ agent.experience }} years</p>
                        <p class="text-sm text-gray-500">Contact: {{ agent.user.contact }}</p>
                        <p class="text-sm text-gray-500">Bio: {{ agent.bio }}</p>
                        <div v-if="agentStatus === 0" class="mt-2">
                            <button @click="approveAgent"
                                class="bg-green-700 text-white py-1 px-3 rounded text-sm hover:bg-green-800">
                                Approve Agent
                            </button>
                        </div>
                        <div v-if="agentStatus === 1" class="mt-2">
                            <span class="text-green-600 font-bold text-sm">Approved</span>
                        </div>
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
                    <!-- Properties Tab -->
                    <div v-if="activeTab === 'properties'">
                        <ServerSideDataTable v-if="properties" :columns="propertyColumns" :rows="properties.data"
                            :selectable="false" :expandable="true" :filterable="true" :perPage="properties.per_page"
                            :virtualScroll="false" :hasRowActions="true" :pagination="{
                                total: properties.total,
                                perPage: properties.per_page,
                                currentPage: properties.current_page,
                                lastPage: properties.last_page
                            }" @update="loadProperties">
                            <template #cell-status="{ value }">
                                <span :class="getStatusClass(value)">
                                    {{ value.charAt(0).toUpperCase() + value.slice(1) }}
                                </span>
                            </template>
                        </ServerSideDataTable>
                        <div v-else class="text-center py-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mx-auto"></div>
                            <p class="mt-2 text-gray-600">Loading properties...</p>
                        </div>
                    </div>

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
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify'
import ServerSideDataTable from '@/Components/Dashboard/Common/ServerSideDataTable.vue'
import StatsCards from '@/Components/Dashboard/Common/StatsCards.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
    agent: {
        type: Object,
        required: true
    },
    properties: {
        type: Object,
        default: () => ({ data: [], total: 0, per_page: 10, current_page: 1, last_page: 1 })
    },
    applications: {
        type: Object,
        default: () => ({ data: [], total: 0, per_page: 10, current_page: 1, last_page: 1 })
    },
    hiringRequests: {
        type: Object,
        default: () => ({ data: [], total: 0, per_page: 10, current_page: 1, last_page: 1 })
    },
    transactions: {
        type: Object,
        default: () => ({ data: [], total: 0, per_page: 10, current_page: 1, last_page: 1 })
    },
    counts: {
        type: Object,
        default: () => ({
            properties: 0,
            applications: 0,
            hiringRequests: 0,
            transactions: 0
        })
    }
})

const activeTab = ref('properties')
const page = usePage()
const agentStatus = ref(props.agent.status)

const tabs = [
    { id: 'properties', name: 'Properties' },
    { id: 'applications', name: 'Applications' },
    { id: 'hiring-requests', name: 'Hiring Requests' },
    { id: 'transactions', name: 'Transactions' }
]

const statsCards = computed(() => [
    {
        title: 'Total Properties',
        value: props.counts?.properties || 0,
        valueColor: 'text-blue-600'
    },
    {
        title: 'Applications',
        value: props.counts?.applications || 0,
        valueColor: 'text-green-600'
    },
    {
        title: 'Hiring Requests',
        value: props.counts?.hiringRequests || 0,
        valueColor: 'text-yellow-600'
    },
    {
        title: 'Transactions',
        value: props.counts?.transactions || 0,
        valueColor: 'text-purple-600'
    }
])

const propertyColumns = [
    { key: 'id', label: 'ID' },
    { key: 'title', label: 'Title' },
    { key: 'price', label: 'Price' },
    { key: 'status', label: 'Status' },
    { key: 'created_at', label: 'Date' }
]

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

const loadProperties = (options = {}) => {
    router.get(route('agents.show', props.agent.id), {
        ...options,
        tab: 'properties',
        preserveState: true,
        preserveScroll: true,
        only: ['properties']
    })
}

const loadApplications = (options = {}) => {
    router.get(route('agents.show', props.agent.id), {
        ...options,
        tab: 'applications',
        preserveState: true,
        preserveScroll: true,
        only: ['applications']
    })
}

const loadHiringRequests = (options = {}) => {
    router.get(route('agents.show', props.agent.id), {
        ...options,
        tab: 'hiring-requests',
        preserveState: true,
        preserveScroll: true,
        only: ['hiringRequests']
    })
}

const loadTransactions = (options = {}) => {
    router.get(route('agents.show', props.agent.id), {
        ...options,
        tab: 'transactions',
        preserveState: true,
        preserveScroll: true,
        only: ['transactions']
    })
}

// Watch for tab changes to load data
watch(activeTab, (newTab) => {
    switch (newTab) {
        case 'properties':
            loadProperties()
            break
        case 'applications':
            loadApplications()
            break
        case 'hiring-requests':
            loadHiringRequests()
            break
        case 'transactions':
            loadTransactions()
            break
    }
})

const approveAgent = () => {
    router.post(route('agents.approve', props.agent.id), {}, {
        preserveScroll: true,
        onSuccess: (response) => {
            agentStatus.value = 1
            toast.success(response.props.flash.success || "Agent approved successfully!")
        },
        onError: () => {
            toast.error(page.props.flash.error || "Something went wrong!")
        }
    })
}
</script>

<style scoped>
.container {
    max-width: 1200px;
}
</style>
