<!-- <template>
    <div id="img-zoomer-box" ref="zoomerBox">
        <img :src="image" id="img-1" />
        <div id="img-2"></div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// Accept the image URL as a prop
const props = defineProps({
    image: {
        type: String,
        required: true,
    }
})

const zoomerBox = ref(null)

onMounted(() => {
    const imageBox = zoomerBox.value
    const original = imageBox.querySelector('#img-1')
    const magnified = imageBox.querySelector('#img-2')

    // Set the magnifier background to the same image
    magnified.style.backgroundImage = `url(${props.image})`
    magnified.style.backgroundSize = original.offsetWidth + 'px auto'

    function handleMouseMoves(e) {
        const style = magnified.style
        // Get bounding rectangle for better calculations
        const rect = imageBox.getBoundingClientRect()
        // Calculate mouse coordinates relative to the box
        const x = e.clientX - rect.left
        const y = e.clientY - rect.top
        const imgWidth = original.offsetWidth
        const imgHeight = original.offsetHeight

        // Calculate percentage values
        let xperc = (x / imgWidth) * 100
        let yperc = (y / imgHeight) * 100

        // Allow slight overshoot from image edges
        if (x >= 0.01 * imgWidth) {
            xperc *= 1.15
        }
        if (y >= 0.01 * imgHeight) {
            yperc *= 1.15
        }

        // Update the background position of the magnifier
        style.backgroundPositionX = `${xperc - 9}%`
        style.backgroundPositionY = `${yperc - 9}%`
        // Position the magnifier centered on the cursor (adjust offsets as desired)
        style.left = `${x - 180}px`
        style.top = `${y - 180}px`
    }

    // Add mouse move listener to the box
    imageBox.addEventListener("mousemove", handleMouseMoves)
})
</script>

<style scoped>
/* Container for the image and the magnifier */
#img-zoomer-box {
    width: 500px;
    position: relative;
    margin-top: 10px;
}

/* The main image; occupies full width of the box */
#img-1 {
    width: 100%;
    display: block;
}

/* Change cursor when hovering */
#img-zoomer-box:hover,
#img-zoomer-box:active {
    cursor: zoom-in;
}

/* Show magnifier when hovering over container */
#img-zoomer-box:hover #img-2,
#img-zoomer-box:active #img-2 {
    opacity: 1;
}

/* Magnifier (zoomed portion) styles */
#img-2 {
    width: 340px;
    height: 340px;
    background: url('') no-repeat #FFF;
    /* Background image is set dynamically */
    box-shadow: 0 5px 10px -2px rgba(0, 0, 0, 0.3);
    pointer-events: none;
    position: absolute;
    opacity: 0;
    border: 4px solid whitesmoke;
    z-index: 99;
    border-radius: 100%;
    transition: opacity 0.2s;
}
</style> -->



<!-- ZoomableImage.vue -->
<template>
    <div ref="zoomerBox" class="relative group cursor-zoom-in">
        <img :src="image" alt="Zoomable" class="w-full h-auto max-h-[90vh] max-w-[90vw]" :data-flip-id="flipId"
            ref="imgRef" />
        <div ref="magnified"
            class="absolute z-50 w-[300px] h-[300px] border-4 border-white rounded-full shadow-md opacity-0 pointer-events-none transition-opacity duration-200">
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, onUnmounted } from 'vue'

const props = defineProps({
    image: String,
    flipId: String,
    enabled: Boolean,
})

const zoomerBox = ref(null)
const imgRef = ref(null)
const magnified = ref(null)

function handleMouseMove(e) {
    const imageBox = zoomerBox.value
    const original = imgRef.value
    const zoom = magnified.value

    const rect = imageBox.getBoundingClientRect()
    const x = e.clientX - rect.left
    const y = e.clientY - rect.top
    const imgWidth = original.offsetWidth
    const imgHeight = original.offsetHeight

    let xperc = (x / imgWidth) * 100
    let yperc = (y / imgHeight) * 100

    if (x >= 0.01 * imgWidth) xperc *= 1.15
    if (y >= 0.01 * imgHeight) yperc *= 1.15

    zoom.style.backgroundPositionX = `${xperc - 9}%`
    zoom.style.backgroundPositionY = `${yperc - 9}%`
    zoom.style.left = `${x - 150}px`
    zoom.style.top = `${y - 150}px`
}

onMounted(() => {
    if (props.enabled) {
        const zoom = magnified.value
        zoom.style.backgroundImage = `url(${props.image})`
        zoom.style.backgroundSize = `${imgRef.value.offsetWidth * 3.5}px auto`
        zoomerBox.value.addEventListener('mousemove', handleMouseMove)
        zoomerBox.value.addEventListener('mouseenter', () => {
            zoom.style.opacity = 1
        })
        zoomerBox.value.addEventListener('mouseleave', () => {
            zoom.style.opacity = 0
        })
    }
})

onUnmounted(() => {
    zoomerBox.value?.removeEventListener('mousemove', handleMouseMove)
})
</script>
