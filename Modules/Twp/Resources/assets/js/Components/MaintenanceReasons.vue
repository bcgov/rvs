<template>
    <div class="card">
        <div class="card-header">
            <div>Denial Reasons Maintenance
                <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newApplicationModal">New Denial Reason</button>
            </div>
        </div>

        <div class="card-body">
            <div v-if="results != null" class="table-responsive pb-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Active</th>
                            <th scope="col" class="col-8">Letter Text</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, i) in results">
                            <td>
                                <button @click="editApplication(i)" type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editApplicationModal">{{ row.title }}</button>
                            </td>
                            <td>
                                <span v-if="row.active_flag" class="badge rounded-pill text-bg-success">True</span>
                                <span v-else class="badge rounded-pill text-bg-danger">False</span>
                            </td>
                            <td>{{ row.letter_body }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h1 v-else class="lead">No results</h1>
        </div>


        <div class="modal modal-lg fade" id="newApplicationModal" tabindex="-1" aria-labelledby="newApplicationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newApplicationModalLabel">Add New Denial Reason</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="newApplicationForm != null" @submit.prevent="newApplication">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-9">
                                        <BreezeLabel for="newApplicationTitle" class="form-label" value="Title *" />
                                        <BreezeInput type="text" class="form-control" id="newApplicationTitle" v-model="newApplicationForm.title" />
                                    </div>

                                    <div class="col-md-3">
                                        <BreezeLabel for="newApplicationActiveFlag" class="form-label" value="Active *" />
                                        <BreezeSelect class="form-select" id="newApplicationActiveFlag" v-model="newApplicationForm.active_flag">
                                            <option value="false">False</option>
                                            <option value="true">True</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-12">
                                        <BreezeLabel for="newApplicationParagraphText" class="form-label" value="Letter Text" />
                                        <textarea class="form-control" id="newApplicationParagraphText" v-model="newApplicationForm.letter_body" />
                                    </div>

                                </div>

                                <div v-if="newApplicationForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="newApplicationForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in newApplicationForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="newApplicationForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="newApplicationForm.formState"></FormSubmitAlert>
                    </form>
                </div>
            </div>
        </div><!-- end new ineligible reason -->

        <div class="modal modal-lg fade" id="editApplicationModal" tabindex="-1" aria-labelledby="editApplicationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editApplicationModalLabel">Edit Denial Reason</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="editApplicationForm != null" @submit.prevent="submitEditApplication">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-9">
                                        <BreezeLabel for="editApplicationTitle" class="form-label" value="Title *" />
                                        <BreezeInput type="text" class="form-control" id="editApplicationTitle" v-model="editApplicationForm.title" />
                                    </div>
                                    <div class="col-md-3">
                                        <BreezeLabel for="editApplicationActiveFlag" class="form-label" value="Active *" />
                                        <BreezeSelect class="form-select" id="editApplicationActiveFlag" v-model="editApplicationForm.active_flag">
                                            <option value="false">False</option>
                                            <option value="true">True</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-12">
                                        <BreezeLabel for="editApplicationLetterBody" class="form-label" value="Letter Text" />
                                        <textarea class="form-control" id="editApplicationLetterBody" v-model="editApplicationForm.letter_body" />
                                    </div>

                                </div>

                                <div v-if="editApplicationForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="editApplicationForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in editApplicationForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="editApplicationForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="editApplicationForm.formState"></FormSubmitAlert>
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
    name: 'MaintenanceApplicationReason',
    components: {
        BreezeInput, Link, FormSubmitAlert, BreezeSelect, BreezeLabel
    },
    props: {
        results: Object,
    },
    data() {
        return {
            newApplicationForm: null,
            newApplicationFormData: {
                formState: true,
                title: '', reason_status: 'Denied', active_flag: false, letter_body: '',
            },
            editApplicationForm: null,

        }
    },
    methods: {
        editApplication: function (index) {
            this.editApplicationForm = useForm(this.results[index]);
        },

        submitEditApplication: function () {
            this.editApplicationForm.formState = '';
            this.editApplicationForm.put('/twp/maintenance/reasons/' + this.editApplicationForm.id, {
                onSuccess: (response) => {
                    $("#editApplicationModal").modal('hide');
                    this.editApplicationForm.formState = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.editApplicationForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },

        newApplication: function ()
        {
            this.newApplicationForm.formState = '';
            this.newApplicationForm.post('/twp/maintenance/reasons', {
                onSuccess: (response) => {
                    $("#newApplicationModal").modal('hide');
                    this.newApplicationForm.reset(this.newApplicationFormData);
                },
                onFailure: () => {
                },
                onError: () => {
                    this.newApplicationForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },
    },
    mounted() {
        this.newApplicationForm = useForm(this.newApplicationFormData);
    }
}

</script>
