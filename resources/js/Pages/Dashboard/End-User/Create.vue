<template>

    <Head title="New End User" />
    <FormLayout title="New End User" :method="'post'" :routeName="'end-users.store'"
        :fields="{ name: '', username: '', email: '', password: '', avatar: '' }" class="bg-white p-5">
        <template #fields="{ form, errors }">
            <div class="bg-white rounded w-[95%] mx-auto flex flex-col md:flex-row justify-between">
                <div class="w-full md:w-2/3 pr-4">
                    <div>
                        <InputLabel for="name" value="Full Name" />
                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 text-sm block w-full"
                            autofocus />
                        <InputError class="mt-2" :message="errors.name" />
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
                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />
                        <TextInput id="password" v-model="form.password" type="password"
                            class="mt-1 text-sm block w-full" />
                        <InputError class="mt-2" :message="errors.password" />
                    </div>
                </div>
                <div
                    class="w-full md:w-1/3 mt-6 md:mt-0 flex flex-col items-center justify-center border-l border-gray-200 pl-6">
                    <div class="relative">
                        <img :src="avatarPreview || 'https://ui-avatars.com/api/?name=User&size=100'" alt="Avatar"
                            class="w-64 h-64 rounded-full shadow-md mb-4 cursor-pointer" @click="triggerFileInput" />
                        <input type="file" ref="fileInput" class="hidden" @change="handleFileChange" accept="image/*" />
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
const avatarPreview = ref(null);
const fileInput = ref(null);

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileChange = async (event) => {
    const file = event.target.files[0];
    if (file) {
        form.avatar = file;
        avatarPreview.value = URL.createObjectURL(file);
    }
};

const form = reactive({
    name: '',
    username: '',
    email: '',
    password: '',
    avatar: ''
});


const header = {
    title: 'User Management',
    mainPage: 'Admin',
    page: 'Register User',
}
provide('layoutHeader', header)
</script>
