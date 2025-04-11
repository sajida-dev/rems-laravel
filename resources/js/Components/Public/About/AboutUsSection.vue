<template>
  <section class="py-16 overflow-x-hidden">
    <div class="container mx-auto px-10">
      <div class="grid grid-cols-1 gap-8 md:grid-cols-2 items-center ">

        <div
          class="min-h-[600px] bg-cover bg-center rounded-sm shadow-lg transition-all duration-700 ease-in-out delay-200"
          :style="{ backgroundImage: 'url(frontend/images/about.jpg)' }">
        </div>

        <div class="wrap-about transition-all duration-700 ease-in-out delay-200">
          <div class="heading-section space-y-8 px-8 md:px-12  py-20 bg-white rounded-sm shadow-custom lg:ml-[-130px]">
            <h2 class="text-3xl md:text-5xl text-gray-800 ">
              We Put People First.
            </h2>

            <p class="text-gray-500 text-xl">
              A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a
              paradisematic country, in which roasted parts of sentences fly into your mouth.
            </p>

            <p class="text-gray-500 text-xl">
              On her way she met a copy. The copy warned the Little Blind Text, that where it came from it would have
              been rewritten a thousand times and everything that was left from its origin would be the word "and" and
              the Little Blind Text should turn around and return to its own, safe country. But nothing the copy said
              could convince her and so it didn’t take long until a few insidious Copy Writers ambushed her, made her
              drunk with Longe and Parole and dragged her into their agency, where they abused her for their.
            </p>
          </div>
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
@media (min-width: 992px) {
  .heading-section {
    margin-left: -130px;
    background: #fff;
    box-shadow: -20px 5px 14px -15px rgba(0, 0, 0, 0.13);
  }
}
</style>
