<template>

    <Head :title="category.name" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
        <FormLayout :title="category.name" :routeName="'categories.update'" :routeParams="{ category: category.id }"
            :submitLabel="'Update ' + category.name"
            :fields="{ categoryName: category.name, categoryDescription: category.description }">
            <template #fields="{ form, errors }">
                <div>
                    <InputLabel for="categoryName" value="Category Name" />
                    <TextInput id="categoryName" v-model="form.categoryName" type="text" class="mt-1 block w-full"
                        autofocus />
                    <InputError class="mt-2" :message="errors.categoryName" />
                </div>

                <div class="mt-4">
                    <InputLabel for="categoryDescription" value="Category Description" />
                    <textarea id="categoryDescription" v-model="form.categoryDescription"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300"></textarea>
                    <InputError class="mt-2" :message="errors.categoryDescription" />
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
    title: 'Category',
    mainPage: 'Pages',
    page: 'Edit',
};
provide('layoutHeader', header)
defineProps({
    category: Object
})
</script>
