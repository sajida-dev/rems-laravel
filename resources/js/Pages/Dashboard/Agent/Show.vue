<!-- <template>

    <Head title="Details Agent" />

</template>
<script setup>
import { Head } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
defineOptions({ layout: DashboardLayout })
</script>
<style></style> -->


<template>

    <Head :title="agent.user.name" />

    <div class="container mx-auto py-8 px-4">
        <!-- Agent Card -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6 flex flex-col md:flex-row items-center gap-6">
            <img :src="profile_pic" :alt="agent.user.name"
                class="w-36 h-36 object-cover rounded-full border border-gray-400" />
            <div>
                <h2 class="text-2xl font-bold">{{ agent.user.name }}</h2>
                <p class="text-gray-700"><strong>Agency:</strong> {{ agent.agency }}</p>
                <p class="text-gray-700"><strong>Experience:</strong> {{ agent.experience }} years</p>
                <p class="text-gray-700"><strong>Contact:</strong> {{ agent.user.contact }}</p>
                <p class="text-gray-700"><strong>Email:</strong> {{ agent.user.email }}</p>
                <p class="text-gray-700"><strong>Bio:</strong> {{ agent.bio }}</p>

                <div v-if="agent.status === 0" class="mt-4">
                    <button @click="approveAgent" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                        Approve Agent
                    </button>
                </div>
                <div v-if="agent.status === 1" class="mt-4">
                    <span class="text-green-600 font-bold">Approved</span>
                </div>
            </div>

        </div>

        <!-- Agent's Properties -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold border-b border-gray-300 pb-2 mb-4">
                Properties by {{ agent.user.name }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-if="properties?.length" v-for="property in properties" :key="property.id"
                    class="bg-white rounded-lg shadow hover:shadow-lg transition">
                    <img :src="`/storage/${property.image_url}`" alt="Property Cover"
                        class="w-full h-48 object-cover rounded-t-lg" />
                    <div class="p-4">
                        <h4 class="text-lg font-semibold">{{ property.title }}</h4>
                        <p class="text-sm text-gray-600"><strong>Location:</strong> {{ property.location }}</p>
                        <p class="text-sm text-gray-600">
                            <strong>Rent Price:</strong> ${{ Number(property.rent_price).toFixed(2) }} / mo
                        </p>
                        <p class="text-sm text-gray-600">
                            <strong>Purchase Price:</strong> ${{ Number(property.purchase_price).toFixed(2) }} / mo
                        </p>
                        <p class="text-sm text-gray-600"><strong>Category:</strong> {{ property.category_name }}</p>
                        <p v-if="property.amenities" class="text-sm text-gray-500">
                            <strong>Amenities:</strong> {{ property.amenities }}
                        </p>
                    </div>
                </div>
                <p v-else class="col-span-3 text-gray-500">No properties found for this agent.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, ref } from 'vue';
import { Head } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { toast } from 'vue3-toastify'

defineOptions({ layout: DashboardLayout })
const props = defineProps({
    agent: Object,
    properties: Array
});
const profile_pic = ref(`/storage/${props.agent.user.profile_photo_path}`)
const approveAgent = () => {
    Inertia.post(route('agents.approve', props.agent.id), {}, {
        preserveScroll: true,
        only: [],
        onSuccess: () => {
            this.agent.status = 1;
            toast.success($page.props.flash.success || "Agent approved successfully!");
        },
        onError: () => {
            toast.error($page.props.flash.error || "Agent is already approved or something went wrong.");
        }
    });
}
</script>

<style scoped>
.container {
    max-width: 1200px;
}
</style>
