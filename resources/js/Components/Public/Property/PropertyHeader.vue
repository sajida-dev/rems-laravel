<template>
    <div>
        <!-- Header Image -->
        <div class="w-full h-[600px] mb-10 bg-cover bg-center"
            :style="{ backgroundImage: 'url(/storage/' + property.image_url + ')' }"></div>
        <!-- Header Text -->
        <div class="text-center flex flex-col space-y-7">
            <span
                class="text-xs uppercase font-semibold tracking-[2px] text-black/40 block flex items-center justify-center gap-2">
                <i class="fas fa-map-marker-alt text-pink-500"></i>
                {{ property.location }}
            </span>
            <h2 class="text-5xl text-gray-700 font-bold mt-2">{{ property.title }}</h2>

            <!-- Agent Info -->
            <div class="flex items-center justify-center gap-3 text-gray-600">
                <i class="fas fa-user-tie text-pink-500"></i>
                <span>Listed by: {{ property.agent?.user?.name || 'Unknown Agent' }}</span>
            </div>

            <div class="mt-4">
                <button v-if="property.type === 'rent' && auth?.user?.role === 'user'" @click="handleRent(property)"
                    class="bg-pink-500 text-white px-6 py-2 rounded hover:bg-pink-600 transition-colors">
                    Apply for Rent
                </button>
                <button v-else-if="property.type === 'buy' && auth?.user?.role === 'user'" @click="handleBuy(property)"
                    class="bg-pink-500 text-white px-6 py-2 rounded hover:bg-pink-600 transition-colors">
                    Request to Buy
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { toast } from 'vue3-toastify';
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    property: {
        type: Object,
        required: true,
    },
})

const auth = usePage().props.auth;

const handleBuy = (property) => {
    router.post('/application', {
        property_id: property.id,
        type: 'buy'
    }, {
        onSuccess: (res) => {
            if (res.props.flash.success) {
                toast.success(res.props.flash.success ?? "Successfully applied for buying the property.");
            }
        },
        onError: (errors) => {
            toast.error("Error applying for buying:", errors);
        }
    });
};

const handleRent = (property) => {
    router.post('/application', {
        property_id: property.id,
        type: 'rent'
    }, {
        onSuccess: (res) => {
            if (res?.props?.flash?.success) {
                toast.success(res?.props?.flash?.success ?? "Successfully applied for renting the property.");
            }
        },
        onError: (errors) => {
            toast.error("Error applying for renting:", errors);
        }
    });
};
</script>

<script>
export default {
    name: "Property Header",
}
</script>
