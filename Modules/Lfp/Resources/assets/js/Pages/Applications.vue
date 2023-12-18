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
                            Applications
                            <button class="btn btn-success btn-sm float-end" data-bs-toggle="modal"
                                    data-bs-target="#newApplicationModal">Add New</button>
                        </div>
                        <div class="card-body">
                            <div v-if="results != null && results.data.length > 0" class="table-responsive pb-3">
                                <table class="table table-striped">
                                    <thead>
                                    <ApplicationsHeader></ApplicationsHeader>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, i) in results.data">
                                        <td><a :href="'/lfp/applications/show/' + row.id">{{ studentLastName(row.last_name) }}</a></td>
                                        <td>{{ row.first_name }}</td>
                                        <td>{{ row.birth_date }}</td>
                                        <td>{{ row.receive_date }}</td>
                                        <td>{{ row.status }}</td>
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



        <div class="modal modal-lg fade" id="newApplicationModal" tabindex="-1" aria-labelledby="newApplicationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newApplicationModalLabel">Enter Applicant SIN Number</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="newApplication">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-12">
                                        <Input type="number" class="form-control" id="newApplicationSin" v-model="newApplicationForm.sin" style="font-size: 77px; display: block; text-align: center;"/>
                                        <div v-if="errors.newApplicationSin" class="text-xs text-red-500">
                                            {{ errors.newApplicationSin.join(', ') }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="w-100 btn-lg btn mr-2 btn-outline-success"
                                :disabled="newApplicationForm.processing">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <FormSubmitAlert :form-state="newApplicationForm.formState" :success-msg="newApplicationForm.formSuccessMsg"
                         :fail-msg="newApplicationForm.formFailMsg"></FormSubmitAlert>

    </AuthenticatedLayout>
</template>
<script>
import { Link, useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '../Layouts/Authenticated.vue';
import Input from '@/Components/Input.vue';
import Label from '@/Components/Label.vue';
import Select from "@/Components/Select";
import FormSubmitAlert from "@/Components/FormSubmitAlert";
import BreezePagination from "@/Components/Pagination";
import ApplicationSearchBox from "../Components/ApplicationSearch";
import ApplicationsHeader from "../Components/ApplicationsHeader";

export default {
    name: 'Applications',
    components: {
        Link, Input, Select, Label, FormSubmitAlert, AuthenticatedLayout, Head, ApplicationsHeader, BreezePagination, ApplicationSearchBox
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
            newApplicationForm: useForm({
                formState: true,
                sin: '',
                formSuccessMsg: 'Application record was submitted successfully.',
                formFailMsg: 'An error occurred creating this application.'
            }),
        }
    },
    methods: {
        studentLastName: function (name)
        {
            return name == null ? "BLANK" : name;
        },
        newApplication: function () {
            let vm = this;
            this.newApplicationForm.formState = '';
            this.newApplicationForm.post('/lfp/applications', {
                onSuccess: (response) => {
                    $("#newApplicationModal").modal('hide')
                        .on('hidden.bs.modal', function () {
                            vm.newApplicationForm.sin = '';
                            if(response.props.app === -1)
                                vm.newApplicationForm.formFailMsg = "There is no SABC Applications for that SIN.";
                            if(response.props.app > 0)
                                vm.$inertia.visit('/lfp/applications/show/' + response.props.app);
                            vm.newApplicationForm.formState = response.props.status;
                        });
                },
                onError: () => {
                    this.newApplicationForm.formState = false;
                },
                preserveState: true
            });
        },

    },
    mounted() {
    }
}

</script>
