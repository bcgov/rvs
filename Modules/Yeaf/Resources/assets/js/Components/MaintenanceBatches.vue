<template>
    <div class="card">
        <div class="card-header">
            <div>Batches Maintenance
                <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newBatchModal">New Batch</button>
            </div>
        </div>

        <div class="card-body">
            <div v-if="results != null" class="table-responsive pb-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" class="col-2">Batch Number</th>
                            <th scope="col" class="col-2">Batch Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, i) in results">
                            <td>
                                <button @click="editBatch(i)" type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editBatchModal">{{ row.batch_number }}</button>
                            </td>
                            <td>{{ row.batch_date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h1 v-else class="lead">No results</h1>
        </div>


        <div class="modal modal-lg fade" id="newBatchModal" tabindex="-1" aria-labelledby="newBatchModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newBatchModalLabel">Add New Batch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="newBatchForm != null" @submit.prevent="newBatch">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <small>For Batch Number please follow the pattern:<br/>XXXX for year number<br/>YY for month number<br/>0 for end of month batch OR 1 for mid-month batch</small>
                                    <br/>
                                    <strong>XXXXYY0 OR XXXXYY1</strong>
                                </div>
                                <div class="row g-3">

                                    <div class="col-md-4">
                                        <BreezeLabel for="newBatchNumber" class="form-label" value="Batch Number" />
                                        <BreezeInput type="number" class="form-control" maxlength="7" id="newBatchNumber" v-model="newBatchForm.batch_number" />
                                    </div>
                                    <div class="col-md-8">
                                        <BreezeLabel for="newBatchDate" class="form-label" value="Batch Date" />
                                        <BreezeInput type="date" min="1990-01-01" max="2040-12-31" class="form-control" id="newBatchDate" v-model="newBatchForm.batch_date" />
                                    </div>

                                </div>

                                <div v-if="newBatchForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="newBatchForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in newBatchForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="newBatchForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="newBatchForm.formState" :success-msg="newBatchForm.formSuccessMsg" :fail-msg="newBatchForm.formFailMsg"></FormSubmitAlert>
                    </form>
                </div>
            </div>
        </div><!-- end new ineligible reason -->

        <div class="modal modal-lg fade" id="editBatchModal" tabindex="-1" aria-labelledby="editBatchModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editBatchModalLabel">Edit Batch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="editBatchForm != null" @submit.prevent="submitEditBatch">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-4">
                                        <BreezeLabel for="editBatchNumber" class="form-label" value="Batch Number" />
                                        <BreezeInput type="number" class="form-control" maxlength="7" id="editBatchNumber" v-model="editBatchForm.batch_number" readonly="readonly" />
                                    </div>
                                    <div class="col-md-8">
                                        <BreezeLabel for="editBatchDate" class="form-label" value="Batch Date" />
                                        <BreezeInput type="date" min="1990-01-01" max="2040-12-31" class="form-control" id="editBatchDate" v-model="editBatchForm.batch_date" />
                                    </div>

                                </div>


                                <div v-if="editBatchForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="editBatchForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in editBatchForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="editBatchForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="editBatchForm.formState"></FormSubmitAlert>
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
    name: 'MaintenanceBatchs',
    components: {
        BreezeInput, Link, FormSubmitAlert, BreezeSelect, BreezeLabel
    },
    props: {
        results: Object,
    },
    data() {
        return {
            newBatchForm: null,
            newBatchFormData: {
                formState: true,
                batch_number: '',
                batch_date: '',
            },
            editBatchForm: null,

        }
    },
    methods: {
        editBatch: function (index) {
            this.editBatchForm = useForm(this.results[index]);
        },

        submitEditBatch: function () {
            this.editBatchForm.formState = '';
            this.editBatchForm.post('/yeaf/maintenance/batches/' + this.editBatchForm.id, {
                onSuccess: (response) => {
                    $("#editBatchModal").modal('hide');
                    this.editBatchForm.formState = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.editBatchForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },

        newBatch: function ()
        {
            this.newBatchForm.formState = '';
            this.newBatchForm.post('/yeaf/maintenance/batches', {
                onSuccess: (response) => {
                    $("#newBatchModal").modal('hide');
                    this.newBatchForm.reset(this.newBatchFormData);
                },
                onFailure: () => {
                },
                onError: () => {
                    this.newBatchForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },
    },
    mounted() {
        this.newBatchForm = useForm(this.newBatchFormData);
    }
}

</script>
