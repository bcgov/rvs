<template>
    <Head title="Admin Home" />

    <AuthenticatedLayout>

        <div class="mt-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <div class="card">
                            <div class="card-header">
                                Admin Menu
                            </div>
                            <div class="card-body">
                                <AdminMenu :page="page" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 mt-3">
                        <AdminMinistry v-if="page === 'ministry'" :results="results"></AdminMinistry>
                        <AdminUsers v-if="page === 'users'" :results="results"></AdminUsers>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>
<script>
import AuthenticatedLayout from '@/Layouts/Authenticated';
import { Head, Link } from '@inertiajs/vue3';
import AdminMenu from "@/Components/Admin/Menu";
import AdminMinistry from "@/Components/Admin/Ministry";
import AdminUsers from "@/Components/Admin/Users";

export default {
    name: 'Admin',
    components: {
        AdminMinistry,
        AdminUsers,
        AdminMenu,
        AuthenticatedLayout, Head, Link
    },
    props: {
        results: Object,
        page: String,
    },
    data() {
        return {
        }
    },
    methods: {
    },
    watch: {
    },
    computed: {
    },
    mounted() {
    }
}
</script>
