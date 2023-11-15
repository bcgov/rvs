<style scoped>
tr {
    padding-bottom: 7px;
    display: block;
}
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
.spinner {
    border-top: 2px solid rgba(0, 0, 0, 0.2);
    border-right: 2px solid rgba(0, 0, 0, 0.2);
    border-bottom: 2px solid rgba(0, 0, 0, 0.2);
    border-left: 4px solid #198754;
    animation: spin 0.6s linear infinite;
    display: inline-block;
    width: 18px;
    height: 18px;
    border-radius: 50%;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
<template>
    <p v-if="grantForms.length === 0" class="text-center leading-5">No Grants.</p>
    <div v-else>
        <div class="accordion" id="accordionGrant">
            <div v-for="(grant,i) in grantForms" class="accordion-item">
                <h2 class="accordion-header" :id="'heading'+i">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse'+i" :aria-expanded="i===0" :aria-controls="'collapse'+i">
                        Grant #{{ i+1 }}
                    </button>
                </h2>
                <div :id="'collapse'+i" class="accordion-collapse collapse" :class="i===0?'show':''" :aria-labelledby="'heading'+i" data-bs-parent="#accordionGrant">
                    <div class="accordion-body">
                        <form @submit.prevent="updateGrant(i)">
                            <div class="row g-3">


                                <div v-if="grant.school != null" class="col-md-4">
                                    <BreezeLabel :for="'inputInstitution'+i" class="form-label" value="Institution" />
                                    <BreezeInput @focusout="resetFilter(i)" @keyup="filterActiveSchools(i, $event)" type="text" class="form-control" :id="'inputInstitution'+i" v-model="grant.school.name" />
                                    <input type="hidden" v-model="grant.institution_id" />
                                    <ul class="dropdown-menu" :class="grant.schoolsListHidden === false ? 'show' : 'hidden'" data-popper-placement="top-start">
                                        <template v-for="(school,j) in schoolsList"><li @click="assignSchool(school, j, i)" :value="school.institution_id" class="dropdown-item">{{ school.name }}</li></template>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel :for="'inputProgramName'+i" class="form-label" value="Program" />
                                    <BreezeInput type="text" class="form-control" :id="'inputProgramName'+i" v-model="grant.program_name" />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel :for="'inputDateReceived'+i" class="form-label" value="Date Received" />
                                    <BreezeInput type="date" max="2040-12-31" class="form-control" :id="'inputDateReceived'+i" v-model="grant.application_receive_date" />
                                </div>

                                <div :class="grant.program_code !== 'I' ? 'col-md-4' : 'col-md-3'">
                                    <BreezeLabel :for="'inputProgramType'+i" class="form-label" value="Program Type" />
                                    <BreezeSelect class="form-select" :id="'inputProgramType'+i" v-model="grant.program_code">
                                        <option v-for="(pt,j) in program_types" :value="pt.program_code">{{ pt.program_description }}</option>
                                    </BreezeSelect>
                                </div>
                                <div v-if="grant.program_code === 'I'" class="col-md-3">
                                    <BreezeLabel :for="'inputProgramOtherDescription'+i" class="form-label" value="Program Other Desc" />
                                    <BreezeInput type="text" class="form-control" :id="'inputProgramOtherDescription'+i" v-model="grant.program_other_description" />
                                </div>
                                <div :class="grant.program_code !== 'I' ? 'col-md-4' : 'col-md-3'">
                                    <BreezeLabel :for="'inputProgramYear'+i" class="form-label" value="Program Year" />
                                    <BreezeSelect class="form-select" :id="'inputProgramYear'+i" v-model="grant.program_year_id">
                                        <option value=""></option>
                                        <option v-for="(py,j) in program_years" :value="py.program_year_id">{{ py.year_start }}/{{ py.year_end }}</option>
                                    </BreezeSelect>
                                </div>
                                <div :class="grant.program_code !== 'I' ? 'col-md-4' : 'col-md-3'">
                                    <BreezeLabel :for="'inputProgramOfficer'+i" class="form-label" value="Program Officer" />
                                    <BreezeSelect class="form-select" :id="'inputProgramOfficer'+i" v-model="grant.officer_user_id">
                                        <option v-for="(officer,j) in all_staff" :value="officer.user_id">{{ officer.first_name }} {{ officer.last_name }}</option>
                                    </BreezeSelect>
                                </div>

                                <div class="col-md-4">
                                    <BreezeLabel :for="'inputStartDate'+i" class="form-label" value="Study Start Date" />
                                    <BreezeInput type="date" max="2040-12-31" class="form-control" :id="'inputStartDate'+i" v-model="grant.study_start_date" />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel :for="'inputEndDate'+i" class="form-label" value="Study End Date" />
                                    <BreezeInput type="date" max="2040-12-31" class="form-control" :id="'inputEndDate'+i" v-model="grant.study_end_date" />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel :for="'inputAge'+i" class="form-label" value="Age" />
                                    <BreezeInput type="text" class="form-control" :id="'inputAge'+i" v-model="grant.age" />
                                </div>

                                <div class="col-md-4">
                                    <BreezeLabel :for="'inputApplicationNumber'+i" class="form-label" value="Application #" />
                                    <BreezeInput type="text" class="form-control" :id="'inputApplicationNumber'+i" v-model="grant.application_number" />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel :for="'inputApplicationType'+i" class="form-label" value="Application Type" />
                                    <BreezeSelect class="form-select" :id="'inputApplicationType'+i" v-model="grant.application_type">
                                        <option value=""></option>
                                        <option value="SFAS Extract">SFAS Extract</option>
                                        <option value="Paper Application">Paper Application</option>
                                    </BreezeSelect>
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel :for="'inputApplicationStatus'+i" class="form-label" value="Application Status" />
                                    <BreezeSelect @change="cboStatus_BeforeUpdate(i)" class="form-select" :id="'inputApplicationStatus'+i" v-model="grant.status_code">
                                        <option value="A">Approved</option>
                                        <option value="D">Denied</option>
                                        <option value="P">Pending</option>
                                    </BreezeSelect>
                                </div>
                            </div>

                            <div v-if="grant.status_code === 'A'" class="card mt-3">
                                <div class="card-header">Approved Application
                                    <button v-if="grant.total_yeaf_award <= 0 || grant.cheque_batch_number == null" @click="giveAward(i)" type="button" class="btn btn-sm float-end btn-success">Give Award</button>
                                    <button v-if="checkLocked(i)" type="button" class="btn btn-sm float-end btn-danger" @click="unlock(i)">Locked</button>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <BreezeLabel class="form-label" value="YEAF Award" />
                                            <BreezeInput type="text" class="form-control" v-model="grant.total_yeaf_award" :disabled="checkLocked(i)" />
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel class="form-label" value="Processing Batch" />
                                            <BreezeSelect class="form-select" v-model="grant.cheque_batch_number" :disabled="checkLocked(i)">
                                                <option value=""></option>
                                                <template v-for="batch in batches">
                                                    <option :value="batch.batch_number">{{ batch.batch_human_date }} | {{ batch.batch_date }}</option>
                                                </template>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel class="form-label" value="Date Issued" />
                                            <BreezeInput type="date" max="2040-12-31" class="form-control" v-model="grant.cheque_issue_date" :disabled="checkLocked(i)" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <BreezeLabel class="form-label" value="Withdrawal?" />
                                            <div class="form-check form-switch">
                                                <input type="checkbox" role="switch" class="form-check-input" v-model="grant.withdrawal" :disabled="checkLocked(i)" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel class="form-label" value="Withdrawal Date" />
                                            <BreezeInput type="date" max="2040-12-31" class="form-control" v-model="grant.withdrawal_date" :disabled="checkLocked(i) || !grant.withdrawal" />
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel class="form-label" value="&nbsp;" />
                                            <div class="d-grid"><button type="button" class="btn btn-success" :disabled="checkLocked(i) && !grant.withdrawal" @click="exportWithdrawlGrant(i)">Withdrawal Letter</button></div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <BreezeLabel class="form-label" value="Overaward Amount" />
                                            <BreezeInput type="text" class="form-control" v-model="grant.overaward" @change.prevent="overaward(i)" :disabled="checkLocked(i)" />
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel class="form-label" value="Overaward Deducted" />
                                            <BreezeInput type="text" class="form-control" v-model="grant.overaward_deducted_amount" readonly="readonly" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="grant.status_code === 'P'" class="card mt-3">
                                <div class="card-header">Pending Reasons<button @click="newReason(i, 'P')" type="button" class="btn btn-sm float-end btn-success">add row +</button></div>
                                <div class="card-body">
                                    <div class="row mb-3" v-for="(grantPending, j) in grant.grant_pending_ineligibles">
                                        <div class="col-md-10">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Pending Reason" />
                                            <BreezeSelect class="form-select" v-model="grantPending.ineligible_code_id">
                                                <template v-for="ineligible in ineligibles">
                                                    <option v-if="ineligible.code_type === 'P'" :disabled="ineligible.active_flag === false" :value="ineligible.code_id">{{ ineligible.description }}</option>
                                                </template>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Clear?" />
                                            <div class="form-check form-switch">
                                                <input type="checkbox" role="switch" class="form-check-input" v-model="grantPending.cleared_flag" />
                                            </div>
                                        </div>
                                    </div><!-- end existing rows -->

                                    <div class="row mb-3" v-for="(grantPending, j) in grant.new_pending_reasons">
                                        <div class="col-md-10">
                                            <BreezeSelect class="form-select" v-model="grantPending.ineligible_code_id">
                                                <template v-for="ineligible in ineligibles">
                                                    <option v-if="ineligible.code_type === 'P'" :disabled="ineligible.active_flag === false" :value="ineligible.code_id">{{ ineligible.description }}</option>
                                                </template>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" role="switch" class="form-check-input" v-model="grantPending.cleared_flag" />
                                            </div>
                                        </div>
                                    </div><!-- end new rows -->

                                    <div class="row">
                                        <div class="col-12">
                                            <BreezeLabel class="form-label" value="Custom Pending Reason" />
                                            <textarea class="form-control" v-model="grant.custom_pending_reason"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="grant.status_code === 'D' || grant.grant_denied_ineligibles.length > 0" class="card mt-3">
                                <div class="card-header">Deny Reasons<button @click="newReason(i, 'D')" type="button" class="btn btn-sm float-end btn-success">add row +</button></div>
                                <div class="card-body">
                                    <div class="row mb-3" v-for="(grantDenied, j) in grant.grant_denied_ineligibles">
                                        <div class="col-md-10">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Deny Reason" />
                                            <BreezeSelect class="form-select" v-model="grantDenied.ineligible_code_id">
                                                <template v-for="ineligible in ineligibles">
                                                    <option v-if="ineligible.code_type === 'D'" :disabled="ineligible.active_flag === false" :value="ineligible.code_id">{{ ineligible.description }}</option>
                                                </template>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Clear?" />
                                            <div class="form-check form-switch">
                                                <input type="checkbox" role="switch" class="form-check-input" v-model="grantDenied.cleared_flag" />
                                            </div>
                                        </div>
                                    </div><!-- end existing rows -->

                                    <div class="row mb-3" v-for="(grantDenied, j) in grant.new_denial_reasons">
                                        <div class="col-md-10">
                                            <BreezeSelect class="form-select" v-model="grantDenied.ineligible_code_id">
                                                <template v-for="ineligible in ineligibles">
                                                    <option v-if="ineligible.code_type === 'D'" :disabled="ineligible.active_flag === false" :value="ineligible.code_id">{{ ineligible.description }}</option>
                                                </template>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" role="switch" class="form-check-input" v-model="grantDenied.cleared_flag" />
                                            </div>
                                        </div>
                                    </div><!-- end new rows -->

                                    <div class="row">
                                        <div class="col-12">
                                            <BreezeLabel class="form-label" value="Custom Denial Reason" />
                                            <textarea class="form-control" v-model="grant.custom_denial_reason"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- start appeals section -->
                            <div v-if="grantForms != null" class="card mt-3">
                                <div class="card-header">Appeals<button @click="newAppeal(i)" type="button" class="btn btn-sm float-end btn-success">add row +</button></div>
                                <div class="card-body">
                                    <div class="row mb-3" v-for="(appeal, j) in grant.appeals">
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Appeal Date" />
                                            <BreezeInput type="date" max="2040-12-31" class="form-control" v-model="appeal.appeal_date" />
                                        </div>
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Type" />
                                            <BreezeSelect class="form-select" v-model="appeal.appeal_code">
                                                <option value="OA">Over age of 24</option>
                                                <option value="OT">Other</option>
                                                <option value="UA">Under age of 19</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Adjudicated By" />
                                            <BreezeInput class="form-control" v-model="appeal.adjudicated_by_user_id" />
                                        </div>
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Status" />
                                            <BreezeSelect class="form-select" v-model="appeal.status_code">
                                                <option value="A">Approved</option>
                                                <option value="D">Denied</option>
                                                <option value="P">Pending</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Status Date" />
                                            <BreezeInput type="date" max="2040-12-31" class="form-control" v-model="appeal.status_affective_date" />
                                        </div>
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Reason (Other)" />
                                            <BreezeInput class="form-control" v-model="appeal.other_appeal_explain" />
                                        </div>

                                    </div><!-- end existing rows -->

                                    <div class="row mb-3" v-for="(appeal, j) in grant.new_appeals">
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Appeal Date" />
                                            <BreezeInput type="date" max="2040-12-31" class="form-control" v-model="appeal.appeal_date" />
                                        </div>
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Type" />
                                            <BreezeSelect class="form-select" v-model="appeal.appeal_code">
                                                <option value="OA">Over age of 24</option>
                                                <option value="OT">Other</option>
                                                <option value="UA">Under age of 19</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Adjudicated By" />
                                            <BreezeInput class="form-control" v-model="appeal.adjudicated_by_user_id" />
                                        </div>
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Status" />
                                            <BreezeSelect class="form-select" v-model="appeal.status_code">
                                                <option value="A">Approved</option>
                                                <option value="D">Denied</option>
                                                <option value="P">Pending</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Status Date" />
                                            <BreezeInput type="date" max="2040-12-31" class="form-control" v-model="appeal.status_affective_date" />
                                        </div>
                                        <div class="col-md-2">
                                            <BreezeLabel v-if="j===0" class="form-label" value="Reason (Other)" />
                                            <BreezeInput class="form-control" v-model="appeal.other_appeal_explain" />
                                        </div>
                                    </div><!-- end new rows -->

                                </div>
                            </div>

                            <div v-if="grant.msg != undefined" class="row">
                                <div class="col-12">
                                    <div class="alert alert-danger mt-3">
                                        <ul>
                                            <li v-if="typeof (grant.msg) === 'string'" v-html="msg"></li>
                                            <template v-else>
                                                <li v-for="msg in grant.msg" v-html="msg"></li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer mt-3">
                                <button @click="evaluateGrant(i)" type="button" class="btn me-2 btn-outline-success" :disabled="grantForms[i].formSubmitting">
                                    <span v-if="!grantForms[i].formSubmitting">Save &amp; Evaluate Grant</span>
                                    <span v-else>
                                        <i class="spinner"></i> Submitting...
                                    </span>
                                </button>
                                <button v-if="result.grants[i].status_code != null" @click="exportGrant(i)" type="button" class="btn me-2 btn-outline-primary float-end">Export Letter</button>
                            </div>

                            <FormSubmitAlert :form-state="grant.formState"
                                             :success-msg="'Grant record was updated successfully.'"></FormSubmitAlert>
                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div>

</template>
<script>
import axios from 'axios';
import {Link, useForm} from '@inertiajs/vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'StudentEditGrantTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect, FormSubmitAlert
    },
    props: {
        result: Object,
        now: String,
        countries: Object,
        provinces: Object,

        program_types: Object,
        program_years: Object,
        schools: Object,
        batches: Object,
        ineligibles: Object,
        active_staff: Object,
        all_staff: Object,
    },
    data() {
        return {
            noChanges: true,
            grantForms: [],
            schoolsList: [],

        }
    },
    methods: {
        resetFilter: function (index){
            //if schoolsListHidden is true then the schools list is still shown
            //and the input field lost focus because the user clicked something
            //other than the list ot assignSchool
            let vm = this;
            setTimeout(function (){
                if(vm.grantForms[index].schoolsListHidden === false){
                        vm.grantForms[index].school = JSON.parse(JSON.stringify(vm.result.grants[index].school));
                        vm.grantForms[index].schoolsListHidden = true;
                        vm.schoolsList = vm.schools;
                }
            }, 300);
        },
        assignSchool: function (school, j, index) {
            this.grantForms[index].institution_id = school.institution_id;
            this.grantForms[index].school = school;
            this.grantForms[index].schoolsListHidden = true;
            this.schoolsList = this.schools;
            // document.getElementById('inputInstitution'+i).value = school.name;
        },
        filterActiveSchools: function (index, e) {
            this.grantForms[index].schoolsListHidden = false;
            let search = e.target.value.toLowerCase();
            if(search.length > 2){
                this.schoolsList = this.schools.filter(obj => {
                    if(obj.name == null)
                        return false;
                    return obj.name.toLowerCase().indexOf(search) >= 0;
                } );
            }
        },

        exportWithdrawlGrant: function (index)
        {
            let vm = this;
            axios({
                url: '/yeaf/grants/validate-letter/' + this.grantForms[index].id,
                method: 'get',
                headers: {'Accept': 'application/json'}
            })
                .then(function (response) {
                    if(response.data.msg === ''){
                        window.location.href = '/yeaf/grants/export-withdrawal-letter/' + vm.grantForms[index].id + '/rptLtrWithdrawal';
                    }else{
                        vm.grantForms[index].msg = response.data.msg;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        exportGrant: function (index)
        {
            let vm = this;
            axios({
                url: '/yeaf/grants/validate-letter/' + this.grantForms[index].id,
                method: 'get',
                headers: {'Accept': 'application/json'}
            })
                .then(function (response) {
                    if(response.data.msg === ''){
                        window.location.href = '/yeaf/grants/export-letter/' + vm.grantForms[index].id + '/' + response.data.docName;
                    }else{
                        vm.grantForms[index].msg = response.data.msg;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        overaward: function (index)
        {
            if(this.grantForms[index].overaward > this.grantForms[index].total_yeaf_award){
                alert("The Overaward amount cannot be larger than the amount awarded");
                this.grantForms[index].overaward = this.result.grants[index].overaward;
            }

            this.grantForms[index].total_yeaf_award -= this.grantForms[index].overaward;
            this.grantForms[index].overaward_deducted_amount = this.grantForms[index].overaward;
        },
        unlock: function (index)
        {
            let check = confirm('Are you sure you want to unlock this grant? This will reset the YEAF Award fied to zero.');
            if(check){
                this.grantForms[index].total_yeaf_award = 0;
                this.grantForms[index].forceUnlock = true;
            }
        },
        giveAward: function (index)
        {
            for(let i = 0; i<this.program_years.length; i++){
                if(this.program_years[i].program_year_id == this.grantForms[index].program_year_id){
                    this.grantForms[index].total_yeaf_award = this.program_years[i].grant_amount;
                    break;
                }
            }
        },
        checkLocked: function (index)
        {
            if(this.grantForms[index].forceUnlock === true)
                return false;
            return ((this.result.grants[index].total_yeaf_award > 0 && this.result.grants[index].cheque_batch_number != null) && this.grantForms[index].total_yeaf_award > 0);
        },
        //type is P|D
        newReason: function (index, type)
        {
            let new_record = type === 'P' ? this.grantForms[index].new_pending_reasons : this.grantForms[index].new_denial_reasons;
            //if we have already added one row. make sure you don't add another if there is a blank entry/row
            if(new_record !== undefined){
                let found_blank = false;
                for(let i=0; i<new_record.length; i++){
                    if(new_record[i].ineligible_code_id === 0){
                        found_blank = true;
                        break;
                    }
                }

                if(found_blank){
                    return false;
                }
                //if we have not added any new rows/entries. Initialize a new array
            }else{
                type === 'P' ? this.grantForms[index].new_pending_reasons = [] : this.grantForms[index].new_denial_reasons = [];
            }
            type === 'P'
                ? this.grantForms[index].new_pending_reasons.push({
                ineligible_code_id: 0, ineligible_code_type: type, cleared_flag: false
            })
                : this.grantForms[index].new_denial_reasons.push({
                ineligible_code_id: 0, ineligible_code_type: type, cleared_flag: false
            });

        },
        newAppeal: function (index)
        {
            let new_record = this.grantForms[index].new_appeals;
            //if we have already added one row. make sure you don't add another if there is a blank entry/row
            if(new_record !== undefined){
                let found_blank = false;
                for(let i=0; i<new_record.length; i++){
                    if(new_record[i].appeal_code === 0){
                        found_blank = true;
                        break;
                    }
                }

                if(found_blank){
                    return false;
                }
                //if we have not added any new rows/entries. Initialize a new array
            }else{
                this.grantForms[index].new_appeals = [];
            }

            this.grantForms[index].new_appeals.push({
                appeal_code: 0, adjudicated_by_user_id: '', appeal_date: '', appeal_status: 'A', status_affective_date: '', other_appeal_explain: ''
            });

        },
        cboStatus_BeforeUpdate: function (index)
        {
            let cancel = false;
            let grant = this.grantForms[index];
            grant.evaluationValid = true;
            if(grant.institution_id == null || grant.study_end_date == null || grant.study_start_date == null || grant.program_code == null || grant.program_year_id == null)
            {
                alert("You cannot change the status to Approved until all required fields are filled in.");
                cancel = true;
            }

            if(this.result.grants[index].total_yeaf_award > 0 && this.result.grants[index].status_code === 'A')
            {
                alert("You cannot change the status from Approved when an award has been given.");
                cancel = true;
            }

            if(cancel){
                this.grantForms[index].status_code = this.result.grants[index].status_code;
                grant.evaluationValid = false;
                return false;
            }
        },
        updateGrant: function (index)
        {
            this.grantForms[index].formState = '';
            this.grantForms[index].put('/yeaf/grants/' + this.grantForms[index].id, {
                onSuccess: () => {
                    this.grantForms[index].formState = true;
                    this.noChanges = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.grantForms[index].formState = false;
                },
                preserveState: false,

            });
        },

        evaluateGrant: function(index){
            let grant = this.grantForms[index];
            grant.evaluationValid = true;

            if(grant.application_receive_date == null || grant.program_year_id === ''){
                alert("You must fill in at least the program year and the date received before evaluating the application.");
                grant.evaluationValid = false;
            }else{
                if(this.result.grants[index].status_code === 'A' && this.result.grants[index].total_yeaf_award > 0){
                    let check = confirm("Once an award has been made, you cannot 'evaluate' an application. Are you sure you want to proceed?");
                    if(!check)
                        grant.evaluationValid = false;
                }
                if(grant.program_year_id === ''){
                    alert("You must select a program year.");
                    grant.evaluationValid = false;
                }
                else if(grant.evaluationValid === true){
                    let vm = this;
                    vm.grantForms[index].formSubmitting = true;
                    vm.grantForms[index].formState = '';

                    let formData = new FormData();
                    formData.append('frm', JSON.stringify(this.grantForms[index]));
                    axios({
                        url: '/yeaf/grants/evaluate/' + this.grantForms[index].id,
                        data: formData,
                        method: 'post',
                        headers: {'Accept': 'application/json', 'Content-Type': 'multipart/form-data'}
                    })
                        .then(function (response) {
                            vm.grantForms[index] = response.data.grant;
                            vm.grantForms[index].formSubmitting = false;
                            if(response.data.msg !== '' && response.data.msg.length > 0){
                                vm.grantForms[index].msg = response.data.msg;
                                vm.grantForms[index].formState = false;
                            }else{
                                vm.grantForms[index].formState = true;
                                setTimeout(function (){
                                    window.location.href = '/yeaf/students/' + vm.result.id;
                                }, 3000);
                            }
                        })
                        .catch(function (error) {
                            vm.grantForms[index].formSubmitting = false;
                            console.log(error);
                        });
                }
            }
        }

    },
    mounted() {
        this.grantForms = JSON.parse(JSON.stringify(this.result.grants));
        this.schoolsList = this.schools;
    }
}

</script>
