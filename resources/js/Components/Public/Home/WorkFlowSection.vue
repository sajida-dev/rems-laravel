<template>
    <section
        class="relative my-28 pb-[10em] lg:mx-10 mx-2 bg-cover bg-center overflow-hidden 
    before:content-[''] before:absolute before:left-0 before:bottom-[-30px] before:w-1/2 before:h-[52px] before:z-[1] before:[transform:skewY(4deg)] before:bg-white
    after:content-[''] after:absolute after:right-0 after:bottom-[-30px] after:w-1/2 after:h-[52px] after:z-[1] after:[transform:skewY(-4deg)] after:bg-white"
        style="background-image: url('frontend/images/bg_2.jpg')">
        <div class="absolute inset-0 w-full opacity-40 md:w-1/2 md:opacity-100 bg-black"></div>

        <div class="container relative transform py-12">
            <!-- Heading -->
            <div class="flex justify-start my-14">
                <div class="w-full md:w-1/2 text-center ftco-animate">
                    <div class="flex items-center my-2 w-64 mx-auto">
                        <hr class="flex-grow border-t-2 border-pink-500">
                        <span class="subheading mx-4 text-base text-white uppercase tracking-widest">Work flow</span>
                        <hr class="flex-grow border-t-2 border-pink-500">
                    </div>
                    <h2 class="mb-3 text-5xl text-white">How it works</h2>
                </div>
            </div>

            <!-- Workflow Items Grid -->
            <div class="flex flex-wrap">
                <div class="w-full md:w-1/2">
                    <div class="flex flex-wrap">
                        <WorkflowItem v-for="(item, index) in workflowItems" :key="index" :number="item.number"
                            :title="item.title" :description="item.description" />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import WorkflowItem from '@/components/Public/Home/WorkflowItem.vue'

const workflowItems = [
    {
        number: '01',
        title: 'Evaluate Property',
        description:
            'A small river named Duden flows by their place and supplies it with the necessary regelialia.'
    },
    {
        number: '02',
        title: 'Meet Your Agent',
        description:
            'A small river named Duden flows by their place and supplies it with the necessary regelialia.'
    },
    {
        number: '03',
        title: 'Close the Deal',
        description:
            'A small river named Duden flows by their place and supplies it with the necessary regelialia.'
    },
    {
        number: '04',
        title: 'Have Your Property',
        description:
            'A small river named Duden flows by their place and supplies it with the necessary regelialia.'
    }
]
</script>
