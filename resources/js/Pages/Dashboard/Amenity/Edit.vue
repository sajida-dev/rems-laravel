<template>

    <Head title="Edit Amenity" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
        <FormLayout title="Update Amenity" :method="'put'" :routeName="'amenities.update'"
            :routeParams="{ amenity: amenity.id }"
            :fields="{ amenityName: amenity.name, amenityDescription: amenity.description }">
            <template #fields="{ form, errors }">
                <div>
                    <InputLabel for="amenityName" value="Amenity Name" />
                    <TextInput id="amenityName" v-model="form.amenityName" type="text" class="mt-1 block w-full"
                        autofocus />
                    <InputError class="mt-2" :message="errors.amenityName" />
                </div>

                <div class="mt-4">
                    <InputLabel for="amenityDescription" value="Amenity Description" />
                    <textarea id="amenityDescription" v-model="form.amenityDescription"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300"></textarea>
                    <InputError class="mt-2" :message="errors.amenityDescription" />
                </div>
            </template>
        </FormLayout>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import { provide } from 'vue'
import FormLayout from '@/Components/Dashboard/Common/FormLayout.vue'
import InputLabel from '@/Components/Default/InputLabel.vue'
import TextInput from '@/Components/Default/TextInput.vue'
import InputError from '@/Components/Default/InputError.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineOptions({ layout: DashboardLayout })

const header = {
    title: 'Amenity',
    mainPage: 'Pages',
    page: 'Edit',
};
provide('layoutHeader', header)
defineProps({
    amenity: Object
})
</script>
