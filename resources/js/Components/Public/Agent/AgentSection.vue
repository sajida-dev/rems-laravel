<template>
    <section class="py-16 bg-white mb-16">
        <div class="container mx-auto px-4">
            <!-- Heading -->
            <div class="text-center mb-12">
                <div class="flex items-center my-8 w-64 mx-auto">
                    <hr class="flex-grow border-t-2 border-pink-300">
                    <span class="subheading mx-4 text-base text-pink-400 uppercase tracking-widest">Agents</span>
                    <hr class="flex-grow border-t-2 border-pink-300">
                </div>
                <h2 class="text-3xl font-semibold">Our Agents</h2>
            </div>

            <!-- Agent Cards -->
            <div class="flex flex-wrap -mx-4">
                <div v-for="agent in agents" :key="agent.id" class="w-full md:w-1/2 lg:w-1/4 px-4 mb-10">
                    <div class="relative w-full z-0">
                        <!-- Agent Image -->
                        <div class="relative overflow-hidden custom-img">
                            <img :src="agent.profile_pic ? `${agent.profile_pic}` : 'frontend/images/avatar.png'"
                                alt="Agent Image"
                                class="w-full h-full object-cover transition-transform duration-500 transform hover:scale-110" />
                        </div>

                        <!-- Description Card -->
                        <div
                            class="relative w-[85%] md:w-[80%] bg-white p-5 -mt-[50px] ml-5 shadow-[0px_5px_21px_-14px_rgba(0,0,0,0.14)]">
                            <h3 class="text-[20px] font-medium leading-tight mb-2">
                                <a :href="`/single-agent/${agent.id}`" class="text-black hover:text-pink-500">
                                    {{ agent.name }}
                                </a>
                            </h3>
                            <p class="text-[16px] m-0">
                                <span class="text-gray-500">Listing</span>
                                <span class="text-black ml-1">— {{ agent.property_count }} Properties</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const agents = [
    {
        id: 1,
        name: 'John Doe',
        profile_pic: 'frontend/images/team-1.jpg',
        property_count: 12,
    },
    {
        id: 2,
        name: 'Jane Smith',
        profile_pic: '',
        property_count: 8,
    },
    {
        id: 3,
        name: 'Mike Johnson',
        profile_pic: 'frontend/images/team-2.jpg',
        property_count: 5,
    },
    {
        id: 4,
        name: 'Sara Lee',
        profile_pic: '',
        property_count: 20,
    },
]
</script>

<style scoped>
.custom-img {
    height: 300px;
    overflow: hidden;
}
</style>
