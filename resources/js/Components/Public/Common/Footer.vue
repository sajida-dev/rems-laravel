<template>
    <footer class="bg-gray-100 py-16 text-gray-700">
        <div class="container mx-auto px-10">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-10 md:text-left text-center">
                <!-- Uptown / About -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Uptown</h2>
                    <p class="text-sm mb-5">Far far away, behind the word mountains, far from the countries.</p>
                    <div class="flex space-x-3">
                        <Link href="#" aria-label="Twitter"
                            class="w-10 h-10 bg-white shadow rounded-full flex items-center justify-center text-pink-500 hover:bg-pink-500 hover:text-white transition duration-300">
                        <i class="fab fa-twitter"></i>
                        </Link>
                        <Link href="#" aria-label="Facebook"
                            class="w-10 h-10 bg-white shadow rounded-full flex items-center justify-center text-pink-500 hover:bg-pink-500 hover:text-white transition duration-300">
                        <i class="fab fa-facebook-f"></i>
                        </Link>
                        <Link href="#" aria-label="Instagram"
                            class="w-10 h-10 bg-white shadow rounded-full flex items-center justify-center text-pink-500 hover:bg-pink-500 hover:text-white transition duration-300">
                        <i class="fab fa-instagram"></i>
                        </Link>
                    </div>
                </div>

                <!-- Community Links -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Community</h2>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <Link href="/all-properties" class="hover:text-pink-500 transition">Search Properties</Link>
                        </li>
                        <li>
                            <Link href="/all-agents" class="hover:text-pink-500 transition">For Agents</Link>
                        </li>
                        <li>
                            <Link href="#" class="hover:text-pink-500 transition">Reviews</Link>
                        </li>
                        <li>
                            <Link href="#" class="hover:text-pink-500 transition">FAQs</Link>
                        </li>
                    </ul>
                </div>

                <!-- About Us -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">About Us</h2>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <Link href="#" class="hover:text-pink-500 transition">Our Story</Link>
                        </li>
                        <li>
                            <Link href="#" class="hover:text-pink-500 transition">Meet the team</Link>
                        </li>
                        <li>
                            <Link href="#" class="hover:text-pink-500 transition">Careers</Link>
                        </li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Company</h2>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <Link href="/about" class="hover:text-pink-500 transition">About Us</Link>
                        </li>
                        <li>
                            <Link href="#" class="hover:text-pink-500 transition">Press</Link>
                        </li>
                        <li>
                            <Link href="/contact" class="hover:text-pink-500 transition">Contact</Link>
                        </li>
                        <li>
                            <Link href="#" class="hover:text-pink-500 transition">Careers</Link>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-span-2 md:col-span-1 ">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Have a Questions?</h2>
                    <ul class="space-y-3 text-sm ">
                        <li class="flex items-start ">
                            <i class="fas fa-map-marker-alt text-pink-500 mr-2 mt-1"></i>
                            <span>203 Fake St. Mountain View, San Francisco, California, USA</span>
                        </li>
                        <li class="flex md:items-start justify-center items-center">
                            <i class="fas fa-phone-alt text-pink-500 mr-2 mt-1"></i>
                            <Link href="tel:+23923929210" class="hover:text-pink-500 transition">+2 392 3929 210</Link>
                        </li>
                        <li class="flex md:items-start justify-center items-center">
                            <i class="fas fa-envelope text-pink-500 mr-2 mt-1"></i>
                            <Link href="mailto:info@uptown.com" class="hover:text-pink-500 transition">info@uptown.com
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="text-center text-sm text-gray-500">
                &copy; {{ currentYear }} All rights reserved
            </div>
        </div>
    </footer>
</template>
<script>
export default {
    name: "Footer",
    data() {
        return {
            currentYear: new Date().getFullYear(),
        };
    },
};
</script>
<script setup>
import { Link } from '@inertiajs/vue3';
</script>