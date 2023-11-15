<template>
    <div class="card">
        <div class="card-header">
            <div>Program Year Maintenance
                <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newProgramYearModal">New Program Year</button>
                <br/><small>(Program year runs from August 01 to July 31)</small>
            </div>
        </div>

        <div class="card-body">
            <div v-if="results != null" class="table-responsive pb-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" class="col-2">Year Start</th>
                            <th scope="col" class="col-2">Year End</th>
                            <th scope="col" class="col-2">Grant Amount</th>
                            <th scope="col" class="col-2">Max Yrs in Prog.</th>
                            <th scope="col" class="col-2">Min Age</th>
                            <th scope="col" class="col-2">Max Age</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, i) in results">
                            <td>
                                <button @click="editProgramYear(i)" type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editProgramYearModal">{{ row.year_start }}</button>
                            </td>
                            <td>{{ row.year_end }}</td>
                            <td>${{ row.grant_amount }}</td>
                            <td>{{ row.max_years_allowed }}</td>
                            <td>{{ row.min_age }}</td>
                            <td>{{ row.max_age }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h1 v-else class="lead">No results</h1>
        </div>


        <div class="modal modal-lg fade" id="newProgramYearModal" tabindex="-1" aria-labelledby="newProgramYearModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newProgramYearModalLabel">Add New Program Year</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="newProgramYearForm != null" @submit.prevent="newProgramYear">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-2">
                                        <BreezeLabel for="newYearStart" class="form-label" value="Year Start" />
                                        <BreezeInput type="number" class="form-control" maxlength="4" id="newYearStart" v-model="newProgramYearForm.year_start" />
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="newYearEnd" class="form-label" value="Year End" />
                                        <BreezeInput type="number" class="form-control" maxlength="4" id="newYearEnd" v-model="newProgramYearForm.year_end" />
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="newGrantAmount" class="form-label" value="Grant Amount" />
                                        <BreezeInput type="number" class="form-control" id="newGrantAmount" v-model="newProgramYearForm.grant_amount" />
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="newMaxYearAllowed" class="form-label" value="Max Yrs Allowed" />
                                        <BreezeInput type="number" class="form-control" maxlength="2" id="newMaxYearAllowed" v-model="newProgramYearForm.max_years_allowed" />
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="newMinAge" class="form-label" value="Min Age" />
                                        <BreezeInput type="number" class="form-control" maxlength="2" id="newMinAge" v-model="newProgramYearForm.min_age" />
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="newMaxAge" class="form-label" value="Max Age" />
                                        <BreezeInput type="number" class="form-control" maxlength="2" id="newMaxAge" v-model="newProgramYearForm.max_age" />
                                    </div>

                                </div>

                                <div v-if="newProgramYearForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="newProgramYearForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in newProgramYearForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="newProgramYearForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="newProgramYearForm.formState" :success-msg="newProgramYearForm.formSuccessMsg" :fail-msg="newProgramYearForm.formFailMsg"></FormSubmitAlert>
                    </form>
                </div>
            </div>
        </div><!-- end new ineligible reason -->

        <div class="modal modal-lg fade" id="editProgramYearModal" tabindex="-1" aria-labelledby="editProgramYearModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProgramYearModalLabel">Edit Program Year</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="editProgramYearForm != null" @submit.prevent="submitEditProgramYear">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-2">
                                        <BreezeLabel for="editYearStart" class="form-label" value="Year Start" />
                                        <BreezeInput type="number" class="form-control" maxlength="4" id="editYearStart" v-model="editProgramYearForm.year_start" />
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="editYearEnd" class="form-label" value="Year End" />
                                        <BreezeInput type="number" class="form-control" maxlength="4" id="editYearEnd" v-model="editProgramYearForm.year_end" />
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="editGrantAmount" class="form-label" value="Grant Amount" />
                                        <BreezeInput type="number" class="form-control" id="editGrantAmount" v-model="editProgramYearForm.grant_amount" />
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="editMaxYearAllowed" class="form-label" value="Max Yrs Allowed" />
                                        <BreezeInput type="number" class="form-control" maxlength="2" id="editMaxYearAllowed" v-model="editProgramYearForm.max_years_allowed" />
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="editMinAge" class="form-label" value="Min Age" />
                                        <BreezeInput type="number" class="form-control" maxlength="2" id="editMinAge" v-model="editProgramYearForm.min_age" />
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel for="editMaxAge" class="form-label" value="Max Age" />
                                        <BreezeInput type="number" class="form-control" maxlength="2" id="editMaxAge" v-model="editProgramYearForm.max_age" />
                                    </div>

                                </div>


                                <div v-if="editProgramYearForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="editProgramYearForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in editProgramYearForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="editProgramYearForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="editProgramYearForm.formState"></FormSubmitAlert>
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
    name: 'MaintenanceProgramYears',
    components: {
        BreezeInput, Link, FormSubmitAlert, BreezeSelect, BreezeLabel
    },
    props: {
        results: Object,
    },
    data() {
        return {
            newProgramYearForm: null,
            newProgramYearFormData: {
                formState: true,
                year_start: 0, year_end: 0, grant_amount: 0, max_years_allowed: 0, min_age: 0, max_age: 100,
            },
            editProgramYearForm: null,

        }
    },
    methods: {
        editProgramYear: function (index) {
            this.editProgramYearForm = useForm(this.results[index]);
        },

        submitEditProgramYear: function () {
            this.editProgramYearForm.formState = '';
            this.editProgramYearForm.post('/yeaf/maintenance/program-year/' + this.editProgramYearForm.id, {
                onSuccess: (response) => {
                    $("#editProgramYearModal").modal('hide');
                    this.editProgramYearForm.formState = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.editProgramYearForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },

        newProgramYear: function ()
        {
            this.newProgramYearForm.formState = '';
            this.newProgramYearForm.post('/yeaf/maintenance/program-year', {
                onSuccess: (response) => {
                    $("#newProgramYearModal").modal('hide');
                    this.newProgramYearForm.reset(this.newProgramYearFormData);
                },
                onFailure: () => {
                },
                onError: () => {
                    this.newProgramYearForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },
    },
    mounted() {
        this.newProgramYearForm = useForm(this.newProgramYearFormData);
    }
}

</script>
