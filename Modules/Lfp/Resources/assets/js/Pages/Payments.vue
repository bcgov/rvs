<template>

    <AuthenticatedLayout v-bind="$attrs">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 mt-3">
                    <div class="card">
                        <div class="card-header">
                            LFP Application Search
                        </div>
                        <div class="card-body">
                            <ApplicationSearchBox page="/lfp/applications/" />
                        </div>
                    </div>
                </div>
                <div class="col-md-9 mt-3">
                    <div class="card mb-3">
                        <div class="card-header">
                            Payments

                            <div class="d-inline-flex dropdown float-end">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    filter by payment status
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" @click="filterStatus('all')">All</a></li>
                                    <li><a class="dropdown-item" href="#" @click="filterStatus('CANC')">Cancelled</a></li>
                                    <li><a class="dropdown-item" href="#" @click="filterStatus('DNYD')">Denied</a></li>
                                    <li><a class="dropdown-item" href="#" @click="filterStatus('EMPY')">Employment Letter Sent</a></li>
                                    <li><a class="dropdown-item" href="#" @click="filterStatus('PAID')">Paid</a></li>
                                    <li><a class="dropdown-item" href="#" @click="filterStatus('PROP')">Proposed</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div v-if="results != null && results.data.length > 0" class="table-responsive pb-3">
                                <table class="table table-striped">
                                    <thead>
                                    <PaymentsHeader></PaymentsHeader>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, i) in results.data">
                                        <td><Link :href="'/lfp/applications/show/' + row.lfp.id"><span v-if="row.sfas_ind != null">{{ studentLastName(row.sfas_ind.last_name) }}</span></Link></td>
                                        <td><span v-if="row.sfas_ind != null">{{ row.sfas_ind.first_name }}</span></td>
                                        <td>{{ row.profession }}</td>
                                        <td>{{ cleanDate(row.sfas_payment.pl_anniversary_dte) }}</td>
                                        <td>{{ row.sfas_payment.pl_payment_status_code }}</td>
                                        <td>{{ row.sfas_payment.pl_payment_status_dte }}</td>
                                        <td>${{ row.sfas_payment.pl_dire_principal_pay_amt }}</td>
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
import { Link, useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '../Layouts/Authenticated.vue';
import BreezePagination from "@/Components/Pagination";
import ApplicationSearchBox from "../Components/ApplicationSearch";
import PaymentsHeader from "../Components/PaymentsHeader";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: 'Payments',
    components: {
        Link, AuthenticatedLayout, Head, PaymentsHeader, BreezePagination, ApplicationSearchBox
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
        filterStatus: function (type)
        {

            let data = {
                'filter_status': type
            };

            Inertia.get('/lfp/payments', data, {
                preserveState: true
            });
        },
        studentLastName: function (name)
        {
            return name == null ? "BLANK" : name;
        },

    },
    mounted() {

    }
}

</script>
