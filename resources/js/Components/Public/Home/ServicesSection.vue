<template>
    <section class="py-16">
        <div class="container">
            <div class="row justify-center">
                <div class="col-md-12 text-center mb-5">
                    <!-- Services Section Title with Line -->
                    <div class="flex items-center my-8 w-64 mx-auto">
                        <hr class="flex-grow border-t-2 border-pink-300">
                        <span class="subheading mx-4 text-base text-pink-700">Our Services</span>
                        <hr class="flex-grow border-t-2 border-pink-300">
                    </div>

                    <!-- Subtitle -->
                    <h2 class="mb-2 text-5xl  text-gray-800">The smartest way to buy a home</h2>
                </div>
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

<script>
export default {
    data() {
        return {
            services: [
                {
                    title: 'No Downpayment',
                    description: 'A small river named Duden flows by their place and supplies it with the necessary regelialia.',
                    icon: 'fas fa-piggy-bank',  // Updated to FontAwesome 5+ icons
                },
                {
                    title: 'All Cash Offer',
                    description: 'A small river named Duden flows by their place and supplies it with the necessary regelialia.',
                    icon: 'fas fa-wallet',  // Updated to FontAwesome 5+ icons
                },
                {
                    title: 'Experts in Your Corner',
                    description: 'A small river named Duden flows by their place and supplies it with the necessary regelialia.',
                    icon: 'fas fa-file-alt',  // Updated to FontAwesome 5+ icons
                },
                {
                    title: 'Locked in Pricing',
                    description: 'A small river named Duden flows by their place and supplies it with the necessary regelialia.',
                    icon: 'fas fa-lock',  // Updated to FontAwesome 5+ icons
                },
            ],
        };
    },
};
</script>

<style scoped></style>
