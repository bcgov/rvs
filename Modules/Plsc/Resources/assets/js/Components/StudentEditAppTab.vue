<template>
    <form v-if="editForm != null" @submit.prevent="updateApplication">
        <div class="row g-3">
            <div class="col-md-4">
                <BreezeLabel for="inputReceiveDate" class="form-label" value="Receive Date" />
                <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputReceiveDate" v-model="editForm.receive_date" />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputSsd" class="form-label" value="Start Date" />
                <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputSsd" v-model="editForm.ssd" />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputSed" class="form-label" value="End Date" />
                <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputSed" v-model="editForm.sed" />
            </div>

            <div class="col-md-3">
                <BreezeLabel for="inputInstitution" class="form-label" value="Institution" />
                <BreezeInput @focusout="resetFilter" @keyup="filterActiveSchools($event)" type="text" class="form-control" id="newInstitution" v-model="editForm.school.name" />
                <ul class="dropdown-menu" :class="editForm.schoolsListHidden === false ? 'show' : 'hidden'" data-popper-placement="top-start" style="
    position: absolute;
    right: 0;
    overflow-y: scroll;
    height: 400px;
">
                    <template v-for="(school,j) in schoolsList"><li @click="assignSchool(school)" :value="school.id" class="dropdown-item">{{ school.name }}</li></template>
                </ul>

            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputProgram" class="form-label" value="Program of Study" />
                <BreezeInput type="text" class="form-control" id="inputProgram" v-model="editForm.program_of_study"/>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputCredential" class="form-label" value="Credential" />
                <BreezeInput type="text" class="form-control" id="inputCredential" v-model="editForm.credential"/>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputApplicationYear" class="form-label" value="Application Year" />
                <BreezeInput type="number" class="form-control" placeholder="i.e 2024" id="inputApplicationYear" v-model="editForm.application_year"/>
            </div>

            <hr/>

            <div class="col-md-3">
                <BreezeLabel for="inputParentLastName" class="form-label" value="Parent Last Name" />
                <BreezeInput type="text" class="form-control" id="inputParentLastName" v-model="editForm.parent_last_name"/>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputParentFirstName" class="form-label" value="Parent First Name" />
                <BreezeInput type="text" class="form-control" id="inputParentFirstName" v-model="editForm.parent_first_name"/>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputParentEmail" class="form-label" value="Parent Email" />
                <BreezeInput type="text" class="form-control" id="inputParentEmail" v-model="editForm.parent_email"/>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputParentPhone" class="form-label" value="Parent Phone" />
                <BreezeInput type="text" class="form-control" id="inputParentPhone" v-model="editForm.parent_phone_number"/>
            </div>

            <div class="col-12">
                <BreezeLabel for="inputParentAddress" class="form-label" value="Parent Address" />
                <BreezeInput type="text" class="form-control" id="inputParentAddress" v-model="editForm.parent_address"/>
            </div>

            <div class="col-md-4">
                <BreezeLabel for="inputParentCity" class="form-label" value="Parent City" />
                <BreezeInput type="text" class="form-control" id="inputParentCity" v-model="editForm.parent_city"/>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputParentProvince" class="form-label" value="Parent Province" />
                <BreezeInput type="text" class="form-control" id="inputParentProvince" v-model="editForm.parent_province"/>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputParentPostalCode" class="form-label" value="Parent Postal Code" />
                <BreezeInput type="text" class="form-control" id="inputParentPostalCode" v-model="editForm.parent_postal_code"/>
            </div>

            <div class="col-md-3">
                <BreezeLabel for="inputParentEmployeeId" class="form-label" value="Parent Employee ID" />
                <BreezeInput type="text" class="form-control" id="inputParentEmployeeId" v-model="editForm.parent_employee_id"/>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputParentDeptId" class="form-label" value="Parent Dept. ID" />
                <BreezeInput type="text" class="form-control" id="inputParentDeptId" v-model="editForm.parent_department_id"/>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputParentMinistry" class="form-label" value="Parent Ministry" />
                <BreezeInput type="text" class="form-control" id="inputParentMinistry" v-model="editForm.parent_ministry"/>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputParentBranch" class="form-label" value="Parent Branch" />
                <BreezeInput type="text" class="form-control" id="inputParentBranch" v-model="editForm.parent_branch"/>
            </div>

            <div class="col-md-4">
                <BreezeLabel for="inputParentJobTitle" class="form-label" value="Parent Job Title" />
                <BreezeInput type="text" class="form-control" id="inputParentJobTitle" v-model="editForm.parent_job_title"/>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputParent3YearInGov" class="form-label" value="Parent 3Y in Gov?" />
                <BreezeSelect class="form-select" id="inputParent3YearInGov" v-model="editForm.three_years_in_gov">
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </BreezeSelect>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputParentOtherOrg" class="form-label" value="Parent Other Org" />
                <BreezeInput type="text" class="form-control" id="inputParentOtherOrg" v-model="editForm.other_org"/>
            </div>

            <hr/>

            <div class="col-md-6">
                <BreezeLabel for="inputHighSchoolTranscript" class="form-label" value="High School Transcript?" />
                <BreezeSelect class="form-select" id="inputHighSchoolTranscript" v-model="editForm.high_school_transcript">
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </BreezeSelect>
            </div>
            <div class="col-md-6">
                <BreezeLabel for="inputHighSchoolAverage" class="form-label" value="High School Average" />
                <BreezeInput type="number" class="form-control" id="inputHighSchoolAverage" v-model="editForm.high_school_average"/>
            </div>

            <div class="col-md-6">
                <BreezeLabel for="inputPostSecondaryTranscript" class="form-label" value="Post Secondary Transcript?" />
                <BreezeSelect class="form-select" id="inputPostSecondaryTranscript" v-model="editForm.post_secondary_transcript">
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </BreezeSelect>
            </div>
            <div class="col-md-6">
                <BreezeLabel for="inputPostSecondaryAverage" class="form-label" value="Post Secondary Average" />
                <BreezeInput type="number" class="form-control" id="inputPostSecondaryAverage" v-model="editForm.post_secondary_average"/>
            </div>

            <div class="col-md-3">
                <BreezeLabel for="inputEnrollmentConfirmed" class="form-label" value="Enrollment Confirmed?" />
                <BreezeSelect class="form-select" id="inputEnrollmentConfirmed" v-model="editForm.enrollment_confirmed">
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </BreezeSelect>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="input750" class="form-label" value="750 Word Essay?" />
                <BreezeSelect class="form-select" id="input750" v-model="editForm.seven_fifty_word_essay">
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </BreezeSelect>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputReferenceLetter" class="form-label" value="Reference Letter?" />
                <BreezeSelect class="form-select" id="inputReferenceLetter" v-model="editForm.student_reference_letter">
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </BreezeSelect>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputCommunicationSkills" class="form-label" value="Communication Skills?" />
                <BreezeSelect class="form-select" id="inputCommunicationSkills" v-model="editForm.communication_skills">
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </BreezeSelect>
            </div>

            <hr/>

            <div class="col-md-3">
                <BreezeLabel for="inputForwardToCommittee" class="form-label" value="Forward to Committee?" />
                <BreezeSelect class="form-select" id="inputForwardToCommittee" v-model="editForm.forward_to_committee">
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </BreezeSelect>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputTotalScore" class="form-label" value="Total Score" />
                <BreezeInput type="number" class="form-control" id="inputTotalScore" v-model="editForm.total_score"/>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputRank" class="form-label" value="Rank" />
                <BreezeInput type="number" class="form-control" id="inputRank" v-model="editForm.rank"/>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputStatusCode" class="form-label" value="Status Code" />
                <BreezeSelect class="form-select" id="inputStatusCode" v-model="editForm.status_code">
                    <option></option>
                    <option value="Approved">Approved</option>
                    <option value="Denied">Denied</option>
                    <option value="Other">Other</option>
                </BreezeSelect>
            </div>


            <div class="col-12">
                <BreezeLabel for="inputComment" class="form-label" value="Comments" />
                <textarea rows="3" class="form-control" id="inputComment" v-model="editForm.comment" />
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
        <div class="card-footer mt-3">
            <button v-if="result==null" type="submit" class="btn me-2 btn-outline-success" :disabled="editForm.processing">Create Application</button>
            <button v-else type="submit" class="btn me-2 btn-outline-success" :disabled="editForm.processing">Update Application</button>
        </div>

        <FormSubmitAlert :form-state="editForm.formState"
                         :success-msg="'Application record was submitted successfully.'"></FormSubmitAlert>

    </form>

