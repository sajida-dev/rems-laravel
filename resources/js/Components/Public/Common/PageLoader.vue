<template>
    <div v-if="loading" id="ftco-loader"
        class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-90">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#f3f3f3" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
                stroke="#d23f95" />
        </svg>
    </div>
</template>

<script>
export default {
    name: 'PageLoader',
    props: {
        loading: {
            type: Boolean,
            required: true
        }
    }
}
</script>

<style scoped>
#ftco-loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
}

.circular {
    animation: rotate 2s linear infinite;
    transform-origin: center center;
}

.path {
    stroke-dasharray: 150, 200;
    stroke-dashoffset: -10;
    animation: dash 1.5s ease-in-out infinite;
}

@keyframes dash {
    0% {
        stroke-dasharray: 1, 200;
        stroke-dashoffset: 0;
    }

    50% {
        stroke-dasharray: 90, 150;
        stroke-dashoffset: -35;
    }

    100% {
        stroke-dasharray: 90, 150;
        stroke-dashoffset: -124;
    }
}

@keyframes rotate {
    100% {
        transform: rotate(360deg);
    }
}
</style>
