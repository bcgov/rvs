<template>
    <div class="card mb-3">
        <div class="card-header text-center">{{ title }}</div>
        <div class="card-body">
            <div v-if="results != null" class="table-responsive pb-3">
                <table :id="tableId" class="table table-striped">
                    <thead>
                    <tr class="d-none"><th colspan="14">{{tableHeader}}</th></tr>
                    <tr>
                        <th></th>
                        <template v-for="k in Object.keys(results['Age and Eligibility'])">
                            <th scope="col" v-if="k !== 'TOTAL'">{{ k }}</th>
                        </template>
                        <th>TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, i) in Object.entries(results)">
                        <th scope="row">{{row[0]}}</th>
                        <template v-for="k in Object.keys(results['Age and Eligibility'])">
                            <td v-if="k !== 'TOTAL'">{{ row[1][k] }}</td>
                        </template>
                        <td>{{ row[1]['TOTAL']}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <h1 v-else class="lead">No results</h1>
        </div>
    </div>
</template>
<script>


import BreezeAuthenticatedLayout from '../Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/vue3';
import StudentSearchBox from '../Components/StudentSearch.vue';
import BreezeLabel from '../Components/Label.vue';
import BreezeInput from '../Components/Input.vue';
import BreezeSelect from '../Components/Select.vue';
import NavLink from "../Components/NavLink";

export default {
    name: 'ReportGrid',
    components: {
        NavLink,
        BreezeAuthenticatedLayout, StudentSearchBox, Head, Link, BreezeInput, BreezeSelect, BreezeLabel
    },
    props: {
        results: Object,
        title: String,
        tableId: String,
        tableHeader: String,
    },
    data() {
        return {
        }
    },
}
</script>
