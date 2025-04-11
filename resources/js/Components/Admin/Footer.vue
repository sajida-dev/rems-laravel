<template>
    <footer class="footer">
        <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">UpTown</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Help</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Licenses</a>
                    </li>
                </ul>
            </nav>
            <div class="copyright">
                Copyright &copy;
                <span>{{ currentYear }}</span>
                <a href="#">Updown</a>
            </div>
        </div>
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
