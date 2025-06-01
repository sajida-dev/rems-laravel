<template>

    <Head :title="`Hiring Request ${hiringRequest?.category?.name}`" />

    <div class="max-w-6xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-semibold mb-6">Hiring Request Details</h1>

        <div class="bg-white rounded shadow p-6 space-y-6">

            <!-- Request Summary -->
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <h2 class="text-sm text-gray-500">Request Type</h2>
                    <p class="text-lg font-medium capitalize">{{ hiringRequest.request_type }}</p>
                </div>

                <div>
                    <h2 class="text-sm text-gray-500">Category</h2>
                    <p class="text-lg font-medium">{{ hiringRequest?.category?.name || '—' }}</p>
                </div>

                <div>
                    <h2 class="text-sm text-gray-500">Location</h2>
                    <p class="text-lg font-medium">{{ hiringRequest.location }}</p>
                </div>
            </div>

            <!-- Budget and Bedrooms -->
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <h2 class="text-sm text-gray-500">Min Budget</h2>
                    <p class="text-lg font-medium">PKR {{ hiringRequest.min_budget }}</p>
                </div>

                <div>
                    <h2 class="text-sm text-gray-500">Max Budget</h2>
                    <p class="text-lg font-medium">PKR {{ hiringRequest.max_budget }}</p>
                </div>

                <div>
                    <h2 class="text-sm text-gray-500">Bedrooms</h2>
                    <p class="text-lg font-medium">{{ hiringRequest.bedrooms }}</p>
                </div>
            </div>

            <!-- Requirements -->
            <div>
                <h2 class="text-sm text-gray-500">Additional Requirements</h2>
                <p class="text-base mt-1">{{ hiringRequest.requirements || 'None specified.' }}</p>
            </div>

            <!-- User Info -->
            <div class="border-t pt-4">
                <h2 class="text-lg font-semibold">Requested By</h2>
                <p class="text-base text-gray-700">
                    {{ hiringRequest.user?.name }} (<a :href="`mailto:${hiringRequest.user?.email}`"
                        class="text-blue-600 underline">{{ hiringRequest.user?.email }}</a>)
                </p>
            </div>

            <!-- Agent Info -->
            <div v-if="hiringRequest.agent" class="border-t pt-4">
                <h2 class="text-lg font-semibold">Assigned Agent</h2>
                <p class="text-base text-gray-700">
                    {{ hiringRequest.agent?.user?.name }} (<a :href="`mailto:${hiringRequest.agent?.user?.email}`"
                        class="text-blue-600 underline">{{ hiringRequest.agent?.user?.email }}</a>)
                </p>
            </div>

            <!-- Optional: Status & Action Buttons -->
            <div class="flex justify-between items-center border-t pt-6">
                <div>
                    <span class="text-sm text-gray-500">Status: </span>
                    <span class="text-base font-semibold capitalize">{{ hiringRequest.status }}</span>
                </div>

                <div v-if="canUpdate">
                    <button @click="rejectRequest" class="mx-1 px-3 py-2 text-white rounded bg-red-600">
                        Reject
                    </button>
                    <button @click="acceptRequest" class="mx-1 px-3 py-2 text-white rounded bg-green-700">
                        Accept
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { usePage } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify'
defineOptions({ layout: DashboardLayout })

const props = defineProps({
    hiringRequest: {
        type: Object,
        required: true,
    },
})

const page = usePage();
const auth = page.props.auth;
const canUpdate = computed(() => {
    return (
        auth?.user?.role === 'agent' &&
        props.hiringRequest.status === 'pending'
    )
})

const acceptRequest = () => {
    router.post(route('hiring-requests.accept', props.hiringRequest.id), {}, {
        preserveScroll: true,
        onSuccess: (response) => {
            toast.success(response.props.flash.success)
        },
        onError: () => {
            toast.error('Something went wrong!')
        },
    })
}

const rejectRequest = () => {
    router.post(route('hiring-requests.reject', props.hiringRequest.id), {}, {
        preserveScroll: true,
        onSuccess: (response) => {
            toast.success(response.props.flash.success)
        },
        onError: () => {
            toast.error('Something went wrong!')
        },
    })
}
</script>

<style scoped>
.btn {
    @apply inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition;
}
</style>
