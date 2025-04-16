<template>
    <section class="py-16 bg-gray-50" id="section-counter">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-5xl text-center mb-12 text-gray-800 ">
                Its all about Number's.
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-center">

                <div></div>

                <!-- Total Properties -->
                <div class="counter-wrap">
                    <div class="py-6 mb-4 ">
                        <div class="flex flex-col items-center justify-center">
                            <strong class="text-5xl font-bold text-gray-800" ref="totalProperties">{{ displayProperties
                            }}</strong>
                            <span class="mt-2 text-lg text-gray-600">Total <br>Properties</span>
                        </div>
                    </div>
                </div>

                <!-- Average House -->
                <div class="counter-wrap">
                    <div class="py-6 mb-4">
                        <div class="flex flex-col items-center justify-center">
                            <strong class="text-5xl font-bold text-gray-800" ref="averageHouse">{{ displayAverage
                            }}</strong>
                            <span class="mt-2 text-lg text-gray-600">Average <br>House</span>
                        </div>
                    </div>
                </div>

                <div></div>

            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "CounterSection",
    props: {
        totalProperties: {
            type: Number,
            required: true,
        },
        averageHouse: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            displayProperties: 0,
            displayAverage: 0,
        };
    },
    mounted() {
        this.animateCounter("displayProperties", this.totalProperties);
        this.animateCounter("displayAverage", this.averageHouse);
    },
    methods: {
        animateCounter(target, endValue) {
            let start = 0;
            const duration = 1000;
            const stepTime = Math.abs(Math.floor(duration / endValue));
            const timer = setInterval(() => {
                start += 1;
                this[target] = start;
                if (start >= endValue) clearInterval(timer);
            }, stepTime);
        },
    },
};
</script>

<style scoped>
.counter-wrap {
    transition: transform 0.3s ease;
}

.counter-wrap:hover {
    transform: translateY(-5px);
}
</style>
