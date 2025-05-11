<!-- <template>
    <div class="tab-content">
        <div class="grid grid-cols-1 md:grid-cols-3 text-base gap-4 space-y-5 m-12">
            <ul class="text-gray-600">
                <li v-if="property.area">
                    <CheckIcon />
                    Lot Area: {{ property.area }} SQ FT
                </li>
                <li v-if="property.area">
                    <CheckIcon />
                    Floor Area: {{ property.area }} SQ FT
                </li>
                <li v-if="property.bedrooms">
                    <CheckIcon />
                    Bed Rooms: {{ property.bedrooms }}
                </li>
                <li v-if="property.bathrooms">
                    <CheckIcon />
                    Bath Rooms: {{ property.bathrooms }}
                </li>
                <li>
                    <CheckIcon />
                    Garage: 2
                </li>
            </ul>
            <ul class="text-gray-600">
                <li class="check" v-if="property.yearBuilt">
                    <CheckIcon />
                    Year Built: {{ property.yearBuilt }}
                </li>
                <li class="check" v-if="property.water">
                    <CheckIcon />
                    Water: Yes
                </li>
                <li class="check" v-if="property.stories">
                    <CheckIcon />
                    Stories: {{ property.stories }}
                </li>
                <li class="check">
                    <CheckIcon />
                    Roofing: New
                </li>
                <li class="check">
                    <CheckIcon />
                    Luggage : {{ property.luggage ? "Yes" : "No" }}
                </li>
            </ul>
            <ul class="text-gray-600">
                <li v-for="(amenity, index) in amenityList" :key="index">
                    <CheckIcon />
                    <strong></strong> {{ amenity }}
                </li>
            </ul>
        </div>

        <div class="w-full h-96 sm:h-[500px] md:h-[600px] lg:h-[500px]">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d435519.22744465544!2d74.00471526778067!3d31.4831036539254!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39190483e58107d9%3A0xc23abe6ccc7e2462!2sLahore%2C%20Pakistan!5e0!3m2!1sen!2s!4v1744810478934!5m2!1sen!2s"
                class="w-full h-full border-0" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

    </div>
</template>

<script setup>
import { computed } from 'vue'
import CheckIcon from './CheckIcon.vue';
const { property } = defineProps({
    property: {
        type: Object,
        required: true,
    },
})

const amenityList = computed(() => property.amenity_list.split(','))
</script> -->



<template>
    <div class="tab-content">
        <div class="grid grid-cols-1 md:grid-cols-3 text-base gap-4 space-y-5 m-12">
            <!-- Column 1 -->
            <ul class="text-gray-600 space-y-2">
                <li v-if="property.lot_area">
                    <CheckIcon /> Lot Area: {{ property.lot_area }} SQ FT
                </li>
                <li v-if="property.floor_area">
                    <CheckIcon /> Floor Area: {{ property.floor_area }} SQ FT
                </li>
                <li v-if="property.bedrooms">
                    <CheckIcon /> Bed Rooms: {{ property.bedrooms }}
                </li>
                <li v-if="property.bathrooms">
                    <CheckIcon /> Bath Rooms: {{ property.bathrooms }}
                </li>
                <li v-if="property.garage">
                    <CheckIcon /> Garage: {{ property.garage }}
                </li>
            </ul>

            <!-- Column 2 -->
            <ul class="text-gray-600 space-y-2">
                <li>
                    <CheckIcon /> Year Built: {{ property.year_built ?? 'N/A' }}
                </li>
                <li>
                    <CheckIcon /> Water: {{ property.is_water ? 'Yes' : 'No' }}
                </li>
                <li>
                    <CheckIcon /> Stories: {{ property.stories ?? 'N/A' }}
                </li>
                <li>
                    <CheckIcon /> Roofing: {{ property.is_new_roofing ? 'New' : 'Old' }}
                </li>
                <li>
                    <CheckIcon /> Luggage: {{ property.is_luggage ? 'Yes' : 'No' }}
                </li>
            </ul>

            <!-- Column 3: Amenities -->
            <ul class="text-gray-600 space-y-2">
                <li v-for="(amenity, index) in property.amenities" :key="index">
                    <CheckIcon /> {{ amenity.name }}
                </li>
            </ul>
        </div>

        <!-- Map -->
        <div class="w-full h-96 sm:h-[500px] md:h-[600px] lg:h-[500px]">
            <iframe :src="googleMapSrc" class="w-full h-full border-0" allowfullscreen loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</template>

<script setup>
import CheckIcon from './CheckIcon.vue';
import { computed } from 'vue';
const { property } = defineProps({
    property: {
        type: Object,
        required: true,
    },
});

// Optional: Generate dynamic Google Maps embed link using lat/lng
const googleMapSrc = computed(() => {
    if (property.latitude && property.longitude) {
        return `https://www.google.com/maps?q=${property.latitude},${property.longitude}&output=embed`;
    }
    return "https://www.google.com/maps/embed?..."; // fallback default
});
</script>
