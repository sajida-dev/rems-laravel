<template>
    <div v-if="loading" id="ftco-loader"
        class="show fullscreen fixed inset-0 z-50 flex items-center justify-center bg-white">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
                stroke="#F96D00" />
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
.path {
    stroke-dasharray: 150, 200;
    stroke-dashoffset: -10;
    animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
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
</style>
