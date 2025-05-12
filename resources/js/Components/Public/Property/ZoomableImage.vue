<template>
    <div ref="zoomerBox" class="relative group cursor-zoom-in">
        <img :src="`/storage/${image.image_path}`" alt="Zoomable" class="w-full h-auto max-h-[90vh] max-w-[90vw]"
            :data-flip-id="flipId" ref="imgRef" />
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
        zoom.style.backgroundImage = `url(/storage/${props.image.image_path})`
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
