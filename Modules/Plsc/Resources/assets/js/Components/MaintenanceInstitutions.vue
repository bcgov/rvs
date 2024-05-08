<template>
    <div class="card">
        <div class="card-header">
            <div>Institutions
                <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newInstitutionModal">New Institution</button>
            </div>
        </div>

        <div class="card-body">

            <div v-if="results != null" class="table-responsive pb-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Active</th>
                            <th scope="col">Contact Name</th>
                            <th scope="col">Contact Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, i) in results">
                            <td>
                                <button @click="editInstitution(i)" type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editInstitutionModal">{{ row.name }}</button>
                            </td>
                            <td>
                                <span v-if="row.active_flag" class="badge rounded-pill text-bg-success">True</span>
                                <span v-else class="badge rounded-pill text-bg-danger">False</span>
                            </td>
                            <td>{{ row.contact_name }}</td>
                            <td>{{ row.contact_email }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h1 v-else class="lead">No results</h1>
        </div>


        <div class="modal modal-lg fade" id="newInstitutionModal" tabindex="-1" aria-labelledby="newInstitutionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newInstitutionModalLabel">Add New Institution</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="newInstitutionForm != null" @submit.prevent="newInstitution">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-9">
                                        <BreezeLabel for="newInstitutionName" class="form-label" value="Name *" />
                                        <BreezeInput type="text" class="form-control" id="newInstitutionName" v-model="newInstitutionForm.name" />
                                    </div>

                                    <div class="col-md-3">
                                        <BreezeLabel for="newApplicationActiveFlag" class="form-label" value="Active *" />
                                        <BreezeSelect class="form-select" id="newApplicationActiveFlag" v-model="newInstitutionForm.active_flag">
                                            <option value="false">False</option>
                                            <option value="true">True</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-md-6">
                                        <BreezeLabel for="newInstitutionContactName" class="form-label" value="Contact Name" />
                                        <BreezeInput type="text" class="form-control" id="newInstitutionContactName" v-model="newInstitutionForm.contact_name" />
                                    </div>
                                    <div class="col-md-6">
                                        <BreezeLabel for="newInstitutionContactEmail" class="form-label" value="Contact Email" />
                                        <BreezeInput type="email" class="form-control" id="newInstitutionContactEmail" v-model="newInstitutionForm.contact_email" />
                                    </div>

                                </div>

                                <div v-if="newInstitutionForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="newInstitutionForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in newInstitutionForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="newInstitutionForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="newInstitutionForm.formState" :success-msg="newInstitutionForm.formSuccessMsg" :fail-msg="newInstitutionForm.formFailMsg"></FormSubmitAlert>
                    </form>
                </div>
            </div>
        </div><!-- end new ineligible reason -->

        <div class="modal modal-lg fade" id="editInstitutionModal" tabindex="-1" aria-labelledby="editInstitutionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editInstitutionModalLabel">Edit Institution</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="editInstitutionForm != null" @submit.prevent="submitEditInstitution">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-9">
                                        <BreezeLabel for="editInstitutionName" class="form-label" value="Name *" />
                                        <BreezeInput type="text" class="form-control" id="editInstitutionName" v-model="editInstitutionForm.name" />
                                    </div>

                                    <div class="col-md-3">
                                        <BreezeLabel for="editApplicationActiveFlag" class="form-label" value="Active *" />
                                        <BreezeSelect class="form-select" id="editApplicationActiveFlag" v-model="editInstitutionForm.active_flag">
                                            <option value="false">False</option>
                                            <option value="true">True</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-md-6">
                                        <BreezeLabel for="editInstitutionContactName" class="form-label" value="Contact Name" />
                                        <BreezeInput type="text" class="form-control" id="editInstitutionContactName" v-model="editInstitutionForm.contact_name" />
                                    </div>
                                    <div class="col-md-6">
                                        <BreezeLabel for="editInstitutionContactEmail" class="form-label" value="Contact Email" />
                                        <BreezeInput type="email" class="form-control" id="editInstitutionContactEmail" v-model="editInstitutionForm.contact_email" />
                                    </div>

                                </div>

                                <div v-if="editInstitutionForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="editInstitutionForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in editInstitutionForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="editInstitutionForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="editInstitutionForm.formState"></FormSubmitAlert>
                    </form>
                </div>
            </div>
        </div><!-- end edit ineligible reason -->

    </div>

</template>
<script>
import {Link, useForm} from '@inertiajs/vue3';
import BreezeInput from '@/Components/Input.vue';
import FormSubmitAlert from "@/Components/FormSubmitAlert";
import BreezeSelect from "@/Components/Select";
import BreezeLabel from "@/Components/Label";

export default {
    name: 'MaintenanceInstitution',
    components: {
        BreezeInput, Link, FormSubmitAlert, BreezeSelect, BreezeLabel
    },
    props: {
        results: Object,
    },
    data() {
        return {
            newInstitutionForm: null,
            newInstitutionFormData: {
                formState: true,
                name: '', contact_name: '', active_flag: false, contact_email: '',
            },
            editInstitutionForm: null,

        }
    },
    methods: {
        editInstitution: function (index) {
            this.editInstitutionForm = useForm(this.results[index]);
        },

        submitEditInstitution: function () {
            this.editInstitutionForm.formState = '';
            this.editInstitutionForm.put('/plsc/maintenance/institutions/' + this.editInstitutionForm.id, {
                onSuccess: (response) => {
                    $("#editInstitutionModal").modal('hide');
                    this.editInstitutionForm.formState = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.editInstitutionForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },

        newInstitution: function ()
        {
            this.newInstitutionForm.formState = '';
            this.newInstitutionForm.post('/plsc/maintenance/institutions', {
                onSuccess: (response) => {
                    $("#newInstitutionModal").modal('hide');
                    this.newInstitutionForm.reset(this.newInstitutionFormData);
                },
                onFailure: () => {
                },
                onError: () => {
                    this.newInstitutionForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },
    },
    mounted() {
        this.newInstitutionForm = useForm(this.newInstitutionFormData);
    }
}

</script>
