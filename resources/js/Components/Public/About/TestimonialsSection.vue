<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';

const testimonials = [
    {
        text: "Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.",
        name: "Roger Scott",
        position: "Marketing Manager",
        image: "/frontend/images/person_1.jpg",
    },
    {
        text: "Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.",
        name: "Roger Scott",
        position: "Marketing Manager",
        image: "/frontend/images/person_2.jpg",
    },
    {
        text: "Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.",
        name: "Roger Scott",
        position: "Marketing Manager",
        image: "/frontend/images/person_3.jpg",
    },
];
</script>

<template>
    <section class="py-16 bg-gray-50" id="testimonials">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-lg text-indigo-500 uppercase tracking-wide">Testimonial</h2>
                <h1 class="text-3xl font-bold text-gray-800 mt-2">Happy Clients</h1>
            </div>

            <Swiper :spaceBetween="30" :slidesPerView="1" :loop="true" class="max-w-3xl mx-auto">
                <SwiperSlide v-for="(testimonial, index) in testimonials" :key="index">
                    <div class="bg-white shadow-lg rounded-2xl p-8">
                        <p class="text-gray-600 italic mb-6">{{ testimonial.text }}</p>
                        <div class="flex items-center">
                            <div class="w-16 h-16 rounded-full bg-cover bg-center mr-4"
                                :style="{ backgroundImage: `url(${testimonial.image})` }"></div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ testimonial.name }}</p>
                                <span class="text-gray-500 text-sm">{{ testimonial.position }}</span>
                            </div>
                        </div>
                    </div>
                </SwiperSlide>
            </Swiper>
        </div>
    </section>
</template>

<style scoped>
/* Optional: Add Swiper navigation/pagination styles if needed */
</style>
