<template>
    <div class="card">
        <div class="card-header">Sanction Types Maintenance
            <button type="button" class="btn btn-sm btn-success float-end" data-bs-toggle="modal" data-bs-target="#newSanctionTypeModal">New Sanction Type</button>
        </div>

        <div class="modal fade" id="newSanctionTypeModal" tabindex="-1" aria-labelledby="newSanctionTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newSanctionTypeModalLabel">Add New Sanction Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="newSanctionType">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <BreezeLabel for="new_description" value="Description" />
                                        <BreezeInput id="new_description" class="form-control" type="text" v-model="newForm.description" :disabled="newForm.processing" />
                                    </div>
                                    <div class="col-lg-8">
                                        <BreezeLabel for="new_short_description" value="Short Description" />
                                        <BreezeInput id="new_short_description" class="form-control" type="text" v-model="newForm.short_description" :disabled="newForm.processing" />
                                    </div>
                                    <div class="col-lg-4">
                                        <BreezeLabel for="new_disabled" value="Status" />
                                        <BreezeSelect id="new_disabled" class="form-control" v-model="newForm.disabled" required :disabled="newForm.processing">
                                            <option value="false">Active</option>
                                            <option value="true">Disabled</option>
                                        </BreezeSelect>
                                    </div>

                                </div>

                                <div v-if="newForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="newForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in newForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="newForm.processing">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editSanctionTypeModal" tabindex="-1" aria-labelledby="editSanctionTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSanctionTypeModalLabel">Edit Sanction Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="editSanctionType">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <BreezeLabel for="edit_description" value="Description" />
                                        <BreezeInput id="edit_description" class="form-control" type="text" v-model="editForm.description" :disabled="editForm.processing" />
                                    </div>
                                    <div class="col-lg-8">
                                        <BreezeLabel for="edit_short_description" value="Short Description" />
                                        <BreezeInput id="edit_short_description" class="form-control" type="text" v-model="editForm.short_description" :disabled="editForm.processing" />
                                    </div>
                                    <div class="col-lg-4">
                                        <BreezeLabel for="edit_disabled" value="Status" />
                                        <BreezeSelect id="edit_disabled" class="form-control" v-model="editForm.disabled" required :disabled="editForm.processing">
                                            <option value="false">Active</option>
                                            <option value="true">Disabled</option>
                                        </BreezeSelect>
                                    </div>
                                </div>

                                <div v-if="editForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="editForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in editForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="editForm.processing">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="results != null" class="table-responsive pb-3">
                <table aria-label="Sanction Types Maintenance List" class="table table-striped">
                    <thead>
                        <tr>
<!--                            <th scope="col">Sanction Code</th>-->
                            <th scope="col">Description</th>
                            <th scope="col">Short Description</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, i) in results">
<!--                            <th scope="row"><button type="button" class="btn btn-link" @click="editRow(row,i)">{{ row.area_of_audit_code }}</button></th>-->
                            <td><button type="button" class="btn btn-link" @click="editRow(row,i)">{{ row.description }}</button></td>
                            <td>{{ row.short_description }}</td>
                            <td>
                                <span v-if="row.disabled" class="badge rounded-pill text-bg-danger">Disabled</span>
                                <span v-else class="badge rounded-pill text-bg-success">Active</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h1 v-else class="lead">No results</h1>
        </div>

        <div v-if="showSuccessMsg" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="updateSuccessAlert" class="alert alert-success alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="100">
                <div class="">
                    <div class="toast-body">
                        Sanction Type submitted successfully.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div v-if="showFailMsg" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="updateFailAlert" class="alert alert-danger alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="100">
                <div class="">
                    <div class="toast-body">
                        There was an error submitting this form.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>

    </div>

</template>
<script>
import {Link, useForm} from '@inertiajs/vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from '@/Components/Select.vue';

export default {
    name: 'MaintenanceSanctionTypes',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect,
    },
    props: {
        results: Object,
    },
    data() {
        return {
            showSuccessMsg: false,
            showFailMsg: false,

            newForm: useForm({
                description: '',
                short_description: '',
                disabled: '',
            }),
            editForm: useForm({
                sanction_code: '',
                description: '',
                short_description: '',
                disabled: '',
                id: '',
            }),

        }
    },
    methods: {
        editRow: function (row, index){
            this.editForm.sanction_code = row.sanction_code;
            this.editForm.description = row.description;
            this.editForm.short_description = row.short_description;
            this.editForm.disabled = row.disabled;
            this.editForm.id = row.id;

            $("#editSanctionTypeModal").modal('show');

        },
        editSanctionType: function ()
        {
            this.editForm.put('/vss/maintenance/sanction-type/' + this.editForm.id, {
                onSuccess: () => {
                    this.showSuccessAlert();
                    this.editForm.reset('sanction_code', 'description', 'short_description', 'disabled');

                    $("#editSanctionTypeModal").modal('hide');

                },
                onFailure: () => {
                },
                onError: () => {
                    this.showFailAlert();
                },
                preserveState: true
            });
        },
        newSanctionType: function ()
        {
            this.newForm.post('/vss/maintenance/sanction-type', {
                onSuccess: () => {
                    this.showSuccessAlert();
                    this.newForm.reset('description', 'short_description', 'disabled');

                    $("#newSanctionTypeModal").modal('hide');

                },
                onFailure: () => {
                },
                onError: () => {
                    this.showFailAlert();
                },
                preserveState: true

            });
        },
        showSuccessAlert: function ()
        {
            this.showSuccessMsg = true;
            let vm = this;
            setTimeout(function (){
                vm.showSuccessMsg = false;
            }, 5000);
        },
        showFailAlert: function ()
        {
            this.showFailMsg = true;
            let vm = this;
            setTimeout(function (){
                vm.showFailMsg = false;
            }, 5000);
        },
    },
    watch: {
    },
    computed: {
    },
    mounted() {
    }
}

</script>