</template>
<script>
import {Link, useForm} from '@inertiajs/vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'StudentEditAppTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect, FormSubmitAlert
    },
    props: {
        result: Object,
        provinces: Object,
        countries: Object,
        schools: Object,
    },
    data() {
        return {
            noChanges: true,
            denial_reasons: null,
            editForm: useForm({
                formState: true,
                formSuccessMsg: 'Form was submitted successfully.',
                formFailMsg: 'There was an error submitting this form.',
                school: {name:''},
                schoolsListHidden: true,

            }),
            schoolsList: ''
        }
    },
    methods: {
        resetFilter: function (){
            //if schoolsListHidden is true then the schools list is still shown
            //and the input field lost focus because the user clicked something
            //other than the list ot assignSchool
            let vm = this;
            setTimeout(function (){
                if(vm.editForm.schoolsListHidden === false){
                    vm.editForm.school = {name:''};
                    vm.editForm.schoolsListHidden = true;
                    vm.schoolsList = vm.schools;
                }
            }, 300);
        },
        assignSchool: function (school) {
            this.editForm.institution_id = school.id;
            this.editForm.school = school;
            this.editForm.schoolsListHidden = true;
            this.schoolsList = this.schools;
        },
        filterActiveSchools: function (e) {
            this.editForm.schoolsListHidden = false;
            let search = e.target.value.toLowerCase();
            if(search.length > 2){
                this.schoolsList = this.schools.filter(obj => {
                    if(obj.name == null)
                        return false;
                    return obj.name.toLowerCase().indexOf(search) >= 0;
                } );
            }
        },
        updateApplication: function ()
        {
            this.editForm.formState = null;
                this.editForm.id = this.result.id;
                this.editForm.put('/plsc/applications/' + this.result.id, {
                    onSuccess: () => {
                        this.editForm.formState = true;
                        this.noChanges = true;
                    },
                    onError: () => {
                        this.editForm.formState = false;
                    },
                });

        },

    },
    mounted() {
        this.editForm = useForm(this.result);
        this.editForm.school = {name:''};
        this.editForm.schoolsListHidden = true;
        this.editForm.formState = true;
        this.editForm.formSuccessMsg = 'Form was submitted successfully.';
        this.editForm.formFailMsg = 'There was an error submitting this form.';

        if(this.result.institution_id !== null){
            const selectedSchool = this.schools.find(school => school.id === this.result.institution_id);
            if (selectedSchool) {
                this.editForm.school = selectedSchool;
            }
        }
    }
}

</script>
