<template>
    <section class="py-16">
        <div class="container">
            <div class="row justify-center">
                <SectionHeading subheading="Our Services" title="The smartest way to buy a home" />
            </div>
            <!-- Services -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div v-for="(service, index) in services" :key="index"
                    class="flex flex-col items-center p-6 bg-white  transition-shadow">
                    <!-- Icon -->
                    <div class="flex justify-center items-center w-20 h-20 mb-4">
                        <i :class="service.icon" class="text-5xl text-pink-400"></i>
                    </div>
                    <!-- Service Details -->
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-gray-800">{{ service.title }}</h3>
                        <p class="text-gray-600">{{ service.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup>
import SectionHeading from '@/components/Public/Common/SectionHeading.vue'

</script>

<script>
export default {
    data() {
        return {
            services: [
                {
                    title: 'No Downpayment',
                    description: 'A small river named Duden flows by their place and supplies it with the necessary regelialia.',
                    icon: 'fas fa-piggy-bank',
                },
                {
                    title: 'All Cash Offer',
                    description: 'A small river named Duden flows by their place and supplies it with the necessary regelialia.',
                    icon: 'fas fa-wallet',
                },
                {
                    title: 'Experts in Your Corner',
                    description: 'A small river named Duden flows by their place and supplies it with the necessary regelialia.',
                    icon: 'fas fa-file-alt',
                },
                {
                    title: 'Locked in Pricing',
                    description: 'A small river named Duden flows by their place and supplies it with the necessary regelialia.',
                    icon: 'fas fa-lock',
                },
            ],
        };
    },
};
</script>

<style scoped></style>
