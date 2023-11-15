<template>
    <div class="card">
        <div class="card-header">Nature of Offence Maintenance
            <button type="button" class="btn btn-sm btn-success float-end" data-bs-toggle="modal" data-bs-target="#newNatureOffenceModal">New Nature of Offence</button>
        </div>

        <div class="modal fade" id="newNatureOffenceModal" tabindex="-1" aria-labelledby="newNatureOffenceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newNatureOffenceModalLabel">Add New Nature of Offence</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="newNatureOffence">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <BreezeLabel for="new_nature_code" value="Nature Code" />
                                        <BreezeInput id="new_nature_code" class="form-control" type="text" v-model="newForm.nature_code" :disabled="newForm.processing" />
                                    </div>
                                    <div class="col-lg-7">
                                        <BreezeLabel for="new_description" value="Description" />
                                        <BreezeInput id="new_description" class="form-control" type="text" v-model="newForm.description" :disabled="newForm.processing" />
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

        <div class="modal fade" id="editNatureOffenceModal" tabindex="-1" aria-labelledby="editNatureOffenceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editNatureOffenceModalLabel">Edit Nature of Offence</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="editNatureOffence">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <BreezeLabel for="edit_nature_code" value="Nature Code" />
                                        <BreezeInput id="edit_nature_code" class="form-control" type="text" v-model="editForm.nature_code" :disabled="editForm.processing" />
                                    </div>
                                    <div class="col-lg-7">
                                        <BreezeLabel for="edit_description" value="Description" />
                                        <BreezeInput id="edit_description" class="form-control" type="text" v-model="editForm.description" :disabled="editForm.processing" />
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
                <table aria-label="Nature of Offence Maintenance List" class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Nature Code</th>
                        <th scope="col">Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, i) in results">
                        <th scope="row"><button type="button" class="btn btn-link" @click="editRow(row,i)">{{ row.nature_code }}</button></th>
                        <td>{{ row.description }}</td>
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
                        Nature of Offence submitted successfully.
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

export default {
    name: 'MaintenanceNatureOffences',
    components: {
        BreezeInput, BreezeLabel, Link
    },
    props: {
        results: Object,
    },
    data() {
        return {
            showSuccessMsg: false,
            showFailMsg: false,

            newForm: useForm({
                nature_code: '',
                description: '',
            }),
            editForm: useForm({
                nature_code: '',
                description: '',
                id: '',
            }),

        }
    },
    methods: {
        editRow: function (row, index){
            this.editForm.nature_code = row.nature_code;
            this.editForm.description = row.description;
            this.editForm.id = row.id;

            $("#editNatureOffenceModal").modal('show');

        },
        editNatureOffence: function ()
        {
            this.editForm.put('/vss/maintenance/nature-offence/' + this.editForm.id, {
                onSuccess: () => {
                    this.showSuccessAlert();
                    this.editForm.reset('nature_code', 'description', 'id');

                    $("#editNatureOffenceModal").modal('hide');

                },
                onFailure: () => {
                },
                onError: () => {
                    this.showFailAlert();
                },
                preserveState: true
            });
        },
        newNatureOffence: function ()
        {
            this.newForm.post('/vss/maintenance/nature-offence', {
                onSuccess: () => {
                    this.showSuccessAlert();
                    this.newForm.reset('nature_code', 'description');

                    $("#newNatureOffenceModal").modal('hide');

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
