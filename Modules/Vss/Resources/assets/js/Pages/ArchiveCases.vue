<style scoped>
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>
    <Head title="Archive Cases" />

    <BreezeAuthenticatedLayout>
        <div class="mt-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <div class="card">
                            <div class="card-header">
                                VSS Case Search
                            </div>
                            <div class="card-body">
                                <CaseSearchBox :ftype="'archive'" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 mt-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                VSS Archive Cases
                                <Link :href="'/vss/archive/cases'" class="btn btn-sm btn-dark float-end">Archives</Link>
                            </div>
                            <div class="card-body">
                                <div v-if="results != null" class="table-responsive pb-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <CasesHeader></CasesHeader>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row, i) in results.data">
                                                <td><Link :href="'/vss/case-funding/' + row.id" v-html="row.sin" /></td>
                                                <td>{{ row.first_name }} {{ row.last_name}}</td>
                                                <td>{{ row.institution_code }}<br/><small v-if="row.institution != null">{{ row.institution.institution_name }}</small></td>
                                                <td>{{ row.open_date }}</td>
                                                <td>{{ row.reactivate_date }}</td>
                                                <td>{{ row.incident_status }}</td>
                                                <td>{{ row.auditor_user_id }}</td>
                                                <td><a :href="'/vss/reports/download/' + row.id" class="btn btn-sm btn-outline-secondary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-down" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z"/>
                                                        <path fill-rule="evenodd" d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>
                                                    </svg>
                                                </a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <BreezePagination :links="results.links" :active-page="results.current_page" />
                                </div>
                                <h1 v-else class="lead">No results</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>

</template>
<script>
import BreezeAuthenticatedLayout from '../Layouts/Authenticated.vue';
import CaseSearchBox from '../Components/CaseSearch.vue';
import CasesHeader from '../Components/CasesHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import BreezeInput from "@/Components/Input";
import BreezeSelect from "@/Components/Select";
import BreezeLabel from "@/Components/Label";
import BreezePagination from "@/Components/Pagination";

export default {
    name: 'ArchiveCases',
    components: {
        BreezeAuthenticatedLayout, CaseSearchBox, CasesHeader, Head, Link, BreezeInput, BreezeSelect, BreezeLabel, BreezePagination
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
    },
    mounted() {
    }
}
</script>
