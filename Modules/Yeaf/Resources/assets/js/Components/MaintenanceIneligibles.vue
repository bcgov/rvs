<template>
    <div class="card">
        <div class="card-header">
            <div>Ineligible Application Reasons Maintenance
                <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newIneligibleModal">New Ineligible Reason</button>
            </div>
        </div>

        <div class="card-body">
            <div v-if="results != null" class="table-responsive pb-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Code ID</th>
                            <th scope="col">Description</th>
                            <th scope="col">Type</th>
                            <th scope="col">Active</th>
                            <th scope="col" class="col-6">Letter Text</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, i) in results">
                            <td>
                                <button @click="editIneligibe(i)" type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editIneligibleModal">{{ row.code_id }}</button>
                            </td>
                            <td>{{ row.description }}</td>
                            <td>
                                <span v-if="row.code_type === 'D'" class="badge rounded-pill text-bg-warning">Denied</span>
                                <span v-if="row.code_type === 'P'" class="badge rounded-pill text-bg-primary">Pending</span>
                            </td>
                            <td>
                                <span v-if="row.active_flag" class="badge rounded-pill text-bg-success">True</span>
                                <span v-else class="badge rounded-pill text-bg-danger">False</span>
                            </td>
                            <td>{{ row.paragraph_text }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h1 v-else class="lead">No results</h1>
        </div>


        <div class="modal modal-lg fade" id="newIneligibleModal" tabindex="-1" aria-labelledby="newIneligibleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newIneligibleModalLabel">Add New Ineligible Application Reason</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="newIneligibleForm != null" @submit.prevent="newIneligible">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-2">
                                        <BreezeLabel for="newCodeId" class="form-label" value="Code ID *" />
                                        <BreezeInput type="text" class="form-control" maxlength="2" id="newCodeId" v-model="newIneligibleForm.code_id" />
                                    </div>
                                    <div class="col-md-6">
                                        <BreezeLabel for="newDescription" class="form-label" value="Description *" />
                                        <BreezeInput type="text" class="form-control" id="newDescription" v-model="newIneligibleForm.description" />
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="newCodeType" class="form-label" value="Type *" />
                                        <BreezeSelect class="form-select" id="newCodeType" v-model="newIneligibleForm.code_type">
                                            <option value="D">Denied</option>
                                            <option value="P">Pending</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="newActiveFlag" class="form-label" value="Active *" />
                                        <BreezeSelect class="form-select" id="newActiveFlag" v-model="newIneligibleForm.active_flag">
                                            <option value="false">False</option>
                                            <option value="true">True</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-12">
                                        <BreezeLabel for="newParagraphText" class="form-label" value="Letter Text" />
                                        <textarea class="form-control" id="newParagraphText" v-model="newIneligibleForm.paragraph_text" />
                                    </div>

                                </div>

                                <div v-if="newIneligibleForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="newIneligibleForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in newIneligibleForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="newIneligibleForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="newIneligibleForm.formState" :success-msg="newIneligibleForm.formSuccessMsg" :fail-msg="newIneligibleForm.formFailMsg"></FormSubmitAlert>
                    </form>
                </div>
            </div>
        </div><!-- end new ineligible reason -->

        <div class="modal modal-lg fade" id="editIneligibleModal" tabindex="-1" aria-labelledby="editIneligibleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editIneligibleModalLabel">Edit Ineligible Application Reason</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="editIneligibleForm != null" @submit.prevent="submitEditIneligible">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-2">
                                        <BreezeLabel for="editCodeId" class="form-label" value="Code ID *" />
                                        <BreezeInput type="text" class="form-control" maxlength="2" id="editCodeId" v-model="editIneligibleForm.code_id" />
                                    </div>
                                    <div class="col-md-6">
                                        <BreezeLabel for="editDescription" class="form-label" value="Description *" />
                                        <BreezeInput type="text" class="form-control" id="editDescription" v-model="editIneligibleForm.description" />
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="editCodeType" class="form-label" value="Type *" />
                                        <BreezeSelect class="form-select" id="editCodeType" v-model="editIneligibleForm.code_type">
                                            <option value="D">Denied</option>
                                            <option value="P">Pending</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="editActiveFlag" class="form-label" value="Active *" />
                                        <BreezeSelect class="form-select" id="editActiveFlag" v-model="editIneligibleForm.active_flag">
                                            <option value="false">False</option>
                                            <option value="true">True</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-12">
                                        <BreezeLabel for="editParagraphText" class="form-label" value="Letter Text" />
                                        <textarea class="form-control" id="editParagraphText" v-model="editIneligibleForm.paragraph_text" />
                                    </div>

                                </div>

                                <div v-if="editIneligibleForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="editIneligibleForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in editIneligibleForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="editIneligibleForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="editIneligibleForm.formState"></FormSubmitAlert>
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
    name: 'MaintenanceIneligibles',
    components: {
        BreezeInput, Link, FormSubmitAlert, BreezeSelect, BreezeLabel
    },
    props: {
        results: Object,
    },
    data() {
        return {
            newIneligibleForm: null,
            newIneligibleFormData: {
                formState: true,
                code_id: '', description: '', code_type: 'D', active_flag: false, paragraph_text: '',
            },
            editIneligibleForm: null,

        }
    },
    methods: {
        editIneligibe: function (index) {
            this.editIneligibleForm = useForm(this.results[index]);
        },

        submitEditIneligible: function () {
            this.editIneligibleForm.formState = '';
            this.editIneligibleForm.post('/yeaf/maintenance/ineligible/' + this.editIneligibleForm.id, {
                onSuccess: (response) => {
                    $("#editIneligibleModal").modal('hide');
                    this.editIneligibleForm.formState = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.editIneligibleForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },

        newIneligible: function ()
        {
            this.newIneligibleForm.formState = '';
            this.newIneligibleForm.post('/yeaf/maintenance/ineligible', {
                onSuccess: (response) => {
                    $("#newIneligibleModal").modal('hide');
                    this.newIneligibleForm.reset(this.newIneligibleFormData);
                },
                onFailure: () => {
                },
                onError: () => {
                    this.newIneligibleForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },
    },
    mounted() {
        this.newIneligibleForm = useForm(this.newIneligibleFormData);
    }
}

</script>
