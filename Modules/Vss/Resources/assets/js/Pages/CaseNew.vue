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
    <div>
        <Head title="VSS - Case Funding Edit" />

        <BreezeAuthenticatedLayout>

            <div class="mt-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    VSS Case Search
                                </div>
                                <div class="card-body">
                                    <CaseSearchBox />
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

                                        <!-- break -->
                                        <hr>
                                        <!-- break -->

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <table>
                                                    <tr>
                                                        <th scope="row">Referral:</th>
                                                        <td class="ps-1">
                                                            <BreezeSelect class="form-select" v-model="editForm.referral_source_id">
                                                                <option v-for="(ref,j) in referrals" :value="ref.id">{{ ref.description }}</option>
                                                            </BreezeSelect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Severity:</th>
                                                        <td class="ps-1">
                                                            <BreezeSelect class="form-select" v-model="editForm.severity">
                                                                <option value="A">Audit</option>
                                                                <option value="I">Investigation</option>
                                                            </BreezeSelect>
                                                        </td>
                                                    </tr>

                                                </table>
                                            </div>
                                            <div class="col-lg-4">

                                                <table>
                                                    <tr>
                                                        <th scope="row">Auditor:</th>
                                                        <td class="ps-1">
                                                            <BreezeSelect class="form-select" v-model="editForm.auditor_user_id">
                                                                <option value=""></option>
                                                                <option v-for="(u,j) in staff" :value="u.user_id">{{ u.first_name }} {{ u.last_name }} | {{ u.user_id }}</option>
                                                            </BreezeSelect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Aud. Date:</th>
                                                        <td class="ps-1">
                                                            <BreezeInput class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.audit_date" />
                                                        </td>
                                                    </tr>

                                                </table>

                                            </div>
                                            <div class="col-lg-4">

                                                <table>
                                                    <tr>
                                                        <th scope="row">Investigator:</th>
                                                        <td class="ps-1">
                                                            <BreezeSelect class="form-select" v-model="editForm.investigator_user_id">
                                                                <option value=""></option>
                                                                <option v-for="(u,j) in staff" :value="u.user_id">{{ u.first_name }} {{ u.last_name }} | {{ u.user_id }}</option>
                                                            </BreezeSelect>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th scope="row">Inv. Date:</th>
                                                        <td class="ps-1">
                                                            <BreezeInput class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.investigation_date" />
                                                        </td>
                                                    </tr>
                                                </table>

                                            </div>
                                        </div>


                                        <!-- break -->
                                        <hr>
                                        <!-- break -->


                                        <div class="row">
                                            <div class="col-lg-8">
                                                <table>
                                                    <tr>
                                                        <th scope="row">Primary Area of Audit:</th>
                                                        <td class="ps-1">
                                                            <BreezeSelect class="form-select" v-model="editForm.area_of_audit_code">
                                                                <option v-for="area in areaOfAudits" :value="area.area_of_audit_code">{{ area.description }}</option>
                                                            </BreezeSelect>
                                                        </td>
                                                    </tr>

                                                </table>
                                            </div>
                                            <div class="col-lg-4">

                                                <table>
                                                    <tr>
                                                        <th scope="row">Audit Type:</th>
                                                        <td class="ps-1">
                                                            <BreezeSelect class="form-select" v-model="editForm.audit_type">
                                                                <option value="A">Pre-Audit</option>
                                                                <option value="P">Post-Audit</option>
                                                            </BreezeSelect>
                                                        </td>
                                                    </tr>

                                                </table>

                                            </div>

                                        </div>

                                        <!-- break -->
                                        <div class="card mt-3">
                                            <div class="card-header text-muted">Additional Areas of Audit</div>
                                            <div class="card-body d-grid gap-2">

                                                <div v-for="(aud, i) in editForm.audits" class="row">
                                                    <div class="col-lg-8">
                                                        <table>
                                                            <tr>
                                                                <th scope="row">Area of Audit:</th>
                                                                <td class="ps-1">
                                                                    <BreezeSelect class="form-select" v-model="aud.area_of_audit_code">
                                                                        <option v-for="area in areaOfAudits" :value="area.area_of_audit_code">{{ area.description }}</option>
                                                                    </BreezeSelect>
                                                                </td>
                                                            </tr>

                                                        </table>
                                                    </div>
                                                    <div class="col-lg-4">

                                                        <table>
                                                            <tr>
                                                                <th scope="row">Audit Type:</th>
                                                                <td class="ps-1">
                                                                    <BreezeSelect class="form-select" v-model="aud.audit_type">
                                                                        <option value="A">Pre-Audit</option>
                                                                        <option value="P">Post-Audit</option>
                                                                    </BreezeSelect>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </div>

                                                </div><!-- end of row -->

                                                <div v-for="(aud, i) in newAreaOfAuditRows" class="row">
                                                    <div class="col-lg-8">
                                                        <table>
                                                            <tr>
                                                                <th scope="row">Area of Audit:</th>
                                                                <td class="ps-1">
                                                                    <BreezeSelect class="form-select" v-model="aud.area_of_audit_code">
                                                                        <option v-for="area in areaOfAudits" :value="area.area_of_audit_code">{{ area.description }}</option>
                                                                    </BreezeSelect>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <table>
                                                            <tr>
                                                                <th scope="row">Audit Type:</th>
                                                                <td class="ps-1">
                                                                    <BreezeSelect class="form-select" v-model="aud.audit_type">
                                                                        <option value="A">Pre-Audit</option>
                                                                        <option value="P">Post-Audit</option>
                                                                    </BreezeSelect>
                                                                </td>
                                                                <td class="ps-1"><button @click="removeNewAreaOfAuditRow(i)" type="button" class="btn-close m-auto" aria-label="Close"></button></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div><!-- end of additional new row -->
                                                <div><button @click="addNewAreaOfAuditRow" type="button" class="btn btn-link">+ Add additional area of audit</button></div>

                                            </div>
                                        </div>

                                        <!-- break -->
                                        <hr>
                                        <!-- break -->

                                        <div class="card mt-3">
                                            <div class="card-header text-muted">Nature of Offense(s)</div>
                                            <div class="card-body d-grid gap-2">

                                                <div class="row">
                                                    <div class="col-auto">
                                                        <table>
                                                            <tr v-for="(row, i) in editForm.offences">
                                                                <th scope="row">Offense:</th>
                                                                <td class="ps-1">
                                                                    <BreezeSelect class="form-select" v-model="row.nature_code">
                                                                        <option v-for="offence in natureOffences" :value="offence.nature_code">{{ offence.description }}</option>
                                                                    </BreezeSelect>
                                                                </td>

                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div v-for="(row, i) in newOffenceRows" class="row">
                                                    <div class="col-auto">
                                                        <table>
                                                            <tr>
                                                                <th scope="row">Nature of Offense:</th>
                                                                <td class="ps-1">
                                                                    <BreezeSelect class="form-select" v-model="row.nature_code">
                                                                        <option v-for="offence in natureOffences" :value="offence.nature_code">{{ offence.description }}</option>
                                                                    </BreezeSelect>
                                                                </td>
                                                                <td class="ps-1"><button @click="removeNewOffenceRow(i)" type="button" class="btn-close m-auto" aria-label="Close"></button></td>

                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div><!-- end of additional new row -->
                                                <div><button @click="addNewOffenceRow" type="button" class="btn btn-link">+ Add additional nature of offence</button></div>
                                            </div>
                                        </div>



                                        <!-- break -->
                                        <hr>
                                        <!-- break -->


                                        <div class="card mt-3">
                                            <div class="card-header text-muted">Sanction(s)</div>
                                            <div class="card-body d-grid gap-2">

                                                <div class="row">
                                                    <div class="col-auto">
                                                        <table>
                                                            <tr v-for="(row, i) in editForm.sanctions">
                                                                <th scope="row">Sanction Type:</th>
                                                                <td class="ps-1">
                                                                    <BreezeSelect class="form-select" v-model="row.sanction_code">
                                                                        <option v-for="sanction in sanctions" :value="sanction.sanction_code">{{ sanction.short_description }} | {{ sanction.description }}</option>
                                                                    </BreezeSelect>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div v-for="(row, i) in newSanctionRows" class="row">
                                                    <div class="col-auto">
                                                        <table>
                                                            <tr>
                                                                <th scope="row">Sanction Type:</th>
                                                                <td class="ps-1">
                                                                    <BreezeSelect class="form-select" v-model="row.sanction_code">
                                                                        <option v-for="sanction in sanctions" :value="sanction.sanction_code">{{ sanction.short_description }} | {{ sanction.description }}</option>
                                                                    </BreezeSelect>
                                                                </td>
                                                                <td class="ps-1"><button @click="removeNewSanctionRow(i)" type="button" class="btn-close m-auto" aria-label="Close"></button></td>

                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div><!-- end of additional new row -->
                                                <div><button @click="addNewSanctionRow" type="button" class="btn btn-link">+ Add additional sanction type</button></div>
                                            </div>
                                        </div>



                                        <!-- break -->
                                        <hr>
                                        <!-- break -->


                                        <div class="row">

                                            <div class="col-lg-3">
                                                <table>
                                                    <tr>
                                                        <th scope="row">Bring Forward</th>
                                                        <td class="ps-1">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" role="switch" id="bringForwardSwitch" v-model="editForm.bring_forward">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ps-1">
                                                            <BreezeLabel for="bring_forward_date">Date</BreezeLabel>
                                                            <BreezeInput id="bring_forward_date" class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.bring_forward_date" :disabled="!editForm.bring_forward" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div><!-- end of col-lg-3 -->
                                            <div class="col-lg-3">
                                                <table>
                                                    <tr>
                                                        <th scope="row">Appealed</th>
                                                        <td class="ps-1">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" role="switch" id="appealedSwitch" v-model="editForm.appeal_flag">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ps-1">
                                                            <BreezeLabel for="appeal_outcome">Appeal Outcome</BreezeLabel>
                                                            <BreezeSelect id="appeal_outcome" class="form-select" v-model="editForm.appeal_outcome" :disabled="!editForm.appeal_flag">
                                                                <option value=""></option>
                                                                <option value="A">Approved</option>
                                                                <option value="D">Denied</option>
                                                                <option value="P">Approved in Part</option>
                                                            </BreezeSelect>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div><!-- end of col-lg-3 -->
                                            <div class="col-lg-3">
                                                <table>
                                                    <tr>
                                                        <th scope="row">Case Closed</th>
                                                        <td class="ps-1">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" role="switch" id="caseClosedSwitch" v-model="editForm.case_close">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ps-1">
                                                            <BreezeLabel for="case_date">Date</BreezeLabel>
                                                            <BreezeInput id="case_date" class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.close_date" :disabled="!editForm.case_close" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ps-1">
                                                            <BreezeLabel for="case_reason">Reason</BreezeLabel>
                                                            <BreezeSelect id="case_reason" class="form-select" v-model="editForm.reason_for_closing" :disabled="!editForm.case_close">
                                                                <option value=""></option>
                                                                <option value="C">Complete</option>
                                                                <option value="D">Deceased</option>
                                                                <option value="N">No Response</option>
                                                            </BreezeSelect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ps-1">
                                                            <BreezeLabel for="case_outcome">Case Outcome</BreezeLabel>
                                                            <BreezeSelect id="case_outcome" class="form-select" v-model="editForm.case_outcome" :disabled="!editForm.case_close">
                                                                <option value=""></option>
                                                                <option value="S">Substantiated</option>
                                                                <option value="U">Unsubstantiated</option>
                                                            </BreezeSelect>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div><!-- end of col-lg-3 -->


                                            <div class="col-lg-3">
                                                <table>
                                                    <tr>
                                                        <th scope="row">Referred to RCMP</th>
                                                        <td class="ps-1">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" role="switch" id="referredToRcmpSwitch" v-model="editForm.rcmp_referral_flag">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ps-1">
                                                            <BreezeLabel for="date_referred_to_rcmp">Date referred to RCMP</BreezeLabel>
                                                            <BreezeInput id="date_referred_to_rcmp" class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.rcmp_referral_date" :disabled="!editForm.rcmp_referral_flag" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ps-1">
                                                            <BreezeLabel for="date_closed_by_rcmp">Date closed by RCMP</BreezeLabel>
                                                            <BreezeInput id="date_closed_by_rcmp" class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.rcmp_closure_date" :disabled="!editForm.rcmp_referral_flag" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Charges laid</th>
                                                        <td class="ps-1">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" role="switch" id="chargesLaidSwitch" v-model="editForm.charges_laid_flag">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Convicted</th>
                                                        <td class="ps-1">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" role="switch" id="convictedSwitch" v-model="editForm.conviction_flag">
                                                            </div>
                                                        </td>
                                                    </tr>

                                                </table>
                                            </div><!-- end of col-lg-3 -->

                                        </div>



                                        <!-- break -->
                                        <hr>
                                        <!-- break -->




                                        <div class="row">
                                            <div class="col-lg-3 text-lg-end text-start"><strong>Sentence Comments:</strong></div>
                                            <div class="col-lg-9">
                                                <textarea class="form-control" v-model="editForm.sentence_comment" rows="3"></textarea>
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


        </BreezeAuthenticatedLayout>
    </div>

