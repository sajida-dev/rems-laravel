<template>

    <Head title="Add Property" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
        <FormLayout title="Add Property" :method="'post'" :enctype="'multipart/form-data'"
            :routeName="'properties.store'" :fields="{
                title: '', description: '', location: '', rent_price: null, type: '',
                purchase_price: null, bedrooms: null, bathrooms: null, lot_area: null,
                floor_area: null, garage: null, year_built: null, stories: null,
                is_water: false, is_luggage: false, is_new_roofing: false,
                category_id: '', amenities: [], main_image: null, latitude: 31.5204, longitude: 74.3587,
                other_images: [], main_image_url: '', other_image_previews: [],
            }">
            <template #fields="{ form, errors }">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="title" value="Property Title" />
                        <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="errors.title" />
                    </div>
                    <div>
                        <InputLabel for="location" value="Location" />
                        <TextInput id="location" v-model="form.location" placeholder="Lahore" type="text"
                            class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="errors.location" />
                    </div>
                    <div>
                        <InputLabel for="type" value="Property Type" />
                        <select id="type" v-model="form.type"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Select Property type</option>
                            <option value="rent">For Rent</option>
                            <option value="buy">For Buy</option>
                        </select>
                        <InputError class="mt-2" :message="errors.type" />
                    </div>
                    <div v-if="form.type === 'rent'">
                        <InputLabel for="rent-price" value="Rent Price" />
                        <TextInput id="rent-price" v-model="form.rent_price" placeholder="129000.0" type="number"
                            class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="errors.rent_price" />
                    </div>
                    <div v-if="form.type === 'buy'">
                        <InputLabel for="purchase-price" value="Purchase Price" />
                        <TextInput id="purchase-price" v-model="form.purchase_price" placeholder="129000.0"
                            type="number" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="errors.purchase_price" />
                    </div>
                    <div>
                        <InputLabel for="bedrooms" value="Bedrooms" />
                        <TextInput id="bedrooms" v-model="form.bedrooms" class="mt-1 block w-full"
                            placeholder="e.g. 2" />
                        <InputError class="mt-2" :message="errors.bedrooms" />
                    </div>

                    <div>
                        <InputLabel for="bathrooms" value="Bathrooms" />
                        <TextInput id="bathrooms" v-model="form.bathrooms" class="mt-1 block w-full"
                            placeholder="e.g. 2" />
                        <InputError class="mt-2" :message="errors.bathrooms" />
                    </div>

                    <div>
                        <InputLabel for="lot_area" value="Lot lot_area (SQ FT)" />
                        <TextInput id="lot_area" v-model="form.lot_area" class="mt-1 block w-full" placeholder="2010" />
                        <InputError class="mt-2" :message="errors.lot_area" />
                    </div>
                    <div>
                        <InputLabel for="floor_area" value="Floor Area (SQ FT)" />
                        <TextInput id="floor_area" v-model="form.floor_area" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="errors.floor_area" />
                    </div>

                    <div>
                        <InputLabel for="garage" value="Garage" />
                        <TextInput id="garage" v-model="form.garage" class="mt-1 block w-full" placeholder="e.g. 2" />
                        <InputError class="mt-2" :message="errors.garage" />
                    </div>
                    <div>
                        <InputLabel for="yearBuilt" value="Year Built" />
                        <TextInput id="yearBuilt" v-model="form.year_built" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="errors.year_built" />
                    </div>

                    <div>
                        <InputLabel for="is_water" value="Is Water Supply" />
                        <select id="is_water" v-model="form.is_water"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option selected value="">Select</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <InputError class="mt-2" :message="errors.is_water" />
                    </div>
                    <div>
                        <InputLabel for="stories" value="Stories" />
                        <TextInput id="stories" v-model="form.stories" class="mt-1 block w-full" placeholder="e.g. 2" />
                        <InputError class="mt-2" :message="errors.stories" />
                    </div>
                    <div>
                        <InputLabel for="is_luggage" value="Is Luggage" />
                        <select v-model="form.is_luggage" id="is_luggage"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option selected value="">Select</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <InputError class="mt-2" :message="errors.is_luggage" />
                    </div>

                    <div>
                        <InputLabel for="is_new_roofing" value="Is New Roofing" />
                        <select id="is_new_roofing" v-model="form.is_new_roofing"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                            <option value="">Select Roofing</option>
                            <option value="1">New</option>
                            <option value="0">Old</option>
                        </select>
                        <InputError class="mt-2" :message="errors.is_new_roofing" />
                    </div>

                    <div>
                        <InputLabel for="category_id" value="Category" />
                        <select id="category_id" v-model="form.category_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                            <option :value="null" disabled>Select Category</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.name }}
                            </option>
                        </select>
                        <InputError class="mt-2" :message="errors.category_id" />
                    </div>

                    <div>
                        <SpecializationSelect :list="amenities" v-model="form.amenities" title="Amenities" />
                        <InputError class="mt-2" :message="errors.amenities" />
                    </div>

                    <div class="sm:col-span-2 mt-4">
                        <InputLabel for="description" value="Description" />
                        <textarea id="description" v-model="form.description"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-gray-300 focus:ring-gray-300"
                            rows="4"></textarea>
                        <InputError class="mt-2" :message="errors.description" />
                    </div>
                    <LocationPicker
                        class="sm:col-span-2 mt-4 border-gray-300 rounded-md  text-sm focus:border-gray-300 focus:ring-gray-300"
                        v-model:latitude="form.latitude" v-model:longitude="form.longitude" :errors="errors" />

                    <!-- Main Image -->
                    <div class="mt-6">
                        <InputLabel value="Main Image" />
                        <input type="file" @change="handleMainImage" @input="form.main_image = $event.target.files[0]"
                            accept="image/*" />
                        <div v-if="mainImagePreview" class="mt-2 rounded-md overflow-hidden border">
                            <img :src="mainImagePreview" class="object-cover w-full h-full" />
                        </div>
                        <InputError class="mt-2" :message="errors.main_image" />
                    </div>

                    <!-- Other Images -->
                    <div class="sm:col-span-2 mt-6">
                        <InputLabel value="Other Images" />
                        <input type="file" @change="handleOtherImages"
                            @input="form.other_images = Array.from($event.target.files)" accept="image/*" multiple />
                        <div class="mt-3 flex flex-wrap gap-2">
                            <div v-for="(img, i) in otherImagePreviews" :key="i"
                                class="w-56 h-52 border rounded-md overflow-hidden">
                                <img :src="img" class="object-cover w-full h-full" />
                            </div>
                        </div>
                        <InputError class="mt-2" :message="errors.other_images" />
                    </div>
                </div>
            </template>
        </FormLayout>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import { provide } from 'vue'
