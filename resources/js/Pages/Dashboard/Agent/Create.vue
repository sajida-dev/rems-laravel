<template>

    <Head title="New Agent" />

    <FormLayout title="New Agent" :method="'post'" :routeName="'agents.store'" :fields="form" class="bg-white p-5">
        <template #fields="{ form, errors }">
            <div class="grid md:grid-cols-3 gap-6 w-[95%] mx-auto">
                <!-- Avatar -->
                <div class="col-span-1 flex justify-center md:block">
                    <div class="relative w-full flex justify-center md:justify-start">
                        <img :src="avatarPreview || 'https://ui-avatars.com/api/?name=Agent&size=256'" alt="Avatar"
                            class="w-64 h-64 rounded-full shadow-md mb-4 cursor-pointer object-cover"
                            @click="triggerFileInput" />
                        <input type="file" ref="fileInput" class="hidden" @change="handleFileChange" accept="image/*" />
                    </div>
                    <InputError class="text-center" :message="errors.avatar" />
                </div>

                <!-- Form Fields -->
                <div class="col-span-2 grid sm:grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="name" value="Agent Name" />
                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 text-sm block w-full"
                            autofocus />
                        <InputError class="mt-2" :message="errors.name" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Email" />
                        <TextInput id="email" v-model="form.email" type="email" class="mt-1 text-sm block w-full" />
                        <InputError class="mt-2" :message="errors.email" />
                    </div>

                    <div>
                        <InputLabel for="agency" value="Agency Name" />
                        <TextInput id="agency" v-model="form.agency" type="text" class="mt-1 text-sm block w-full" />
                        <InputError class="mt-2" :message="errors.agency" />
                    </div>

                    <div>
                        <InputLabel for="contact" value="Contact Number" />
                        <TextInput id="contact" v-model="form.contact" type="text" class="mt-1 text-sm block w-full" />
                        <InputError class="mt-2" :message="errors.contact" />
                    </div>

                    <div>
                        <InputLabel for="experience" value="Experience (Years)" />
                        <TextInput id="experience" v-model="form.experience" type="number"
                            class="mt-1 text-sm block w-full" min="0" />
                        <InputError class="mt-2" :message="errors.experience" />
                    </div>
                    <div>
                        <InputLabel for="licence_no" value="Licence Number" />
                        <TextInput id="licence_no" v-model="form.licence_no" type="text"
                            class="mt-1 text-sm block w-full" />
                        <InputError class="mt-2" :message="errors.licence_no" />
                    </div>


                    <div class="sm:col-span-2">
                        <InputLabel for="bio" value="Agent Bio" />
                        <textarea id="bio" v-model="form.bio" rows="4"
                            class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500  "></textarea>
                        <InputError class="mt-2" :message="errors.bio" />
                    </div>

                    <div class="sm:col-span-2">
                        <SpecializationSelect :categories="categories" v-model="form.categories" />
                        <InputError class="mt-2" :message="errors.categories" />
                    </div>
                </div>
            </div>
        </template>
    </FormLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import { ref, reactive, provide } from 'vue'
import FormLayout from '@/Components/Dashboard/Common/FormLayout.vue'
import InputLabel from '@/Components/Default/InputLabel.vue'
import TextInput from '@/Components/Default/TextInput.vue'
import InputError from '@/Components/Default/InputError.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import SpecializationSelect from '@/Components/Dashboard/Common/SpecializationSelect.vue'

defineOptions({ layout: DashboardLayout })

defineProps({
    categories: Array
})
const form = reactive({
    name: '',
    email: '',
    licence_no: '',
    agency: '',
    contact: '',
    experience: '',
    bio: '',
    status: false,
    categories: [],
    avatar: null
})


const avatarPreview = ref(null)
const fileInput = ref(null)

const triggerFileInput = () => fileInput.value.click()

const handleFileChange = (e) => {
    const file = e.target.files[0]
    if (file) {
        form.avatar = file
        avatarPreview.value = URL.createObjectURL(file)
    }
}

const header = {
    title: 'Agent Management',
    mainPage: 'Dashboard',
    page: 'New Agent'
}
provide('layoutHeader', header)
</script>
