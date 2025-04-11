<template>
    <nav :class="navbarClasses">
        <div class="container h-14 mx-auto flex items-center justify-between px-5 lg:py-3 py-4">
            <!-- Brand -->
            <a :class="brandClasses" href="/">
                Uptown 
            </a>
            <!-- Mobile Menu Toggle Button -->
            <button class="lg:hidden focus:outline-none" @click="toggleNav" :class="togglerClasses">
                <svg class="h-auto w-6 fill-current" viewBox="0 0 24 24">
                    <path d="M4 5h16M4 12h16M4 19h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center">
                <ul class="flex space-x-2">
                    <li>
                        <a :href="'/'" :class="getNavLinkClasses('/')">Home</a>
                    </li>
                    <li v-if="isAuthenticated">
                        <a :href="profileUrl" :class="getNavLinkClasses(profileUrl)">Profile</a>
                    </li>
                    <li>
                        <a :href="'/about'" :class="getNavLinkClasses('/about')">About</a>
                    </li>
                    <li>
                        <a :href="'/agent'" :class="getNavLinkClasses('/agent')">Agent</a>
                    </li>
                    <li>
                        <a :href="'/services'" :class="getNavLinkClasses('/services')">Services</a>
                    </li>
                    <li>
                        <a :href="'/properties'" :class="getNavLinkClasses('/properties')">Properties</a>
                    </li>
                    <li>
                        <a :href="'/contact'" :class="getNavLinkClasses('/contact')">Contact</a>
                    </li>
                    <template v-if="!isAuthenticated">
                        <li>
                            <a :href="'/login'" :class="getButtonLinkClasses('/login')">Login</a>
                        </li>
                        <li>
                            <a :href="'/register'" :class="getButtonLinkClasses('/register')">Register</a>
                        </li>
                    </template>
                    <template v-else>
                        <li>
                            <span :class="navLinkClasses">{{ userName }}</span>
                        </li>
                        <li>
                            <a :href="'/logout'" :class="getButtonLinkClasses('/logout')">Logout</a>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
        <!-- Mobile Navigation Drawer -->
        <transition name="slide">
            <div v-if="isOpen" class="fixed inset-0  z-50 flex">
                <div class="absolute inset-0 bg-black opacity-50" @click="toggleNav"></div>
                <div class="relative px-4 z-50 h-full w-72 bg-white shadow transform transition-transform duration-300">
                    <div class="p-4 border-b">
                        <a :class="brandClasses" href="/">
                            Uptown <span class="text-pink-400 font-bold">Brand</span>
                        </a>
                    </div>
                    <div class="p-4">
                        <ul class="flex flex-col space-y-1">
                            <li>
                                <a :href="'/'" :class="getNavLinkMobileClasses('/')">Home</a>
                            </li>
                            <li v-if="isAuthenticated">
                                <a :href="profileUrl" :class="getNavLinkMobileClasses(profileUrl)">Profile</a>
                            </li>
                            <li>
                                <a :href="'/about'" :class="getNavLinkMobileClasses('/about')">About</a>
                            </li>
                            <li>
                                <a :href="'/agent'" :class="getNavLinkMobileClasses('/agent')">Agent</a>
                            </li>
                            <li>
                                <a :href="'/services'" :class="getNavLinkMobileClasses('/services')">Services</a>
                            </li>
                            <li>
                                <a :href="'/properties'" :class="getNavLinkMobileClasses('/properties')">Properties</a>
                            </li>
                            <li>
                                <a :href="'/contact'" :class="getNavLinkMobileClasses('/contact')">Contact</a>
                            </li>
                            <template v-if="!isAuthenticated">
                                <li>
                                    <a :href="'/login'" :class="getButtonLinkMobileClasses('/login')">Login</a>
                                </li>
                                <li>
                                    <a :href="'/register'" :class="getButtonLinkMobileClasses('/register')">Register</a>
                                </li>
                            </template>
                            <template v-else>
                                <li>
                                    <span :class="navLinkMobileClasses">{{ userName }}</span>
                                </li>
                                <li>
                                    <a :href="'/logout'" :class="getButtonLinkMobileClasses('/logout')">Logout</a>
                                </li>
                            </template>
                        </ul>
                        <div class="my-4 text-center text-sm text-gray-600">
                            &copy; {{ currentYear }} Uptown. All rights reserved.
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </nav>
</template>

<script>
export default {
    name: "Navbar",
    data() {
        return {
            isOpen: false,
            isAuthenticated: false,
            userName: "Guest",
            role: "",
            scrolled: false
        };
    },
    computed: {
        currentYear() {
            return new Date().getFullYear();
        },
        profileUrl() {
            return this.role === "agent" ? "/profile" : "/profile";
        },
        navbarClasses() {
            const base = "w-full h-18 z-50 transition-all duration-300 ease-out bg-white";
            if (!this.scrolled) {
                return base + "lg:absolute py-3 top-0 left-0 right-0";
            }
            return base + " lg:py-2 fixed top-0 left-0 right-0 shadow-md";
        },
        brandClasses() {
            const base = "font-extrabold text-[20px] transition-colors duration-300";
            if (!this.scrolled) {
                return base + " lg:text-black text-black";
            }
            return base + " text-black";
        },
        navLinkClasses() {
            return "text-[15px] font-normal transition-colors duration-300 pt-[0.9rem] pb-[0.9rem] px-5 text-black hover:text-pink-400";
        },
        navLinkMobileClasses() {
            return "block text-[15px] font-normal transition-colors duration-300 py-3 px-4 text-black hover:text-pink-400";
        }
    },
    methods: {
        toggleNav() {
            this.isOpen = !this.isOpen;
        },
        handleScroll() {
            this.scrolled = window.scrollY > 100;
        },
        isActive(href) {
            return window.location.pathname === href;
        },
        getNavLinkClasses(href) {
            return [
                this.navLinkClasses,
                this.isActive(href) ? "text-pink-400" : ""
            ].join(" ");
        },
        getButtonLinkClasses(href) {
            return [
                this.navLinkClasses,
                "bg-pink-400 hover:bg-pink-500 rounded px-3 py-2 text-white",
                this.isActive(href) ? "bg-pink-500" : ""
            ].join(" ");
        },
        getNavLinkMobileClasses(href) {
            return [
                this.navLinkMobileClasses,
                this.isActive(href) ? "text-pink-400" : ""
            ].join(" ");
        },
        getButtonLinkMobileClasses(href) {
            return [
                this.navLinkMobileClasses,
                "bg-pink-400 hover:bg-pink-500 rounded text-white",
                this.isActive(href) ? "bg-pink-400" : ""
            ].join(" ");
        }
    },
    mounted() {
        window.addEventListener("scroll", this.handleScroll);
        this.handleScroll();
    },
    beforeUnmount() {
        window.removeEventListener("scroll", this.handleScroll);
    }
};
</script>

<style>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(-100%);
}

.slide-enter-to,
.slide-leave-from {
    transform: translateX(0);
}
</style>
