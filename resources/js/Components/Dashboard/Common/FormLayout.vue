<template>
    <form @submit.prevent="submit" :enctype="currentEnctype">
        <h5 class="text-2xl text-center my-5 font-bold">
            {{ title }}
        </h5>
        <div class="main-body">
            <slot name="fields" :form="form" :errors="form.errors" />
        </div>

        <div class="border-0 my-5 flex justify-end mx-5">
            <PrimaryButton :disabled="form.processing" :class="{ 'opacity-25': form.processing }">
                {{ submitLabel }}
            </PrimaryButton>
        </div>
    </form>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/Default/PrimaryButton.vue'
import { defineProps, computed } from 'vue'
import { ref } from 'vue'
import { toast } from 'vue3-toastify'
const csrfToken = ref(document.querySelector('meta[name="csrf-token"]').getAttribute('content'))
const props = defineProps({
    title: String,
    fields: Object,
    routeName: String,
    submitLabel: {
        type: String,
        default: 'Submit'
    },
    routeParams: {
        type: Object,
        default: () => ({}),
    },
})

const form = useForm({ ...props.fields })
const hasFileUpload = computed(() =>
    Object.values(form.data()).some(v => v instanceof File)
)
const currentEnctype = computed(() =>
    hasFileUpload.value ? 'multipart/form-data' : 'application/json'
)
const submit = () => {


    form.post(window.route(props.routeName, props.routeParams), {
        preserveScroll: true,
        forceFormData: hasFileUpload.value,
        onSuccess: (response) => {
            toast.success(response.props?.flash?.success || 'Submitted successfully')
            form.reset(props.fields)
        },
        onError: (errors) => {
            Object.values(errors).flat().forEach(message => {
                toast.error(message)
            })
        }
    })


}



function handleFileChange(event) {
    const file = event.target.files[0];
    form.avatar = file;
    avatarPreview.value = URL.createObjectURL(file);
}
</script>
