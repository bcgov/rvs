<template>

    <AuthenticatedLayout v-bind="$attrs">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 mt-3">
                    <div class="card">
                        <div class="card-header">
                            LFP Search
                        </div>
                        <div class="card-body">
                            <ApplicationSearchBox page="/lfp/applications/" />
                        </div>
                    </div>
                </div>
                <div class="col-md-9 mt-3">
                    <div class="card mb-3">
                        <div class="card-header">
                            Intake Applications
                            <div class="d-flex float-end">
                                <a href="/lfp/intakes/create" class="btn btn-success btn-sm">New App</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div v-if="results != null && results.data.length > 0" class="table-responsive pb-3">
                                <table class="table table-striped">
                                    <thead>
                                    <IntakesHeader></IntakesHeader>
                                    </thead>
                                    <tbody>
                                    <tr v-for="row in results.data">
                                        <td><a :href="'/lfp/intakes/' + row.id">{{ studentLastName(row.last_name) }}</a></td>
                                        <td>{{ row.first_name }}</td>
                                        <td>{{ row.in_good_standing }}</td>
                                        <td>{{ row.intake_status }}</td>
                                        <td>{{ cleanDate(row.receive_date) }}</td>
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

    </AuthenticatedLayout>
</template>
<script>
import { Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '../Layouts/Authenticated.vue';
import BreezePagination from "@/Components/Pagination";
import ApplicationSearchBox from "../Components/ApplicationSearch";
import IntakesHeader from "../Components/IntakesHeader";

export default {
    name: 'Intakes',
    components: {
        Link, AuthenticatedLayout, Head, IntakesHeader, BreezePagination, ApplicationSearchBox
    },
    props: {
        results: Object,
        errors: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        return {
            applications: [],
        }
    },
    methods: {
        cleanDate: function(d)
        {
            if(d == null) return d;
            let date = d.split(" ");
            return date[0]
        },

        studentLastName: function (name)
        {
            return name == null ? "BLANK" : name;
        },


    },
}

</script>
