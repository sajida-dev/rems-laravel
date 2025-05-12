<script setup>
import { ref } from 'vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import ActionMessage from '@/Components/Default/ActionMessage.vue';
import FormSection from '@/Components/Default/FormSection.vue';
import InputError from '@/Components/Default/InputError.vue';
import InputLabel from '@/Components/Default/InputLabel.vue';
import PrimaryButton from '@/Components/Default/PrimaryButton.vue';
import SecondaryButton from '@/Components/Default/SecondaryButton.vue';
import TextInput from '@/Components/Default/TextInput.vue';
import { toast } from 'vue3-toastify'
import SpecializationSelect from '@/Components/Dashboard/Common/SpecializationSelect.vue';

const props = defineProps({
    user: Object,
});

const page = usePage()
const user = page.props.auth.user
const agent = user.agent || {}
const categories = page.props.allCategories  // array of { id, name }
const savedCategories = page.props.savedCategories
const form = useForm({
    _method: 'PUT',
    name: user.name,
    username: user.username ?? '',
    email: user.email,
    contact: user.contact ?? '',
    photo: null,
    agency: agent?.agency ?? '',
    licence_no: agent?.licence_no ?? '',
    experience: agent?.experience ?? '',
    bio: agent?.bio ?? '',
    categories: savedCategories ?? [],
});


const verificationLinkSent = ref(user.profile_photo_path);
const photoPreview = ref(null);
const photoInput = ref(null);
const profileImage = ref('/storage' + '/' + user.profile_photo_path ?? 'default/avatar.png');

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Profile updated successfully')
            clearPhotoFileInput()
        },
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
            toast.success('Profile photo deleted successfully')
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            Profile Information
        </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input id="photo" ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">

                <InputLabel for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div v-show="!photoPreview" class="mt-2">
                    <img :src="profileImage" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'" />
                </div>

                <SecondaryButton class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
                    Select A New Photo
                </SecondaryButton>

                <SecondaryButton v-if="user.profile_photo_path" type="button" class="mt-2" @click.prevent="deletePhoto">
                    Remove Photo
                </SecondaryButton>

                <InputError :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required
                    autocomplete="name" />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>


            <div v-if="user.role !== 'agent'" class="col-span-6 sm:col-span-4">
                <InputLabel for="username" value="Username" />
                <TextInput id="username" v-model="form.username" type="text" class="mt-1 block w-full" required
                    autocomplete="username" />
                <InputError :message="form.errors.username" class="mt-2" />
            </div>
            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">

                <InputLabel for="email" value="Email" />
                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required
                    autocomplete="email" />
                <InputError :message="form.errors.email" class="mt-2" />

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2">
                        Your email address is unverified.

                        <Link :href="route('verification.send')" method="post" as="button"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            @click.prevent="sendEmailVerification">
                        Click here to re-send the verification email.
                        </Link>
                    </p>

                    <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                        A new verification link has been sent to your email address.
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="contact" value="Contact" />
                <TextInput id="contact" v-model="form.contact" type="text" class="mt-1 block w-full" autocomplete="tel"
                    placeholder="+92 (300) 1234567" />
                <InputError :message="form.errors.contact" class="mt-2" />
            </div>
            <div v-if="user.role === 'agent'" class="col-span-6 sm:col-span-4 ">
                <!-- Agency Name -->
                <div class="col-span-6 sm:col-span-4 mt-1">
                    <InputLabel for="agency" value="Agency Name" />
                    <TextInput id="agency" v-model="form.agency" type="text" class="mt-1 text-sm block w-full" />
                    <InputError :message="form.errors.agency" class="mt-2" />
                </div>

                <!-- Experience (Years) -->
                <div class="col-span-6 sm:col-span-4 mt-1">
                    <InputLabel for="experience" value="Experience (Years)" />
                    <TextInput id="experience" v-model="form.experience" type="number" class="mt-1 text-sm block w-full"
                        min="0" />
                    <InputError :message="form.errors.experience" class="mt-2" />
                </div>
                <!-- License Number -->
                <div class="col-span-6 sm:col-span-4 mt-1">
                    <InputLabel for="licence_no" value="License Number" />
                    <TextInput id="licence_no" v-model="form.licence_no" type="text"
                        class="mt-1 text-sm block w-full" />
                    <InputError :message="form.errors.licence_no" class="mt-2" />
                </div>

                <!-- Bio -->
                <div class="col-span-6 sm:col-span-4 mt-1">
                    <InputLabel for="bio" value="Agent Bio" />
                    <textarea id="bio" v-model="form.bio" rows="4"
                        class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500"></textarea>
                    <InputError :message="form.errors.bio" class="mt-2" />
                </div>
                <!-- Specializations -->
                <div class="col-span-6 sm:col-span-4 mt-1">
                    <SpecializationSelect :list="categories" v-model="form.categories" />
                    <InputError :message="form.errors.categories" class="mt-2" />
                </div>
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
