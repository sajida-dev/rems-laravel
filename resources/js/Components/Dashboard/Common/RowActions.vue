<!-- <template>
    <div class="flex gap-2 items-center">
        <Link v-if="viewRoute" :href="viewRoute(row)" class="text-blue-600" title="View">
        <i class="fas fa-eye"></i>
        </Link>
        <Link v-if="editRoute" :href="editRoute(row)" class="text-green-800" title="Edit">
        <i class="fas fa-edit"></i>
        </Link>
        <button v-if="deleteHandler" @click="handleDelete" class="text-red-600" title="Delete">
            <i class="fas fa-trash-alt"></i>
        </button>
        <ConfirmDialog v-if="showConfirm" :visible="showConfirm" :title="`Delete Category`"
            :message="`Are you sure you want to delete category '${row.name}'?`" @confirm="handleConfirm"
            @cancel="showConfirm = false" />
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import ConfirmDialog from './ConfirmDialog.vue'

const showConfirm = ref(false)

const props = defineProps({
    row: { type: Object, required: true },
    viewRoute: Function,
    editRoute: Function,
    deleteHandler: Function
})
const handleDelete = () => {
    showConfirm.value = true
}

const handleConfirm = () => {
    props.deleteHandler(props.row)
    showConfirm.value = false
}
</script> -->



<template>
    <div class="flex gap-2 items-center">
        <Link v-if="viewRoute" :href="viewRoute(row)" class="text-blue-600" title="View">
        <i class="fas fa-eye"></i>
        </Link>
        <Link v-if="editRoute" :href="editRoute(row)" class="text-green-800" title="Edit">
        <i class="fas fa-edit"></i>
        </Link>
        <button v-if="deleteHandler" @click="handleDelete" class="text-red-600" title="Delete">
            <i class="fas fa-trash-alt"></i>
        </button>

        <ConfirmDialog v-if="showConfirm" :visible="showConfirm" :title="`Delete '${row.name}'`"
            :message="`Are you sure you want to delete '${row.name}'?`" @confirm="handleConfirm"
            @cancel="showConfirm = false" />
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import ConfirmDialog from './ConfirmDialog.vue'

const showConfirm = ref(false)

const props = defineProps({
    row: { type: Object, required: true },
    viewRoute: Function,
    editRoute: Function,
    deleteHandler: Function
})

const handleDelete = () => {
    showConfirm.value = true
}

const handleConfirm = () => {
    if (props.deleteHandler) {
        props.deleteHandler(props.row) // this ensures row is passed
    }
    showConfirm.value = false
}
</script>
