<template>
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-10 mx-20 border-y md:border-none">
                <ContactInfoItem icon="fas fa-map-marker-alt" label="Address"
                    value="198 West 21th Street, Suite 721 New York NY 10016" />
                <ContactInfoItem icon="fas fa-phone-alt" label="Phone" value="+1235 2355 98" link="tel:+1235235598"
                    borderClass="md:border-x" />
                <ContactInfoItem icon="fas fa-envelope" label="Email" value="info@example.com"
                    link="mailto:info@example.com" />
            </div>

            <!-- Contact Form -->
            <div class="flex justify-center mb-10">
                <div class="w-full md:w-3/4 lg:w-1/2">
                    <ContactForm />
                </div>
            </div>

            <!-- Map -->
            <div class="w-full h-96 bg-gray-100 rounded shadow overflow-hidden">
                <iframe class="w-full h-full"
                    src="https://maps.google.com/maps?q=new%20york&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0"
                    allowfullscreen></iframe>
            </div>
        </div>
    </section>
</template>

<script setup>
import ContactInfoItem from '@/components/Public/Contact/ContactInfoItem.vue'
import ContactForm from '@/components/Public/Contact/ContactForm.vue'
</script>