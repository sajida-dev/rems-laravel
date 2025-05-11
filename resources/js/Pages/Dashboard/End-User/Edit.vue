<template>

    <Head :title="endUser.name" />

    <FormLayout :title="endUser.name" :routeName="'end-users.update'" :submitLabel="'Update ' + endUser.name"
        :routeParams="{ end_user: endUser.id }" class="bg-white p-5" :fields="form">
        <template #fields="{ form, errors }">
            <div class="bg-white rounded w-[95%] mx-auto flex flex-col md:flex-row justify-between">
                <div class="w-full md:w-2/3 pr-4">
                    <div class="flex flex-row gap-5">
                        <div class="w-1/2">
                            <InputLabel for="name" value="Full Name" />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 text-sm block w-full"
                                autofocus />
                            <InputError class="mt-2" :message="errors.name" />
                        </div>
                        <div class="w-1/2">
                            <InputLabel for="contact" value="contact" />
                            <TextInput id="contact" autocomplete="tel" v-model="form.contact" type="text"
                                placeholder="+92 (300) 1234567" class="mt-1 text-sm block w-full" />
                            <InputError class="mt-2" :message="errors.contact" />
                        </div>
                    </div>


                    <div class="mt-4">
                        <InputLabel for="username" value="Username" />
                        <TextInput id="username" v-model="form.username" type="text"
                            class="mt-1 text-sm block w-full" />
                        <InputError class="mt-2" :message="errors.username" />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="email" value="Email" />
                        <TextInput id="email" v-model="form.email" type="email" class="mt-1 text-sm block w-full" />
                        <InputError class="mt-2" :message="errors.email" />
                    </div>
                    <div class="flex flex-row gap-5 mt-4">
                        <div class="w-1/2">
                            <InputLabel for="password" value="Password" />
                            <TextInput id="password" v-model="form.password" type="password"
                                class="mt-1 text-sm block w-full" />
                            <InputError class="mt-2" :message="errors.password" />
                        </div>
                        <div class="w-1/2">
                            <InputLabel for="password_confirmation" value="Confirm Password" />
                            <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password"
                                class="mt-1 text-sm block w-full" />
                            <InputError class="mt-2" :message="errors.password_confirmation" />
                        </div>
                    </div>


                </div>

                <div
                    class="w-full md:w-1/3 mt-6 md:mt-0 flex flex-col items-center justify-center border-l border-gray-200 pl-6">
                    <div class="relative">
                        <img :src="avatarPreview || 'https://ui-avatars.com/api/?name=User&size=100'"
                            :alt="endUser.name" class="w-64 h-64 rounded-full shadow-md mb-4 cursor-pointer"
                            @click="triggerFileInput" />
                        <input type="file" ref="fileInput" class="hidden" @input="form.avatar = $event.target.files[0]"
                            @change="handleFileChange" accept="image/*" />
                        <InputError class="mt-2" :message="errors.avatar" />

                    </div>
                </div>

            </div>

        </template>
    </FormLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import { provide, reactive, ref } from 'vue'
import FormLayout from '@/Components/Dashboard/Common/FormLayout.vue'
import InputLabel from '@/Components/Default/InputLabel.vue'
import TextInput from '@/Components/Default/TextInput.vue'
import InputError from '@/Components/Default/InputError.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
defineOptions({ layout: DashboardLayout })

const { endUser } = defineProps({
    endUser: Object
})


const avatarPreview = ref(endUser.profile_photo_path
    ? `${'/storage' + '/' + endUser.profile_photo_path}`
    : null);
const fileInput = ref(null);

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileChange = async (event) => {
    const file = event.target.files[0];
    if (file) {
        avatarPreview.value = URL.createObjectURL(file);
    }
};
const form = reactive({
    name: endUser.name,
    username: endUser.username,
    email: endUser.email,
    contact: endUser.contact,
    password: '',
    password_confirmation: '',
    avatar: endUser.profile_photo_path,
});

const header = {
    title: 'User Management',
    mainPage: 'Admin',
    page: 'Edit End User',
}
provide('layoutHeader', header)
</script>
