<template>
    <div class="card">
        <div class="card-header">
            <div>Payment Types Maintenance
                <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newPaymentTypeModal">New Payment Type</button>
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
                                <button @click="editPaymentType(i)" type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editPaymentTypeModal">{{ row.title }}</button>
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


        <div class="modal modal-lg fade" id="newPaymentTypeModal" tabindex="-1" aria-labelledby="newPaymentTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newPaymentTypeModalLabel">New PaymentType Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="newPaymentTypeForm != null" @submit.prevent="newPaymentType">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-9">
                                        <BreezeLabel for="newPaymentTypeTitle" class="form-label" value="Title *" />
                                        <BreezeInput type="text" class="form-control" id="newPaymentTypeTitle" v-model="newPaymentTypeForm.title" />
                                    </div>

                                    <div class="col-md-3">
                                        <BreezeLabel for="newPaymentTypeActiveFlag" class="form-label" value="Active *" />
                                        <BreezeSelect class="form-select" id="newPaymentTypeActiveFlag" v-model="newPaymentTypeForm.active_flag">
                                            <option value="false">False</option>
                                            <option value="true">True</option>
                                        </BreezeSelect>
                                    </div>

                                </div>

                                <div v-if="newPaymentTypeForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="newPaymentTypeForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in newPaymentTypeForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="newPaymentTypeForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="newPaymentTypeForm.formState"></FormSubmitAlert>
                    </form>
                </div>
            </div>
        </div><!-- end new ineligible reason -->

        <div class="modal modal-lg fade" id="editPaymentTypeModal" tabindex="-1" aria-labelledby="editPaymentTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPaymentTypeModalLabel">Edit PaymentType Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="editPaymentTypeForm != null" @submit.prevent="submitEditPaymentType">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-9">
                                        <BreezeLabel for="editPaymentTypeTitle" class="form-label" value="Title *" />
                                        <BreezeInput type="text" class="form-control" id="editPaymentTypeTitle" v-model="editPaymentTypeForm.title" />
                                    </div>
                                    <div class="col-md-3">
                                        <BreezeLabel for="editPaymentTypeActiveFlag" class="form-label" value="Active *" />
                                        <BreezeSelect class="form-select" id="editPaymentTypeActiveFlag" v-model="editPaymentTypeForm.active_flag">
                                            <option value="false">False</option>
                                            <option value="true">True</option>
                                        </BreezeSelect>
                                    </div>

                                </div>

                                <div v-if="editPaymentTypeForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="editPaymentTypeForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in editPaymentTypeForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="editPaymentTypeForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="editPaymentTypeForm.formState"></FormSubmitAlert>
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
    name: 'MaintenancePaymentTypes',
    components: {
        BreezeInput, Link, FormSubmitAlert, BreezeSelect, BreezeLabel
    },
    props: {
        results: Object,
    },
    data() {
        return {
            newPaymentTypeForm: null,
            newPaymentTypeFormData: {
                formState: true,
                title: '', active_flag: false,
            },
            editPaymentTypeForm: null,

        }
    },
    methods: {
        editPaymentType: function (index) {
            this.editPaymentTypeForm = useForm(this.results[index]);
        },

        submitEditPaymentType: function () {
            this.editPaymentTypeForm.formState = '';
            this.editPaymentTypeForm.put('/twp/maintenance/payments/' + this.editPaymentTypeForm.id, {
                onSuccess: (response) => {
                    $("#editPaymentTypeModal").modal('hide');
                    this.editPaymentTypeForm.formState = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.editPaymentTypeForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },

        newPaymentType: function ()
        {
            this.newPaymentTypeForm.formState = '';
            this.newPaymentTypeForm.post('/twp/maintenance/payments', {
                onSuccess: (response) => {
                    $("#newPaymentTypeModal").modal('hide');
                    this.newPaymentTypeForm.reset(this.newPaymentTypeFormData);
                },
                onFailure: () => {
                },
                onError: () => {
                    this.newPaymentTypeForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },
    },
    mounted() {
        this.newPaymentTypeForm = useForm(this.newPaymentTypeFormData);
    }
}

</script>
