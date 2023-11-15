<template>
    <div class="card">
        <div class="card-header">
            <div>Staff Maintenance</div>
        </div>

        <div class="card-body">
            <div v-if="results != null" class="table-responsive pb-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">User ID</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">Type of Access</th>
                            <th scope="col">Status</th>
                            <th scope="col">Disabled Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, i) in results">
                            <td>
                                <Link :href="'/vss/maintenance/staff/' + row.id">{{ row.user_id }}</Link>
                            </td>
                            <td>{{ row.first_name }}</td>
                            <td>{{ row.last_name}}</td>
                            <td>{{ row.email }}</td>
                            <td>{{ row.start_date }}</td>
                            <td>
                                <span v-if="row.access_type === 'A'">Admin</span>
                                <span v-if="row.access_type === 'U'">User</span>
                                <span v-if="row.access_type === 'S'">Tech Support</span>
                            </td>
                            <td>
                                <span v-if="row.disabled" class="badge rounded-pill text-bg-danger">Disabled</span>
                                <span v-else class="badge rounded-pill text-bg-success">Active</span>
                            </td>
                            <td>{{ row.end_date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h1 v-else class="lead">No results</h1>
        </div>

    </div>

</template>
<script>
import { Link } from '@inertiajs/vue3';
import BreezeInput from '@/Components/Input.vue';

export default {
    name: 'MaintenanceStaff',
    components: {
        BreezeInput, Link
    },
    props: {
        results: Object,
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
    }
}

</script>
