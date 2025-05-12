<template>

    <Head title="Edit Property" />
    <div class="bg-white rounded w-[95%] p-5 mx-auto">
        <FormLayout :title="'Edit Property'" :submitLabel="'Update Property'" :routeName="'properties.update'"
            :routeParams="{ property: property.id }" :fields="form">
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
                        <InputLabel for="rent-price" value="Rent Price" />
                        <TextInput id="rent-price" v-model="form.rent_price" placeholder="129000.0" type="number"
                            class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="errors.rent_price" />
                    </div>
                    <div>
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
                        <InputLabel for="lot_area" value="Lot Area (SQ FT)" />
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

                    <div class="mt-6">
                        <InputLabel value="Main Image" />
                        <input type="file" @change="handleMainImage" @input="form.main_image = $event.target.files[0]"
                            accept="image/*" />
                        <div v-if="mainImagePreview" class="mt-2 rounded-md overflow-hidden border">
                            <img :src="mainImagePreview" class="object-cover w-full h-full" />
                        </div>
                        <InputError class="mt-2" :message="errors.main_image" />
                    </div>



                    <div class="sm:col-span-2 mt-4">
                        <InputLabel value="Other Images" />

                        <!-- File input for new images -->
                        <input type="file" accept="image/*" multiple @change="handleOtherImages"
                            @input="form.other_images = Array.from($event.target.files)" class="mt-1" />

                        <div class="mt-3 flex flex-wrap gap-2">
                            <!-- Existing uploads -->
                            <div v-for="(upload, idx) in existingUploads" :key="upload.id"
                                class="relative w-56 h-52 border rounded-md overflow-hidden">
                                <!-- delete button -->
                                <button @click.prevent="promptDelete(upload, idx)"
                                    class="absolute top-1 right-1 bg-white rounded-full pl-1.5 pr-1.5 shadow">
                                    ×
                                </button>

                                <img :src="`/storage/${upload.image_path}`" class="object-cover w-full h-full" />
                            </div>

                            <!-- Newly selected files -->
                            <div v-for="(src, idx) in otherImagePreviews" :key="`new-${idx}`"
                                class="relative w-56 h-52 border rounded-md overflow-hidden">
                                <!-- remove-new button -->
                                <button @click.prevent="removeNewImage(idx)"
                                    class="absolute top-1 right-1 bg-white rounded-full pl-1.5 pr-1.5  shadow">
                                    ×
                                </button>

                                <img :src="src" class="object-cover w-full h-full" />
                            </div>
                        </div>

                        <InputError class="mt-2" :message="errors.other_images" />

                        <!-- Confirmation Dialog -->
                        <ConfirmDialog v-if="showConfirm" :visible="showConfirm" :title="`Delete this image?`"
                            message="This will remove the image from the property." @confirm="handleConfirmDelete"
                            @cancel="showConfirm = false" />
                    </div>
                </div>
            </template>
        </FormLayout>
    </div>

</template>

<script setup>
import { ref, provide, computed, reactive } from 'vue'
import { Head } from '@inertiajs/vue3'
import FormLayout from '@/Components/Dashboard/Common/FormLayout.vue'
import TextInput from '@/Components/Default/TextInput.vue'
import InputLabel from '@/Components/Default/InputLabel.vue'
import InputError from '@/Components/Default/InputError.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import LocationPicker from '@/Components/Dashboard/Common/LocationPicker.vue'
import SpecializationSelect from '@/Components/Dashboard/Common/SpecializationSelect.vue'
import ConfirmDialog from '@/Components/Dashboard/Common/ConfirmDialog.vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
    property: Object,
    amenities: Array,
    categories: Array
})

const initialFields = computed(() => ({
    title: props.property.title,
    description: props.property.description,
    location: props.property.location,
    rent_price: props.property.rent_price,
    purchase_price: props.property.purchase_price,
    bedrooms: props.property.bedrooms,
    bathrooms: props.property.bathrooms,
    lot_area: props.property.lot_area,
    floor_area: props.property.floor_area,
    garage: props.property.garage,
    year_built: props.property.year_built,
    stories: props.property.stories,
    is_water: props.property.is_water,
    is_new_roofing: props.property.is_new_roofing,
    is_luggage: props.property.is_luggage,
    category_id: props.property.category_id,
    // amenities: props.property.amenities.map(a => a.id) || [],
    amenities: Array.isArray(props.property.amenities)
        ? props.property.amenities.map(a => a.id)
        : [],
    latitude: props.property.latitude,
    longitude: props.property.longitude,
    main_image: null,
    other_images: [],
}))

const form = reactive({
    ...initialFields.value,
})

const mainImagePreview = ref(
    props.property.image_url
        ? `/storage/${props.property.image_url}`
        : null
)

const existingUploads = ref(
    (props.property.uploads || []).map(u => ({
        id: u.id,
        image_path: u.image_path
    }))
)

const otherImagePreviews = ref([])

// File handlers
function handleMainImage(e) {
    const file = e.target.files[0]
    if (!file) return
    form.main_image = file
    mainImagePreview.value = URL.createObjectURL(file)
}

function handleOtherImages(e) {
    const files = Array.from(e.target.files || [])
    if (!files.length) return
    form.other_images = files
    const newPreviews = files.map(f => URL.createObjectURL(f))
    otherImagePreviews.value = newPreviews
}

// Delete existing upload confirmation state
const showConfirm = ref(false)
const uploadToDelete = ref(null)
const deleteIndex = ref(null)

function promptDelete(upload, idx) {
    uploadToDelete.value = upload
    deleteIndex.value = idx
    showConfirm.value = true
}

async function handleConfirmDelete() {
    showConfirm.value = false
    const { id } = uploadToDelete.value
    const url = route('uploads.destroy', { upload: id })

    console.log('Deleting upload at:', url)

    try {
        await axios.delete(url)
        existingUploads.value.splice(deleteIndex.value, 1)
        toast.success('Image deleted')
    } catch (e) {
        console.error('Delete failed response:', e.response || e)
        toast.error('Failed to delete image')
    }
}


// const mainImagePreview = ref(
//     props.property.image_url
//         ? `/storage/${props.property.image_url}`
//         : null
// )
// const otherImagePreviews = ref(
//     (props.property.uploads || []).map((u) => `/storage/${u.image_path}`)
// )

// const handleMainImage = (e) => {
//     const file = e.target.files[0]
//     if (!file) return

//     form.main_image = file
//     mainImagePreview.value = URL.createObjectURL(file)
// }

// const handleOtherImages = (e) => {
//     const files = Array.from(e.target.files || [])
//     if (!files.length) return

//     form.other_images = files

//     const newPreviews = files.map(f => URL.createObjectURL(f))
//     otherImagePreviews.value = [
//         ...otherImagePreviews.value,
//         ...newPreviews,
//     ]
// }

provide('layoutHeader', {
    title: 'Property Management',
    mainPage: 'Dashboard',
    page: 'Edit Property'
})
</script>
