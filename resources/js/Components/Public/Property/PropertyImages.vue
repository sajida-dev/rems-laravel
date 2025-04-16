<!-- <template>
    <div class="tab-content">


        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <div v-for="(img, index) in images" :key="index" class="cursor-pointer" @click="openModal(index)">
                <img :src="img" alt="Property Image"
                    class="rounded-md w-full h-80 object-cover hover:opacity-80 transition" />
            </div>
        </div>

        <div v-if="modalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50"
            @click.self="closeModal">
            <div class="relative w-full h-full flex items-center justify-center">
                <button class="absolute top-4 right-4 text-white text-3xl z-50" @click="closeModal">
                    &times;
                </button>

                <img :src="images[currentImageIndex]"
                    class="max-h-[90vh] max-w-[90vw] transition-transform duration-500 ease-in-out" ref="zoomImg"
                    :data-flip-id="`image-${currentImageIndex}`" />
            </div>
        </div>
        <ZoomableImage v-for="(img, index) in images" :key="index" :image="images[currentImageIndex]" />
    </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'
import gsap from 'gsap'
import Flip from 'gsap/Flip'
import ZoomableImage from './ZoomableImage.vue'

gsap.registerPlugin(Flip)

const props = defineProps({
    images: {
        type: Array,
        required: true,
    },
})

const modalOpen = ref(false)
const currentImageIndex = ref(0)
const zoomImg = ref(null)
let lastState = null

const openModal = async (index) => {
    currentImageIndex.value = index
    const elem = document.querySelector(`[data-flip-id="image-${index}"]`)
    if (elem) {
        lastState = Flip.getState(elem)
    }

    modalOpen.value = true

    await nextTick()
    const newElem = document.querySelector(`[data-flip-id="image-${index}"]`)
    if (lastState && newElem) {
        Flip.from(lastState, {
            targets: newElem,
            duration: 1,
            ease: 'power2.inOut',
            absolute: true,
        })
    }
}

const closeModal = () => {
    const elem = document.querySelector(
        `[data-flip-id="image-${currentImageIndex.value}"]`
    )
    if (elem) {
        lastState = Flip.getState(elem)
    }

    modalOpen.value = false

    nextTick(() => {
        const newElem = document.querySelector(
            `[data-flip-id="image-${currentImageIndex.value}"]`
        )
        if (lastState && newElem) {
            Flip.from(lastState, {
                targets: newElem,
                duration: 0.2,
                ease: 'power2.inOut',
                absolute: true,
            })
        }
    })
}
</script> -->
<template>
    <div class="tab-content">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <div v-for="(img, index) in images" :key="index" class="cursor-pointer" @click="openModal(index)">
                <img :src="img" alt="Property Image"
                    class="rounded-md w-full h-80 object-cover hover:opacity-80 transition"
                    :data-flip-id="`image-${index}`" />
            </div>
        </div>

        <!-- Modal with Zoom Effect -->
        <div v-if="modalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50"
            @click.self="closeModal">
            <div class="relative w-full h-full flex items-center justify-center">
                <button class="absolute top-4 right-4 text-white text-3xl z-50" @click="closeModal">
                    &times;
                </button>

                <ZoomableImage :image="images[currentImageIndex]" :flipId="`image-${currentImageIndex}`"
                    :enabled="true" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import gsap from 'gsap'
import Flip from 'gsap/Flip'
import ZoomableImage from './ZoomableImage.vue'

gsap.registerPlugin(Flip)

const props = defineProps({
    images: {
        type: Array,
        required: true,
    },
})

const modalOpen = ref(false)
const currentImageIndex = ref(0)
let lastState = null

const openModal = async (index) => {
    currentImageIndex.value = index
    const elem = document.querySelector(`[data-flip-id="image-${index}"]`)
    if (elem) {
        lastState = Flip.getState(elem)
    }

    modalOpen.value = true

    await nextTick()
    const newElem = document.querySelector(`[data-flip-id="image-${index}"]`)
    if (lastState && newElem) {
        Flip.from(lastState, {
            targets: newElem,
            duration: 1,
            ease: 'power2.inOut',
            absolute: true,
        })
    }
}

const closeModal = () => {
    const elem = document.querySelector(
        `[data-flip-id="image-${currentImageIndex.value}"]`
    )
    if (elem) {
        lastState = Flip.getState(elem)
    }

    modalOpen.value = false

    nextTick(() => {
        const newElem = document.querySelector(
            `[data-flip-id="image-${currentImageIndex.value}"]`
        )
        if (lastState && newElem) {
            Flip.from(lastState, {
                targets: newElem,
                duration: 0.2,
                ease: 'power2.inOut',
                absolute: true,
            })
        }
    })
}
</script>
