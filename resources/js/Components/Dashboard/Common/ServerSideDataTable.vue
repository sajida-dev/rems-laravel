<template>
    <div class="border rounded-lg shadow-sm overflow-hidden">
        <!-- Toolbar -->
        <div class="flex items-center justify-between p-4 bg-gray-100">
            <input v-model="filters.global" @input="debouncedSearch" type="text" placeholder="Search..."
                class="px-3 py-2 border rounded w-1/3" />
            <slot name="actions">
                <div>
                    <button @click="exportCSV" class="px-3 py-1 bg-pink-600 text-white rounded">
                        Export CSV
                    </button>
                    <Link href="/categories/create" class="px-3 py-1 bg-pink-600 text-white rounded">
                    Add new categories
                    </Link>
                </div>

            </slot>
        </div>

        <!-- Horizontal scroll container -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto text-sm">
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
                        <th v-if="expandable" class="w-10 px-3 py-2"></th>
                    </tr>
                    <tr v-if="filterable">
                        <th v-if="selectable" class="sticky left-0 bg-gray-50"></th>
                        <th v-for="column in columns" :key="column.key" class="px-3 py-1">
                            <input v-model="filters.columns[column.key]" @input="debouncedSearch" type="text"
                                class="px-2 py-1 border rounded w-full" placeholder="Filter..." />
                        </th>
                        <th v-if="expandable"></th>
                    </tr>
                </thead>

                <!-- Virtualized rows -->
                <virtual-list :data-key="'id'" :data-sources="filteredData" :size="rowHeight" tag="tbody"
                    class="virtual-list bg-white">
                    <template #default="{ item: row, index }">
                        <tr class="hover:bg-gray-50 transition">
                            <td v-if="selectable" class="sticky left-0 bg-white w-12 px-3 py-2 z-10">
                                <input type="checkbox" v-model="selectedRows" :value="row.id" />
                            </td>

                            <td v-for="column in columns" :key="column.key" class="px-3 py-2 whitespace-nowrap">
                                <template v-if="$slots['cell-' + column.key]">
                                    <slot :name="'cell-' + column.key" :row="row" />
                                </template>
                                <template v-else>
                                    {{ row[column.key] }}
                                </template>
                            </td>

                            <td v-if="expandable" class="text-center px-3 py-2">
                                <button @click="toggleExpand(row.id)">
                                    {{ expandedRows.includes(row.id) ? '−' : '+' }}
                                </button>
                            </td>
                        </tr>
                        <tr v-if="expandable && expandedRows.includes(row.id)" class="bg-gray-50">
                            <td :colspan="columns.length + (selectable ? 1 : 0) + (expandable ? 1 : 0)" class="p-3">
                                <slot name="expand" :row="row" />
                            </td>
                        </tr>
                    </template>
                </virtual-list>
            </table>
        </div>

        <!-- Pagination (if not using virtual scroll for full dataset) -->
        <div v-if="!virtualScroll" class="flex justify-between items-center p-4 border-t bg-gray-50">
            <div>
                Showing {{ pageStart }} to {{ pageEnd }} of {{ filteredData.length }}
            </div>
            <div class="flex items-center gap-2">
                <button @click="prevPage" :disabled="page === 1" class="px-3 py-1 border rounded">
                    Previous
                </button>
                <button @click="nextPage" :disabled="page === totalPages" class="px-3 py-1 border rounded">
                    Next
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import debounce from 'lodash.debounce'
import VirtualList from 'vue3-virtual-scroll-list'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    columns: { type: Array, required: true },
    rows: { type: Array, required: true },
    selectable: { type: Boolean, default: false },
    expandable: { type: Boolean, default: false },
    filterable: { type: Boolean, default: false },
    perPage: { type: Number, default: 10 },
    virtualScroll: { type: Boolean, default: true },
    rowHeight: { type: Number, default: 48 }
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
    const idx = expandedRows.value.indexOf(id)
    if (idx > -1) expandedRows.value.splice(idx, 1)
    else expandedRows.value.push(id)
}

const toggleAllSelection = () => {
    selectedRows.value = allSelected.value ? [] : props.rows.map(r => r.id)
}

const allSelected = computed(() =>
    props.rows.length > 0 && selectedRows.value.length === props.rows.length
)

const sortedData = computed(() => {
    let data = [...props.rows]
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

const pageStart = computed(() => (page.value - 1) * props.perPage + 1)
const pageEnd = computed(() => Math.min(page.value * props.perPage, filteredData.value.length))
const totalPages = computed(() => Math.ceil(filteredData.value.length / props.perPage))

const sort = key => {
    if (sortBy.value === key) sortDesc.value = !sortDesc.value
    else { sortBy.value = key; sortDesc.value = false }
}
const prevPage = () => page.value--
const nextPage = () => page.value++

watch([filters, sortBy, sortDesc, page], () => {
    emit('update', { filters: filters.value, sortBy: sortBy.value, sortDesc: sortDesc.value, page: page.value, perPage: props.perPage })
})

const exportCSV = () => {
    const csvRows = []
    const headers = props.columns.map(col => col.label)
    csvRows.push(headers.join(','))
    props.rows.forEach(row => {
        const values = props.columns.map(col => `"${row[col.key] ?? ''}"`)
        csvRows.push(values.join('\n'))
    })
    const blob = new Blob([csvRows.join('\n')], { type: 'text/csv' })
    const link = document.createElement('a')
    link.href = URL.createObjectURL(blob)
    link.download = 'export.csv'
    link.click()
}
</script>
