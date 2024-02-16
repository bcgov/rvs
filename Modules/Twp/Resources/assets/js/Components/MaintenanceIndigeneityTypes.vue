<template>
    <div class="card">
        <div class="card-header">
            <div>Indigeneity Type Maintenance
                <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newIndigeneityTypeModal">New Indigeneity Type</button>
            </div>
        </div>

        <div class="card-body">
            <div v-if="results != null" class="table-responsive pb-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, i) in results">
                            <td>
                                <button @click="editIndigeneityType(i)" type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editIndigeneityTypeModal">{{ row.title }}</button>
                            </td>
                            <td>
                                <span v-if="row.active_flag" class="badge rounded-pill text-bg-success">True</span>
                                <span v-else class="badge rounded-pill text-bg-danger">False</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h1 v-else class="lead">No results</h1>
        </div>


        <div class="modal modal-lg fade" id="newIndigeneityTypeModal" tabindex="-1" aria-labelledby="newIndigeneityTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newIndigeneityTypeModalLabel">New Indigeneity Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="newIndigeneityTypeForm != null" @submit.prevent="newIndigeneityType">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-9">
                                        <BreezeLabel for="newIndigeneityTypeTitle" class="form-label" value="Title *" />
                                        <BreezeInput type="text" class="form-control" id="newIndigeneityTypeTitle" v-model="newIndigeneityTypeForm.title" />
                                    </div>

                                    <div class="col-md-3">
                                        <BreezeLabel for="newIndigeneityTypeActiveFlag" class="form-label" value="Active *" />
                                        <BreezeSelect class="form-select" id="newIndigeneityTypeActiveFlag" v-model="newIndigeneityTypeForm.active_flag">
                                            <option value="false">False</option>
                                            <option value="true">True</option>
                                        </BreezeSelect>
                                    </div>

                                </div>

                                <div v-if="newIndigeneityTypeForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="newIndigeneityTypeForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in newIndigeneityTypeForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="newIndigeneityTypeForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="newIndigeneityTypeForm.formState"></FormSubmitAlert>
                    </form>
                </div>
            </div>
        </div><!-- end new ineligible reason -->

        <div class="modal modal-lg fade" id="editIndigeneityTypeModal" tabindex="-1" aria-labelledby="editIndigeneityTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editIndigeneityTypeModalLabel">Edit Indigeneity Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="editIndigeneityTypeForm != null" @submit.prevent="submitEditIndigeneityType">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-9">
                                        <BreezeLabel for="editIndigeneityTypeTitle" class="form-label" value="Title *" />
                                        <BreezeInput type="text" class="form-control" id="editIndigeneityTypeTitle" v-model="editIndigeneityTypeForm.title" />
                                    </div>
                                    <div class="col-md-3">
                                        <BreezeLabel for="editIndigeneityTypeActiveFlag" class="form-label" value="Active *" />
                                        <BreezeSelect class="form-select" id="editIndigeneityTypeActiveFlag" v-model="editIndigeneityTypeForm.active_flag">
                                            <option value="false">False</option>
                                            <option value="true">True</option>
                                        </BreezeSelect>
                                    </div>

                                </div>

                                <div v-if="editIndigeneityTypeForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="editIndigeneityTypeForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in editIndigeneityTypeForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="editIndigeneityTypeForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="editIndigeneityTypeForm.formState"></FormSubmitAlert>
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
    name: 'MaintenanceIndigeneityTypes',
    components: {
        BreezeInput, Link, FormSubmitAlert, BreezeSelect, BreezeLabel
    },
    props: {
        results: Object,
    },
    data() {
        return {
            newIndigeneityTypeForm: null,
            newIndigeneityTypeFormData: {
                formState: true,
                title: '', active_flag: false,
            },
            editIndigeneityTypeForm: null,

        }
    },
    methods: {
        editIndigeneityType: function (index) {
            this.editIndigeneityTypeForm = useForm(this.results[index]);
        },

        submitEditIndigeneityType: function () {
            this.editIndigeneityTypeForm.formState = '';
            this.editIndigeneityTypeForm.put('/twp/maintenance/indigeneity/' + this.editIndigeneityTypeForm.id, {
                onSuccess: (response) => {
                    $("#editIndigeneityTypeModal").modal('hide');
                    this.editIndigeneityTypeForm.formState = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.editIndigeneityTypeForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },

        newIndigeneityType: function ()
        {
            this.newIndigeneityTypeForm.formState = '';
            this.newIndigeneityTypeForm.post('/twp/maintenance/indigeneity', {
                onSuccess: (response) => {
                    $("#newIndigeneityTypeModal").modal('hide');
                    this.newIndigeneityTypeForm.reset(this.newIndigeneityTypeFormData);
                },
                onFailure: () => {
                },
                onError: () => {
                    this.newIndigeneityTypeForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },
    },
    mounted() {
        this.newIndigeneityTypeForm = useForm(this.newIndigeneityTypeFormData);
    }
}

</script>
