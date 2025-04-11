<!-- <template>
  <section class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-2">
        <div
          class="min-h-[300px] bg-cover bg-center"
          :style="{ backgroundImage: `url(${imageUrl})` }"
          role="img"
          :aria-label="imageAlt"
        ></div>

        <div class="flex items-center">
          <div class="p-6 md:p-10">
            <h2 class="text-3xl md:text-4xl font-semibold text-gray-900 mb-6">
              {{ title }}
            </h2>

            <p class="text-gray-700 leading-relaxed mb-4">
              {{ paragraph1 }}
            </p>

            <p class="text-gray-700 leading-relaxed">
              {{ paragraph2 }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    default: 'We Put People First.',
  },
  imageUrl: {
    type: String,
    default: '/images/about.jpg',
  },
  imageAlt: {
    type: String,
    default: 'About our company',
  },
  paragraph1: {
    type: String,
    default:
      'A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.',
  },
  paragraph2: {
    type: String,
    default:
      'On her way she met a copy. The copy warned the Little Blind Text, that where it came from it would have been rewritten a thousand times and everything that was left from its origin would be the word "and"...',
  },
});
</script> -->



<template>
  <section class="py-16">
    <div class="container mx-auto">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        
        <!-- Image Section -->
        <div 
          class="min-h-[300px] bg-cover bg-center rounded-xl shadow-lg"
          :style="{ backgroundImage: 'url(/images/about.jpg)' }">
        </div>

        <!-- Content Section -->
        <div class="space-y-6">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800 leading-tight">
            We Put People First.
          </h2>

          <p class="text-gray-600">
            A small river named Duden flows by their place and supplies it with the necessary regelialia.
            It is a paradisematic country, in which roasted parts of sentences fly into your mouth.
          </p>

          <p class="text-gray-600">
            On her way she met a copy. The copy warned the Little Blind Text, that where it came from
            it would have been rewritten a thousand times and everything that was left from its origin
            would be the word "and" and the Little Blind Text should turn around and return to its own,
            safe country.
          </p>
        </div>

      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'AboutSection',
}
</script>

<style scoped>
/* Add any additional custom styling here */
</style>