import FormLayout from '@/Components/Dashboard/Common/FormLayout.vue'
import InputLabel from '@/Components/Default/InputLabel.vue'
import TextInput from '@/Components/Default/TextInput.vue'
import InputError from '@/Components/Default/InputError.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import SpecializationSelect from '@/Components/Dashboard/Common/SpecializationSelect.vue'
import { ref, reactive } from 'vue';
import LocationPicker from '@/Components/Dashboard/Common/LocationPicker.vue'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
    amenities: Array,
    categories: Array
})
const header = {
    title: 'Property',
    mainPage: '',
    page: 'Create'
}
provide('layoutHeader', header)

const form = reactive({
    title: '',
    description: '',
    location: '',
    type: '',
    rent_price: null,
    purchase_price: null,
    bedrooms: null,
    bathrooms: null,
    lot_area: null,
    floor_area: null,
    garage: null,
    year_built: null,
    stories: null,
    is_water: false,
    is_luggage: false,
    is_new_roofing: false,
    category_id: '',
    amenities: [],
    main_image: null,
    other_images: [],
    main_image_url: '',
    other_image_previews: [],
    latitude: 31.5204,
    longitude: 74.3587,
});


const mainImagePreview = ref(null);
const otherImagePreviews = ref([]);

const handleMainImage = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.main_image = file;
        mainImagePreview.value = URL.createObjectURL(file);
    }
};

const handleOtherImages = (e) => {
    form.other_images = Array.from(e.target.files);
    otherImagePreviews.value = form.other_images.map(file => URL.createObjectURL(file));
};
</script>
