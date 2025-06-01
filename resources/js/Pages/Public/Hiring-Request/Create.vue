<template>

    <Head :title="'Hiring Request'" />

    <div class="px-6 sm:px-16 py-10">
        <HeroSection variant="page" :heading="'Hiring Request - Request Agent to Hire'" :breadcrumbs="[
            { label: 'Home', link: '/' },
            { label: 'Hiring Request' }
        ]" />

        <FormLayout title="New Hiring Request" :routeName="'hiring-requests.store'" :fields="form"
            class="bg-white p-8 rounded shadow-md">
            <template #fields="{ form, errors }">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Request Type -->
                    <div>
                        <InputLabel for="request_type" value="Request Type" />
                        <select id="request_type" v-model="form.request_type"
                            class="mt-1 text-sm block w-full border rounded p-2">
                            <option disabled value=""> Select Request Type </option>
                            <option value="rent">Rent</option>
                            <option value="buy">Buy</option>
                            <option value="sell">Sell</option>
                        </select>
                        <InputError class="mt-2" :message="errors.request_type" />
                    </div>

                    <!-- Category Dropdown -->
                    <div>
                        <InputLabel for="category_id" value="Category" />
                        <select id="category_id" v-model="form.category_id"
                            class="mt-1 text-sm block w-full border rounded p-2">
                            <option disabled value=""> Select Category </option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <InputError class="mt-2" :message="errors.category_id" />
                    </div>

                    <!-- Location -->
                    <div>
                        <InputLabel for="location" value="Location" />
                        <TextInput id="location" v-model="form.location" type="text" class="mt-1 text-sm block w-full"
                            placeholder="City, Area" />
                        <InputError class="mt-2" :message="errors.location" />
                    </div>

                    <!-- Min Budget -->
                    <div>
                        <InputLabel for="min_budget" value="Minimum Budget" />
                        <TextInput id="min_budget" v-model="form.min_budget" type="number" step="0.01"
                            class="mt-1 text-sm block w-full" placeholder="0.00" />
                        <InputError class="mt-2" :message="errors.min_budget" />
                    </div>

                    <!-- Max Budget -->
                    <div>
                        <InputLabel for="max_budget" value="Maximum Budget" />
                        <TextInput id="max_budget" v-model="form.max_budget" type="number" step="0.01"
                            class="mt-1 text-sm block w-full" placeholder="0.00" />
                        <InputError class="mt-2" :message="errors.max_budget" />
                    </div>

                    <!-- Bedrooms -->
                    <div>
                        <InputLabel for="bedrooms" value="Bedrooms" />
                        <TextInput id="bedrooms" v-model="form.bedrooms" type="number" class="mt-1 text-sm block w-full"
                            placeholder="e.g. 2" />
                        <InputError class="mt-2" :message="errors.bedrooms" />
                    </div>

                    <!-- Requirements -->
                    <div class="md:col-span-3">
                        <InputLabel for="requirements" value="Additional Requirements" />
                        <textarea id="requirements" v-model="form.requirements"
                            class="mt-1 text-sm block w-full border rounded p-2" rows="4"
                            placeholder="Specify any additional requirements..."></textarea>
                        <InputError class="mt-2" :message="errors.requirements" />
                    </div>
                </div>
            </template>
        </FormLayout>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import HeroSection from '@/Components/Public/Common/HeroSection.vue'
import FormLayout from '@/Components/Dashboard/Common/FormLayout.vue'
import InputLabel from '@/Components/Default/InputLabel.vue'
import TextInput from '@/Components/Default/TextInput.vue'
import InputError from '@/Components/Default/InputError.vue'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import axios from 'axios'

defineOptions({ layout: PublicLayout })
const props = defineProps({
    categories: {
        type: Object,
        required: true
    },
    agent_id: {
        required: true
    }
})

// Form fields to be submitted
const form = ref({
    request_type: '',
    category_id: '',
    location: '',
    min_budget: '',
    max_budget: '',
    bedrooms: '',
    requirements: '',
    agent_id: props.agent_id,

})

</script>
