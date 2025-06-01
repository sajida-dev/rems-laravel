<template>
    <div>
        <!-- Header Image -->
        <div class="w-full h-[600px] mb-10 bg-cover bg-center"
            :style="{ backgroundImage: 'url(/storage/' + property.image_url + ')' }"></div>
        <!-- Header Text -->
        <div class="text-center flex flex-col space-y-7">
            <span class="text-xs uppercase font-semibold tracking-[2px] text-black/40 block">
                {{ property.location }}
            </span>
            <h2 class="text-5xl text-gray-700 font-bold mt-2">{{ property.title }}</h2>
            <div class="mt-4">
                <Button :property="property" @buy="handleBuy(property)" @rent="handleRent(property)" />
            </div>
        </div>
    </div>
</template>

<script setup>
import Button from '@Components/Public/Property/Button.vue';
import { toast } from 'vue3-toastify';
import { router } from '@inertiajs/vue3';
const props = defineProps({
    property: {
        type: Object,
        required: true,
    },
})

const handleBuy = (property) => {

    router.post('/application', {
        property_id: property.id,
        type: 'buy'
    }, {
        onSuccess: (res) => {
            if (res.props.flash.success) {
                toast.success("Successfully applied for buying the property.");
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
            if (res.props.flash.success) {
                toast.success("Successfully applied for renting the property.");
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
    components: {
        Button,
    }
}
</script>
