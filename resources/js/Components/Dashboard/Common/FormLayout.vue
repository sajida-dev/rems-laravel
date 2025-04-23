<template>
    <form @submit.prevent="submit">
        <h5 class="text-2xl text-center my-5 font-bold">
            {{ title }}
        </h5>


        <div class="main-body">
            <slot name="fields" :form="form" :errors="form.errors" />
        </div>

        <div class="border-0 my-5 flex justify-end mx-5">
            <PrimaryButton :disabled="form.processing" :class="{ 'opacity-25': form.processing }">
                Submit
            </PrimaryButton>
        </div>
    </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/Default/PrimaryButton.vue'
import { defineProps } from 'vue'

const props = defineProps({
    title: String,
    fields: Object,
    routeName: String,
})

const form = useForm({ ...props.fields })

const submit = () => {
    form.post(route(props.routeName), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    })
}
</script>
