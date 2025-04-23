<template>

    <Head title="Dashboard" />
    <div>
        <AdminDashboard v-if="user.role === 'admin'" />
        <AgentDashboard v-else />
    </div>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3'

import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import AdminDashboard from '@/Components/Dashboard/AdminDashboard.vue';
import AgentDashboard from '@/Components/Dashboard/AgentDashboard.vue';
defineOptions({
    layout: (h, page) => h(DashboardLayout, {
        header: {
            title: 'Dashboard',
            mainPage: 'Pages',
            page: '',
        }
    }, () => page)
})

const { props } = usePage()
const user = props.auth.user
</script>

<script>
export default {
    name: "Home",
    components: {
        AdminDashboard,
        AgentDashboard
    }
}
</script>
<style lang="">

</style>