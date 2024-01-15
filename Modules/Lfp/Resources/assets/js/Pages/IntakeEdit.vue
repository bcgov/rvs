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
                                    LFP Search
                                </div>
                                <div class="card-body">
                                    <ApplicationSearchBox />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 mt-3 mb-5">
                            <div class="card">
                                <div v-if="editForm != null" class="card-header">
                                    Intake App Edit
                                    <span class="btn btn-sm rounded-pill text-bg-primary ms-2 disabled">{{ editForm.intake_status }}</span>
                                </div>
                                <template v-if="editForm != null">
                                    <form @submit.prevent="updateCase">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <table>
                                                        <tr>
                                                            <th scope="row">SIN:</th>
                                                            <td class="ps-1">{{ editForm.sin }}</td>
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
                                                                <BreezeInput class="form-control" type="date" min="1019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" v-model="editForm.open_date" />
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
                                                                <BreezeInput class="form-control" type="date" min="1019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" v-model="editForm.reactivate_date" />
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
<!--                                                                <BreezeSelect class="form-select" v-model="editForm.institution_code">-->
<!--                                                                    <option v-for="(school,j) in schools" :value="school.institution_code">{{ school.institution_name }} | {{ school.institution_code }}</option>-->
<!--                                                                </BreezeSelect>-->
                                                                <BreezeInput @focusout="resetSchoolFilter" @keyup="filterActiveSchools($event)" type="text" class="form-control" id="inputInstitution" v-model="editForm.institution.institution_name" />
                                                                <input type="hidden" v-model="editForm.institution_code" />
                                                                <ul class="dropdown-menu" :class="editForm.schoolsListHidden === false ? 'show' : 'hidden'" data-popper-placement="top-start" style="
    position: absolute;
    right: 0;
    overflow-y: scroll;
    height: 400px;
">
                                                                    <template v-for="(school,j) in schoolsList">
                                                                        <li @click="assignSchool(school, j)" :value="school.institution_code" class="dropdown-item">{{ school.institution_code }} | {{ school.institution_name }}</li>
                                                                    </template>
                                                                </ul>
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

                                            <div v-if="editForm.errors != undefined" class="row">
                                                <div class="col-12">
                                                    <div v-if="editForm.hasErrors == true" class="alert alert-danger mt-3">
                                                        <ul>
                                                            <li v-for="err in editForm.errors">{{ err }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="editForm.processing">Update Case</button>
                                            <Link @click="back" class="btn btn-outline-primary float-end" href="#">Back</Link>
                                            <Link :href="'/vss/case-funding/' + result.id" class="btn btn-outline-warning float-end me-2">Funds</Link>
                                            <Link :href="'/vss/case-comment/' + result.id" class="btn btn-outline-dark float-end me-2">Comments</Link>
                                        </div>
                                    </form>
                                </template>
                                <h1 v-else class="lead">No results</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="showSuccessMsg" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="updateSuccessAlert" class="alert alert-success alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="100">
                    <div class="">
                        <div class="toast-body">
                            Case record was updated successfully.
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
import NavLink from "@/Components/NavLink";

export default {
    name: 'IntakeEdit',
    components: {
        NavLink,
        AuthenticatedLayout, ApplicationSearchBox, Head, Link, BreezeInput, BreezeSelect, BreezeLabel
    },
    props: {
        result: Object,
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

            editForm: null,
            schoolsList: [],


        }
    },
    methods: {

        updateCase: function ()
        {
            this.editForm.processing = true;

            let oldAuditCodes = [];
            for(let i=0; i<this.editForm.audits.length; i++)
            {
                oldAuditCodes.push({
                    'area_of_audit_code': this.editForm.audits[i].area_of_audit_code,
                    'audit_type': this.editForm.audits[i].audit_type
                });
            }

            let oldNatureCodes = [];
            for(let i=0; i<this.editForm.offences.length; i++)
            {
                oldNatureCodes.push({
                    'nature_code': this.editForm.offences[i].nature_code
                });
            }

            let oldSanctionCodes = [];
            for(let i=0; i<this.editForm.sanctions.length; i++)
            {
                oldSanctionCodes.push({
                    'sanction_code': this.editForm.sanctions[i].sanction_code
                });
            }

            this.editForm = useForm({

                incident_id: this.editForm.incident_id,
                sin: this.editForm.sin,
                institution_code: this.editForm.institution_code,
                last_name: this.editForm.last_name,
                first_name: this.editForm.first_name,
                year_of_audit: this.editForm.year_of_audit,
                open_date: this.editForm.open_date,
                application_number: this.editForm.application_number,
                reactivate_date: this.editForm.reactivate_date,
                incident_status: this.editForm.incident_status,
                referral_source_id: this.editForm.referral_source_id,
                severity: this.editForm.severity,
                auditor_user_id: this.editForm.auditor_user_id,
                audit_date: this.editForm.audit_date,
                investigator_user_id: this.editForm.investigator_user_id,
                investigation_date: this.editForm.investigation_date,
                area_of_audit_code: this.editForm.area_of_audit_code,
                audit_type: this.editForm.audit_type,
                bring_forward: this.editForm.bring_forward,
                bring_forward_date: this.editForm.bring_forward_date,
                appeal_flag: this.editForm.appeal_flag,
                appeal_outcome: this.editForm.appeal_outcome,
                case_close: this.editForm.case_close,
                close_date: this.editForm.close_date,
                reason_for_closing: this.editForm.reason_for_closing,
                case_outcome: this.editForm.case_outcome,
                rcmp_referral_flag: this.editForm.rcmp_referral_flag,
                rcmp_referral_date: this.editForm.rcmp_referral_date,
                rcmp_closure_date: this.editForm.rcmp_closure_date,
                charges_laid_flag: this.editForm.charges_laid_flag,
                conviction_flag: this.editForm.conviction_flag,
                sentence_comment: this.editForm.sentence_comment,


                old_audit_codes: oldAuditCodes,
                new_audit_codes: this.newAreaOfAuditRows,

                old_offence_codes: oldNatureCodes,
                new_offence_codes: this.newOffenceRows,

                old_sanction_codes: oldSanctionCodes,
                new_sanction_codes: this.newSanctionRows,

            });

            this.addDefaultLists();

            this.editForm.put('/lfp/intakes/update/' + this.result.id, {
                onSuccess: () => {
                    this.showSuccessAlert();
                },
                onError: () => {
                    this.addDefaultLists();
                }
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
    watch: {
    },
    computed: {
    },
    mounted() {
    }
}
</script>
