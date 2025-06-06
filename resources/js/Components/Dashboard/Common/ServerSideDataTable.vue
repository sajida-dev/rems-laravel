<template>
    <div class="border rounded-lg shadow-sm overflow-hidden">
        <!-- Toolbar -->
        <div class="flex items-center justify-between p-4 bg-gray-100">
            <input v-model="filters.global" @input="debouncedSearch" type="text" placeholder="Search..."
                class="px-3 py-2 border rounded w-1/3" />
            <div>
                <Link v-if="createRoute && createLabel && isShow" :href="createRoute"
                    class="mx-3 px-3 py-2 bg-pink-500 text-white rounded">
                {{ createLabel }}
                </Link>
                <button v-if="paginatedData.length > 0" @click="exportCSV"
                    class="px-3 py-1 bg-black text-white rounded">
                    Export CSV
                </button>
                <button v-if="paginatedData.length > 0" @click="exportPDF"
                    class=" mx-3 px-3 py-1 bg-yellow-600 text-white rounded">
                    Export PDF
                </button>
            </div>
        </div>

        <!-- Horizontal scroll -->
        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th v-if="selectable" class="sticky left-0 w-12 bg-gray-50 px-3 py-2 text-left z-20">
                            <input type="checkbox" @change="toggleAllSelection" :checked="allSelected" />
                        </th>
                        <th v-for="column in columns" :key="column.key"
                            class="px-3 py-2 text-left cursor-pointer select-none" @click="sort(column.key)">
                            {{ column.label }}
                            <span v-if="sortBy === column.key">
                                {{ sortDesc ? '🔽' : '🔼' }}
                            </span>
                        </th>
                        <th v-if="hasRowActions" class="px-3 py-2 text-left">Actions</th>
                        <th v-if="expandable" class="w-10 px-3 py-2"></th>
                    </tr>
                    <tr v-if="filterable">
                        <th v-if="selectable" class="sticky left-0 bg-gray-50"></th>
                        <th v-for="column in columns" :key="column.key" class="px-3 py-1">
                            <input v-model="filters.columns[column.key]" @input="debouncedSearch" type="text"
                                class="px-2 py-1 border rounded w-full" placeholder="Filter..." />
                        </th>
                        <th v-if="expandable || hasRowActions"></th>
                    </tr>
                </thead>

                <tbody class="bg-white">
                    <template v-for="(row, index) in paginatedData" :key="row?.id || index">
                        <tr class="hover:bg-gray-50 transition">
                            <td v-if="selectable" class="sticky left-0 bg-white w-12 px-3 py-2 z-10">
                                <input type="checkbox" v-model="selectedRows" :value="row?.id" />
                            </td>
                            <td v-for="column in columns" :key="column.key" class="px-3 py-2 whitespace-nowrap">
                                <template v-if="$slots['cell-' + column.key]">
                                    <slot :name="'cell-' + column.key" :value="row?.[column.key]" :row="row" />
                                </template>
                                <template v-else>
                                    {{ row?.[column.key] }}
                                </template>
                            </td>
                            <td v-if="hasRowActions" class="px-3 py-2 whitespace-nowrap">
                                <slot name="row-actions" :row="row" />
                            </td>
                            <td v-if="expandable" class="text-center px-3 py-2">
                                <button @click="toggleExpand(row?.id)">
                                    {{ expandedRows.includes(row?.id) ? '−' : '+' }}
                                </button>
                            </td>
                        </tr>

                        <tr v-if="expandable && row && expandedRows.includes(row.id)" class="bg-gray-50">
                            <td :colspan="columns.length + (selectable ? 1 : 0) + (expandable ? 1 : 0)" class="p-3">
                                <slot name="expanded-row" :row="row" />
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center p-4 border-t bg-gray-50">
            <div>
                Showing {{ pageStart }} to {{ pageEnd }} of {{ props.pagination.total ?? 0 }}
            </div>
            <div class="flex items-center gap-2">
                <button @click="prevPage" :disabled="props.pagination.currentPage === 1"
                    class="px-3 py-1 border rounded">Previous</button>
                <button @click="nextPage" :disabled="props.pagination.currentPage === props.pagination.lastPage"
                    class="px-3 py-1 border rounded">Next</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import debounce from 'lodash.debounce'
import { Link, usePage } from '@inertiajs/vue3'
import { jsPDF } from 'jspdf';
import { autoTable } from 'jspdf-autotable';

const usepage = usePage()

const isShow = ref(true)

onMounted(() => {
    if (props.isProperties) {
        const user = usepage.props.auth.user
        if (user.role === 'admin' || (user.role === 'agent' && user.agent?.status === 0)) {
            isShow.value = false
        }
    }
})

