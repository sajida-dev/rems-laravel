<template lang="">
    <Head title="All Amenities" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
   <div class="">
    <h2 class="text-2xl font-semibold mb-4">Amenities</h2>

    <ServerSideDataTable
      :columns="columns"
      :rows="amenities.data"
      :selectable="false"
      :expandable="false"
      :filterable="true"
      :perPage="amenities.per_page"
      :virtualScroll="false"
      createRoute="/amenities/create"
      createLabel="Add Amenity"
      :hasRowActions="true"
      @update="loadAmenities"
    >

    <template #row-actions="{ row }">
  <RowActions
    :row="row"
    :editRoute="row => route('amenities.edit', row.id)"
    :deleteHandler="deleteAmenity"
  />
</template>
</ServerSideDataTable>

</div>
</div>
</template>
<script setup>
import { router } from '@inertiajs/vue3'
import RowActions from '@/Components/Dashboard/Common/RowActions.vue'
import ServerSideDataTable from '@/Components/Dashboard/Common/ServerSideDataTable.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3'
import { provide } from 'vue'

defineOptions({ layout: DashboardLayout })

const header = {
    title: 'Amenity',
    mainPage: 'Pages',
    page: 'Create',
};
provide('layoutHeader', header)
const props = defineProps({
    amenities: Object,
})

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'description', label: 'Description' },

]

const deleteAmenity = (row) => {

    router.delete(route('amenities.destroy', row.id), {
        preserveScroll: true,
        onSuccess: () => {
            loadAmenities({
                filters: { global: '' },
                sortBy: 'id',
                sortDesc: false,
                page: 1,
                perPage: 10,
            })
        }
    })
}


const loadAmenities = ({ filters, sortBy, sortDesc, page, perPage }) => {
    router.get(
        route('amenities.index'),
        {
            global: filters.global,
            sortBy,
            sortDesc,
            page,
            perPage,
        },
        {
            preserveState: true,
            replace: true,
        }
    )
}
</script>