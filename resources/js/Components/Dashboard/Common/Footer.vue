<template>
    <footer class="bg-white border-t p-4 text-sm text-gray-500 text-center">
        &copy; {{ currentYear }}, copyright reserved by <a href="/" class="text-pink-500">Uptown</a>.
    </footer>
</template>

<script>
export default {
    name: "Footer",
    data() {
        return {
            // Get the current year dynamically
            currentYear: new Date().getFullYear(),
        };
    },
};
</script>

<style scoped>
.footer {
    background-color: #f8f9fa;
    padding: 20px 0;
}

.nav-item .nav-link {
    color: #333;
}

.nav-item .nav-link:hover {
    color: #0056b3;
}

.copyright {
    font-size: 14px;
    color: #6c757d;
}
</style>