const props = defineProps({
    columns: { type: Array, required: true, default: () => [] },
    rows: { type: Array, required: true, default: () => [] },
    selectable: { type: Boolean, default: false },
    expandable: { type: Boolean, default: false },
    filterable: { type: Boolean, default: false },
    perPage: { type: Number, default: 10 },
    virtualScroll: { type: Boolean, default: false },
    rowHeight: { type: Number, default: 48 },
    createRoute: { type: String, default: '' },
    createLabel: { type: String, default: '' },
    hasRowActions: { type: Boolean, default: false },
    pagination: { type: Object, required: true },
    isProperties: { type: Boolean, default: false },
})

const emit = defineEmits(['update'])
const selectedRows = ref([])
const expandedRows = ref([])
const page = ref(1)
const sortBy = ref('')
const sortDesc = ref(false)
const filters = ref({ global: '', columns: {} })

const debouncedSearch = debounce(() => { page.value = 1 }, 400)

const toggleExpand = id => {
    if (!id) return
    const idx = expandedRows.value.indexOf(id)
    if (idx > -1) expandedRows.value.splice(idx, 1)
    else expandedRows.value.push(id)
}

const toggleAllSelection = () => {
    selectedRows.value = allSelected.value ? [] : props.rows.filter(r => r?.id).map(r => r.id)
}

const allSelected = computed(() =>
    props.rows.length > 0 && selectedRows.value.length === props.rows.filter(r => r?.id).length
)

const sortedData = computed(() => {
    let data = [...props.rows].filter(row => row)
    if (sortBy.value) {
        data.sort((a, b) => {
            const aVal = a[sortBy.value]
            const bVal = b[sortBy.value]
            if (aVal < bVal) return sortDesc.value ? 1 : -1
            if (aVal > bVal) return sortDesc.value ? -1 : 1
            return 0
        })
    }
    return data
})

const filteredData = computed(() => {
    return sortedData.value.filter(row => {
        if (!row) return false
        const globalMatch = Object.values(row).some(val =>
            val?.toString().toLowerCase().includes(filters.value.global.toLowerCase())
        )
        const columnMatch = Object.keys(filters.value.columns).every(key => {
            const filter = filters.value.columns[key]
            return !filter || row[key]?.toString().toLowerCase().includes(filter.toLowerCase())
        })
        return globalMatch && columnMatch
    })
})

const paginatedData = computed(() => {
    const start = (page.value - 1) * props.perPage
    return filteredData.value.slice(start, start + props.perPage)
})

const pageStart = computed(() => {
    if (!props.pagination || !props.pagination.perPage || !props.pagination.currentPage) return 0;
    return (props.pagination.perPage * (props.pagination.currentPage - 1)) + 1;
});

const pageEnd = computed(() => {
    if (!props.pagination || !props.pagination.perPage || !props.pagination.currentPage || !props.pagination.total) return 0;
    return Math.min(props.pagination.currentPage * props.pagination.perPage, props.pagination.total);
});

const totalPages = computed(() => {
    return props.pagination?.lastPage ?? 0;
});

const sort = key => {
    if (sortBy.value === key) sortDesc.value = !sortDesc.value
    else { sortBy.value = key; sortDesc.value = false }
}

const prevPage = () => {
    if (props.pagination.currentPage > 1) {
        emit('update', { page: props.pagination.currentPage - 1 })
    }
}

const nextPage = () => {
    if (props.pagination.currentPage < props.pagination.lastPage) {
        emit('update', { page: props.pagination.currentPage + 1 })
    }
}

watch([filters, sortBy, sortDesc, page], () => {
    emit('update', {
        filters: filters.value,
        sortBy: sortBy.value,
        sortDesc: sortDesc.value,
        page: page.value,
        perPage: props.perPage
    })
})

const exportCSV = () => {
    const csvRows = []
    const headers = props.columns.map(col => col.label)
    csvRows.push(headers.join(','))
    props.rows.filter(row => row).forEach(row => {
        const values = props.columns.map(col => `"${row[col.key] ?? ''}"`)
        csvRows.push(values.join(','))
    })
    const blob = new Blob([csvRows.join('\n')], { type: 'text/csv' })
    const link = document.createElement('a')
    link.href = URL.createObjectURL(blob)
    link.download = 'export.csv'
    link.click()
}

const exportPDF = () => {
    const doc = new jsPDF()

    const headers = props.columns.map(col => col.label)
    const data = props.rows.filter(row => row).map(row =>
        props.columns.map(col => row[col.key] ?? '')
    )

    autoTable(doc, {
        head: [headers],
        body: data,
        styles: { fontSize: 10 },
        headStyles: { fillColor: [0, 0, 0], textColor: [255, 255, 255] },
        startY: 20,
        margin: { top: 10 },
    })

    doc.save('export.pdf')
}
</script>