</template>
<script>
import {computed} from "vue";

import BreezeAuthenticatedLayout from '../Layouts/Authenticated.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import CaseSearchBox from '../Components/CaseSearch.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeSelect from '@/Components/Select.vue';

export default {
    name: 'CaseNew',
    components: {
        BreezeAuthenticatedLayout, CaseSearchBox, Head, Link, BreezeInput, BreezeSelect, BreezeLabel
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

            this.newForm.post('/vss/cases', {
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

        addNewAreaOfAuditRow: function(){
            this.newAreaOfAuditRows.push({
                'area_of_audit_code': 1,
                'audit_type': 'A'
            });
            this.noChanges = false;
        },
        removeNewAreaOfAuditRow: function(index){
            let newArray = [];
            for(let i=0; i<this.newAreaOfAuditRows.length; i++){
                if(i !== index){
                    newArray.push(this.newAreaOfAuditRows[i]);
                }
            }
            this.newAreaOfAuditRows.length = 0;
            this.newAreaOfAuditRows = [];
            this.newAreaOfAuditRows = newArray;
        },

        addNewOffenceRow: function(){
            this.newOffenceRows.push({
                'nature_code': 'Failure to Report'
            });
            this.noChanges = false;
        },
        removeNewOffenceRow: function(index){
            let newArray = [];
            for(let i=0; i<this.newOffenceRows.length; i++){
                if(i !== index){
                    newArray.push(this.newOffenceRows[i]);
                }
            }
            this.newOffenceRows.length = 0;
            this.newOffenceRows = [];
            this.newOffenceRows = newArray;
        },

        addNewSanctionRow: function(){
            this.newSanctionRows.push({
                'sanction_code': 1
            });
            this.noChanges = false;
        },
        removeNewSanctionRow: function(index){
            let newArray = [];
            for(let i=0; i<this.newSanctionRows.length; i++){
                if(i !== index){
                    newArray.push(this.newSanctionRows[i]);
                }
            }
            this.newSanctionRows.length = 0;
            this.newSanctionRows = [];
            this.newSanctionRows = newArray;
        },

        back: function()
        {
            window.history.back();
        },

        addDefaultLists: function ()
        {
            this.newAreaOfAuditRows = [];
            this.newOffenceRows = [];
            this.newSanctionRows = [];
        }

    },
    watch: {
    },
    computed: {
    },
    mounted() {
        this.addDefaultLists();
    }
}
</script>
