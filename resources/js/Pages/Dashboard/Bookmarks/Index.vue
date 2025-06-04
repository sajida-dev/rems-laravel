<template>

    <Head title="My Bookmarks" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
        <div class="">
            <h2 class="text-2xl font-semibold mb-4">
                {{ userRole === 'admin' ? 'All Properties' :
                    userRole === 'agent' ? 'My Properties' :
                        'My Bookmarked Properties' }}
            </h2>

            <ServerSideDataTable :columns="columns" :rows="formattedProperties" :selectable="false" :expandable="false"
                :filterable="true" :perPage="properties.per_page" :virtualScroll="true" :hasRowActions="true"
                :pagination="{
                    total: properties.total,
                    perPage: properties.per_page,
                    currentPage: properties.current_page,
                    lastPage: properties.last_page
                }" @update="loadData">
                <template #row-actions="{ row }">
                    <RowActions :row="row"
                        :viewRoute="row => userRole === 'user' ? route('property-details', { id: row.id, title: slugify(row.title) }) : route('bookmarks.show', row.id)"
                        :showEdit="false" :showDelete="false" />
                </template>
            </ServerSideDataTable>
        </div>
    </div>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import ServerSideDataTable from '@/Components/Dashboard/Common/ServerSideDataTable.vue';
import RowActions from '@/Components/Dashboard/Common/RowActions.vue';
import { toast } from 'vue3-toastify';

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    properties: Object,
    filters: Object,
    userRole: String
});

const formattedProperties = computed(() =>
    props.properties.data.map(property => ({
        ...property,
        category_name: property.category?.name ?? '-',
        agent_name: property.agent?.user?.name ?? '-',
    }))
);

const columns = computed(() => {
    const baseColumns = [
        { key: 'id', label: 'ID' },
        { key: 'title', label: 'Title' },
        { key: 'location', label: 'Location' },
        { key: 'category_name', label: 'Category' },
        { key: 'agent_name', label: 'Agent' },
        { key: 'rent_price', label: 'Rent Price' },
    ];

    // Add bookmark count column only for admin and agent roles
    if (props.userRole !== 'user') {
        baseColumns.push({ key: 'bookmarks_count', label: 'Bookmarks' });
    }

    return baseColumns;
});

const tableState = reactive({
    filters: { global: '' },
    sortBy: 'id',
    sortDesc: false,
    page: 1,
    perPage: 10,
});

const loadData = (options = {}) => {
    router.get(route('bookmarks.index'), {
        global: tableState.filters.global,
        sortBy: tableState.sortBy,
        sortDesc: tableState.sortDesc,
        page: options.page ?? tableState.page,
        perPage: tableState.perPage,
    }, {
        preserveState: true,
        replace: true,
    });
};

const removeBookmark = (row) => {
    router.delete(route('bookmarks.destroy', row.id), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Bookmark removed successfully');
            loadData();
        },
        onError: () => {
            toast.error('Failed to remove bookmark');
        },
    });
};

const updateTableState = ({ filters, sortBy, sortDesc, page, perPage }) => {
    tableState.filters = filters;
    tableState.sortBy = sortBy;
    tableState.sortDesc = sortDesc;
    tableState.page = page;
    tableState.perPage = perPage;
    loadData();
};

const slugify = (name) => {
    return name.toLowerCase().replace(/\s+/g, '-');
};
</script>