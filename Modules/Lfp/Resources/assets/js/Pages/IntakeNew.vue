<style scoped>
tr {
    padding-bottom: 7px;
    display: block;
}
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>

    <AuthenticatedLayout v-bind="$attrs">

            <div class="mt-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    VSS Case Search
                                </div>
                                <div class="card-body">
                                    <ApplicationSearchBox />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 mt-3 mb-5">
                            <div class="card">
                                <div class="card-header">
                                    VSS Create New Case
                                </div>
                                <form @submit.prevent="storeCase">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <table>
                                                    <tr>
                                                        <th scope="row">SIN:</th>
                                                        <td class="ps-1">
                                                            <BreezeInput class="form-control" type="number" oninput="javascript: if (this.value.length > this.maxLength) editForm.sin = this.value.slice(0, this.maxLength);" maxlength="9" v-model="editForm.sin" aria-required="true" />
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th scope="row">Year of Audit:</th>
                                                        <td class="ps-1">
                                                            <BreezeInput class="form-control" type="text" placeholder="i.e 21/22" maxlength="5" v-model="editForm.year_of_audit" aria-required="true" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Date Opened:</th>
                                                        <td class="ps-1">
                                                            <BreezeInput class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.open_date" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-lg-4">

                                                <table>
                                                    <tr>
                                                        <th scope="row">Last Name:</th>
                                                        <td class="ps-1">
                                                            <BreezeInput class="form-control" type="text" v-model="editForm.last_name" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Application:</th>
                                                        <td class="ps-1">
                                                            <BreezeInput class="form-control" type="number" v-model="editForm.application_number" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Reactivate Date:</th>
                                                        <td class="ps-1">
                                                            <BreezeInput class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.reactivate_date" />
                                                        </td>
                                                    </tr>

                                                </table>

                                            </div>
                                            <div class="col-lg-4">

                                                <table>
                                                    <tr>
                                                        <th scope="row">First Name:</th>
                                                        <td class="ps-1">
                                                            <BreezeInput class="form-control" type="text" v-model="editForm.first_name" />
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th scope="row">School:</th>
                                                        <td class="ps-1">
                                                            <BreezeSelect class="form-select" v-model="editForm.institution_code">
                                                                <option v-for="(school,j) in schools" :value="school.institution_code">{{ school.institution_name }} | {{ school.institution_code }}</option>
                                                            </BreezeSelect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Status Code:</th>
                                                        <td class="ps-1">
                                                            <BreezeSelect class="form-select" v-model="editForm.incident_status">
                                                                <option value="Active">Active</option>
                                                                <option value="Re-activated">Re-activated</option>
                                                                <option value="Inactive">Inactive</option>
                                                            </BreezeSelect>
                                                        </td>
                                                    </tr>
                                                </table>

                                            </div>
                                        </div>

                                        <div v-if="newForm !== null" class="row">
                                            <div class="col-12">
                                                <div v-if="newForm.hasErrors === true" class="alert alert-danger mt-3">
                                                    <ul>
                                                        <li v-for="err in newForm.errors">{{ err }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn me-2 btn-outline-success" :disabled="this.editForm.processing">Create Case</button>
                                        <Link @click="back" class="btn btn-outline-primary float-end" href="#">Back</Link>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="showSuccessMsg" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="updateSuccessAlert" class="alert alert-success alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="100">
                    <div class="">
                        <div class="toast-body">
                            Case record was created successfully.
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>


        </AuthenticatedLayout>

</template>
<script>

import AuthenticatedLayout from '../Layouts/Authenticated.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ApplicationSearchBox from '../Components/ApplicationSearch.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeSelect from '@/Components/Select.vue';

export default {
    name: 'IntakeNew',
    components: {
        AuthenticatedLayout, ApplicationSearchBox, Head, Link, BreezeInput, BreezeSelect, BreezeLabel
    },
    props: {
        funds: Object,
        now: String,
        schools: Object,
        areaOfAudits: Object,
        natureOffences: Object,
        referrals: Object,
        sanctions: Object,
        staff: Object
    },
    data() {
        return {
            noChanges: true,
            newRows: [],
            showSuccessMsg: false,

            newAreaOfAuditRows: [],
            newOffenceRows: [],
            newSanctionRows: [],

            editForm: {

                sin: '',
                institution_code: '',
                last_name: '',
                first_name: '',
                year_of_audit: '',
                open_date: '',
                application_number: '',
                reactivate_date: '',
                incident_status: '',
                referral_source_id: '',
                severity: '',
                auditor_user_id: '',
                audit_date: '',
                investigator_user_id: '',
                investigation_date: '',
                area_of_audit_code: '',
                audit_type: '',
                bring_forward_date: '',
                appeal_outcome: '',
                close_date: '',
                reason_for_closing: '',
                case_outcome: '',
                rcmp_referral_date: '',
                rcmp_closure_date: '',
                sentence_comment: '',

                new_audit_codes: '',
                new_offence_codes: '',
                new_sanction_codes: '',

                old_audit_codes: [],
                old_offence_codes: [],
                old_sanction_codes: [],

                bring_forward: false,
                rcmp_referral_flag: false,
                conviction_flag: false,
                charges_laid_flag: false,
                appeal_flag: false,
                case_close: false,
            },
            newForm: null,
        }
    },
    methods: {

        storeCase: function ()
        {

            this.editForm.new_audit_codes = this.newAreaOfAuditRows;
            this.editForm.new_offence_codes = this.newOffenceRows;
            this.editForm.new_sanction_codes = this.newSanctionRows;
            this.newForm = useForm(this.editForm);

            this.newForm.post('/lfp/intakes', {
                onSuccess: () => {
                    this.showSuccessAlert();
                },
            });
            // form.wasSuccessful();
        },
        showSuccessAlert: function ()
        {
            this.showSuccessMsg = true;
            let vm = this;
            setTimeout(function (){
                vm.showSuccessMsg = false;
                vm.noChanges = true;
            }, 5000);
        },


        back: function()
        {
            window.history.back();
        },


    },
}
</script>
