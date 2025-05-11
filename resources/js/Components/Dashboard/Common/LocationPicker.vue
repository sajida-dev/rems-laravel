<!-- <template>
    <div>
        <div id="map" class="h-96 w-full rounded-lg mb-4"></div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 font-semibold">Latitude</label>
                <input type="text" :value="lat" class="input" readonly />
            </div>

            <div>

                <label class="block mb-1 font-semibold">Longitude</label>
                <input type="text" :value="lng" class="input" readonly />
            </div>
        </div>
    </div>
</template>

<script setup>
import InputError from '@/Components/Default/InputError.vue'
import InputLabel from '@/Components/Default/InputLabel.vue'
import TextInput from '@/Components/Default/TextInput.vue'
import L from 'leaflet'
import { onMounted, watch, defineEmits, defineProps } from 'vue'

const emit = defineEmits(['update:lat', 'update:lng'])
const props = defineProps({
    lat: Number,
    lng: Number,
})

let map, marker

onMounted(() => {
    map = L.map('map').setView([props.lat || 31.5204, props.lng || 74.3587], 13)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map)

    marker = L.marker([props.lat || 31.5204, props.lng || 74.3587], {
        draggable: true
    }).addTo(map)

    marker.on('dragend', function (e) {
        const { lat, lng } = marker.getLatLng()
        emit('update:lat', lat)
        emit('update:lng', lng)
    })

    map.on('click', function (e) {
        const { lat, lng } = e.latlng
        marker.setLatLng(e.latlng)
        emit('update:lat', lat)
        emit('update:lng', lng)
    })
})
</script>

<style>
#map {
    z-index: 1;
}

.input {
    @apply w-full px-3 py-2 border rounded;
}
</style> -->



<template>
    <div>
        <div id="map" class="h-96 w-full rounded-lg mb-4"></div>

        <div class="grid grid-cols-2 gap-4">
            <!-- Latitude -->
            <div>
                <InputLabel for="latitude" value="Latitude" />
                <TextInput id="latitude" :value="latitude" @input="$emit('update:latitude', $event.target.value)"
                    class="mt-1 block w-full" placeholder="e.g. 31.5204" readonly />
                <InputError class="mt-2" :message="errors?.latitude" />
            </div>

            <!-- Longitude -->
            <div>
                <InputLabel for="longitude" value="Longitude" />
                <TextInput id="longitude" :value="longitude" @input="$emit('update:longitude', $event.target.value)"
                    class="mt-1 block w-full" placeholder="e.g. 74.3587" readonly />
                <InputError class="mt-2" :message="errors?.longitude" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import L from 'leaflet'
import InputError from '@/Components/Default/InputError.vue'
import InputLabel from '@/Components/Default/InputLabel.vue'
import TextInput from '@/Components/Default/TextInput.vue'

const emit = defineEmits(['update:latitude', 'update:longitude'])

const props = defineProps({
    latitude: Number,
    longitude: Number,
    errors: Object,
})

let map, marker

onMounted(() => {
    const initialLat = props.latitude || 31.5204
    const initialLng = props.longitude || 74.3587

    map = L.map('map').setView([initialLat, initialLng], 13)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
    }).addTo(map)

    marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map)

    marker.on('dragend', () => {
        const { lat, lng } = marker.getLatLng()
        emit('update:latitude', lat)
        emit('update:longitude', lng)
    })

    map.on('click', (e) => {
        const { lat, lng } = e.latlng
        marker.setLatLng([lat, lng])
        emit('update:latitude', lat)
        emit('update:longitude', lng)
    })
})
</script>

<style>
#map {
    z-index: 1;
}
</style>
