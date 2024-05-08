<style scoped>
.st0{fill-rule:evenodd;clip-rule:evenodd;}
</style>
<template>
        <Head title="PLSCPS - Edit Student" />

        <BreezeAuthenticatedLayout v-bind="$attrs">

            <div class="mt-3">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mt-3">
                            <div class="card mb-2">
                                <div class="card-header">
                                    <Link @click="back" href="#" class="btn btn-link">

                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 200" width="25">
                                            <g>
                                                <path class="st0" d="M102.5,3.3c4.3,4.3,4.3,9,0,12.6l0,0L26.6,77.6L102.5,153.6c4.3,4.3,4.3,9,0,12.6s-9,4.3-12.6,0L11,89.2   c-4.3-4.3-4.3-9,0-12.6l0,0L89.9,3.3C93.5-1.4,98.2-1.4,102.5,3.3L102.5,3.3z"></path>
                                                <path class="st0" d="M22.3,77.6c0-6.1,5-11.1,11.1-11.1h144.7c30.8,0,55.9,25.1,55.9,55.9v122.9c0,6.1-5,11.1-11.1,11.1   s-11.1-5-11.1-11.1V122.4c0-18.4-15-33.4-33.4-33.4H33.4C27.3,89.2,22.3,83.3,22.3,77.6z"></path>
                                            </g>
                                        </svg>

                                    </Link> Student Info
                                </div>
                                <div v-if="editForm != null" class="card-body">
                                    <StudentEditStudentTab :result="editForm"></StudentEditStudentTab>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-header">
                                    Applications
                                    <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newAppModal">New Application</button>

                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li v-for="(app, i) in result.applications" class="list-group-item d-flex justify-content-between align-items-start">
                                            <button @click="showApp(i)" type="button" class="btn btn-link">
                                                <span v-if="app.receive_date != null">{{ app.receive_date }}</span>
                                                <span v-else>Missing Received Date</span>
                                            </button>
                                            <span v-if="app.status_code === 'DONE'" class="badge rounded-pill text-bg-success">{{app.status_code}}</span>
                                            <span v-else-if="app.status_code === 'INTF'" class="badge rounded-pill text-bg-info">{{app.status_code}}</span>
                                            <span v-else-if="app.status_code === 'DCLN'" class="badge rounded-pill text-bg-danger">{{app.status_code}}</span>
                                            <span v-else class="badge rounded-pill text-bg-primary">{{app.status_code}}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div v-if="activeApp != null" class="col-md-8 mt-3 mb-5">
                            <div class="card">
                                <div v-if="editForm != null" class="card-header">
                                    Edit Application
                                </div>
                                <div class="card-body">

                                    <div class="tab-content" id="myStudentTabContent">
                                        <div class="tab-pane fade active show" id="plsc-app-tab-pane" role="tabpanel" aria-labelledby="plsc-app-tab" tabindex="1">
                                            <StudentEditAppTab :countries="countries" :provinces="provinces" :schools="schools" :result="activeApp"></StudentEditAppTab>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal modal-lg fade" id="newAppModal" tabindex="-1" aria-labelledby="newAppModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newAppModalLabel">New Application</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form @submit.prevent="newApplication">
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-3">
                                            <BreezeLabel for="inputReceiveDate" class="form-label" value="Receive Date" />
                                            <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputReceiveDate" v-model="newAppForm.receive_date" />
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputSsd" class="form-label" value="Start Date" />
                                            <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputSsd" v-model="newAppForm.ssd" />
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputSed" class="form-label" value="End Date" />
                                            <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputSed" v-model="newAppForm.sed" />
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputSfasDate" class="form-label" value="SFAS Entry Date" />
                                            <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputSfasDate" v-model="newAppForm.sfas_date" />
                                        </div>

                                        <div class="col-md-3">
                                            <BreezeLabel for="inputInstitution" class="form-label" value="Institution" />
<!--                                            <BreezeInput type="text" class="form-control" id="inputInstitution" v-model="newAppForm.institution_id"/>-->
                                            <BreezeInput @focusout="resetFilter" @keyup="filterActiveSchools($event)" type="text" class="form-control" id="newInstitution" v-model="newAppForm.school.name" />
                                            <ul class="dropdown-menu" :class="newAppForm.schoolsListHidden === false ? 'show' : 'hidden'" data-popper-placement="top-start" style="
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
                                            <BreezeInput type="text" class="form-control" id="inputProgram" v-model="newAppForm.program_of_study"/>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputCredential" class="form-label" value="Credential" />
                                            <BreezeInput type="text" class="form-control" id="inputCredential" v-model="newAppForm.credential"/>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputApplicationYear" class="form-label" value="Application Year" />
                                            <BreezeInput type="number" class="form-control" placeholder="i.e 2024" id="inputApplicationYear" v-model="newAppForm.application_year"/>
                                        </div>

                                        <hr/>

                                        <div class="col-md-3">
                                            <BreezeLabel for="inputParentLastName" class="form-label" value="Parent Last Name" />
                                            <BreezeInput type="text" class="form-control" id="inputParentLastName" v-model="newAppForm.parent_last_name"/>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputParentFirstName" class="form-label" value="Parent First Name" />
                                            <BreezeInput type="text" class="form-control" id="inputParentFirstName" v-model="newAppForm.parent_first_name"/>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputParentEmail" class="form-label" value="Parent Email" />
                                            <BreezeInput type="text" class="form-control" id="inputParentEmail" v-model="newAppForm.parent_email"/>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputParentPhone" class="form-label" value="Parent Phone" />
                                            <BreezeInput type="text" class="form-control" id="inputParentPhone" v-model="newAppForm.parent_phone_number"/>
                                        </div>

                                        <div class="col-12">
                                            <BreezeLabel for="inputParentAddress" class="form-label" value="Parent Address" />
                                            <BreezeInput type="text" class="form-control" id="inputParentAddress" v-model="newAppForm.parent_address"/>
                                        </div>

                                        <div class="col-md-4">
                                            <BreezeLabel for="inputParentCity" class="form-label" value="Parent City" />
                                            <BreezeInput type="text" class="form-control" id="inputParentCity" v-model="newAppForm.parent_city"/>
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="inputParentProvince" class="form-label" value="Parent Province" />
                                            <BreezeInput type="text" class="form-control" id="inputParentProvince" v-model="newAppForm.parent_province"/>
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="inputParentPostalCode" class="form-label" value="Parent Postal Code" />
                                            <BreezeInput type="text" class="form-control" id="inputParentPostalCode" v-model="newAppForm.parent_postal_code"/>
                                        </div>

                                        <div class="col-md-3">
                                            <BreezeLabel for="inputParentEmployeeId" class="form-label" value="Parent Employee ID" />
                                            <BreezeInput type="text" class="form-control" id="inputParentEmployeeId" v-model="newAppForm.parent_employee_id"/>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputParentDeptId" class="form-label" value="Parent Dept. ID" />
                                            <BreezeInput type="text" class="form-control" id="inputParentDeptId" v-model="newAppForm.parent_department_id"/>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputParentMinistry" class="form-label" value="Parent Ministry" />
                                            <BreezeInput type="text" class="form-control" id="inputParentMinistry" v-model="newAppForm.parent_ministry"/>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputParentBranch" class="form-label" value="Parent Branch" />
                                            <BreezeInput type="text" class="form-control" id="inputParentBranch" v-model="newAppForm.parent_branch"/>
                                        </div>

                                        <div class="col-md-4">
                                            <BreezeLabel for="inputParentJobTitle" class="form-label" value="Parent Job Title" />
                                            <BreezeInput type="text" class="form-control" id="inputParentJobTitle" v-model="newAppForm.parent_job_title"/>
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="inputParent3YearInGov" class="form-label" value="Parent 3Y in Gov?" />
                                            <BreezeSelect class="form-select" id="inputParent3YearInGov" v-model="newAppForm.three_years_in_gov">
                                                <option value="true">Yes</option>
                                                <option value="false">No</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="inputParentOtherOrg" class="form-label" value="Parent Other Org" />
                                            <BreezeInput type="text" class="form-control" id="inputParentOtherOrg" v-model="newAppForm.other_org"/>
                                        </div>

                                        <hr/>

                                        <div class="col-md-6">
                                            <BreezeLabel for="inputHighSchoolTranscript" class="form-label" value="High School Transcript?" />
                                            <BreezeSelect class="form-select" id="inputHighSchoolTranscript" v-model="newAppForm.high_school_transcript">
                                                <option value="true">Yes</option>
                                                <option value="false">No</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-6">
                                            <BreezeLabel for="inputHighSchoolAverage" class="form-label" value="High School Average" />
                                            <BreezeInput type="number" class="form-control" id="inputHighSchoolAverage" v-model="newAppForm.high_school_average"/>
                                        </div>

                                        <div class="col-md-6">
                                            <BreezeLabel for="inputPostSecondaryTranscript" class="form-label" value="Post Secondary Transcript?" />
                                            <BreezeSelect class="form-select" id="inputPostSecondaryTranscript" v-model="newAppForm.post_secondary_transcript">
                                                <option value="true">Yes</option>
                                                <option value="false">No</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-6">
                                            <BreezeLabel for="inputPostSecondaryAverage" class="form-label" value="Post Secondary Average" />
                                            <BreezeInput type="number" class="form-control" id="inputPostSecondaryAverage" v-model="newAppForm.post_secondary_average"/>
                                        </div>

                                        <div class="col-md-3">
                                            <BreezeLabel for="inputEnrollmentConfirmed" class="form-label" value="Enrollment Confirmed?" />
                                            <BreezeSelect class="form-select" id="inputEnrollmentConfirmed" v-model="newAppForm.enrollment_confirmed">
                                                <option value="true">Yes</option>
                                                <option value="false">No</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="input750" class="form-label" value="750 Word Essay?" />
                                            <BreezeSelect class="form-select" id="input750" v-model="newAppForm.word_essay">
                                                <option value="true">Yes</option>
                                                <option value="false">No</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputReferenceLetter" class="form-label" value="Reference Letter?" />
                                            <BreezeSelect class="form-select" id="inputReferenceLetter" v-model="newAppForm.student_reference_letter">
                                                <option value="true">Yes</option>
                                                <option value="false">No</option>
                                            </BreezeSelect>
                                        </div>

                                        <div class="col-md-3">
                                            <BreezeLabel for="inputCommunicationSkills" class="form-label" value="Communication Skills?" />
                                            <BreezeSelect class="form-select" id="inputCommunicationSkills" v-model="newAppForm.communication_skills">
                                                <option value="true">Yes</option>
                                                <option value="false">No</option>
                                            </BreezeSelect>
                                        </div>

                                        <hr/>


                                        <div class="col-md-3">
                                            <BreezeLabel for="inputForwardToCommittee" class="form-label" value="Forward to Committee?" />
                                            <BreezeSelect class="form-select" id="inputForwardToCommittee" v-model="newAppForm.forward_to_committee">
                                                <option value="true">Yes</option>
                                                <option value="false">No</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputTotalScore" class="form-label" value="Total Score" />
                                            <BreezeInput type="number" class="form-control" id="inputTotalScore" v-model="newAppForm.total_score"/>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputRank" class="form-label" value="Rank" />
                                            <BreezeInput type="number" class="form-control" id="inputRank" v-model="newAppForm.rank"/>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputStatusCode" class="form-label" value="Status Code" />
                                            <BreezeSelect class="form-select" id="inputStatusCode" v-model="newAppForm.status_code">
                                                <option value="DCLN">DCLN</option>
                                                <option value="DONE">DONE</option>
                                                <option value="INTF">INTF</option>
                                            </BreezeSelect>
                                        </div>

                                        <div class="col-12">
                                            <BreezeLabel for="inputComment" class="form-label" value="Comments" />
                                            <textarea rows="3" class="form-control" id="inputComment" v-model="newAppForm.comment" />
                                        </div>

                                    </div>

                                    <div v-if="newAppForm.errors != undefined" class="row">
                                        <div class="col-12">
                                            <div v-if="newAppForm.hasErrors == true" class="alert alert-danger mt-3">
                                                <ul>
                                                    <li v-for="err in newAppForm.errors"><small>{{ err }}</small></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn me-2 btn-outline-success" :disabled="newAppForm.processing">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </BreezeAuthenticatedLayout>

</template>
<script>

import BreezeAuthenticatedLayout from '../Layouts/Authenticated.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import StudentEditStudentTab from "../Components/StudentEditStudentTab.vue";
import StudentEditAppTab from "../Components/StudentEditAppTab.vue";
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'StudentEdit',
    components: {
        StudentEditStudentTab,
        StudentEditAppTab,
        BreezeAuthenticatedLayout, Head, BreezeInput, BreezeLabel, Link, BreezeSelect, useForm, FormSubmitAlert
    },
    props: {
        result: Object,
        now: String,
        provinces: Object,
        countries: Object,
        schools: Object,
    },
    data() {
        return {
            editForm: null,
            activeTab: 'plsc-app',
            activeApp: null,
            activeAppIndex: null,

            newAppForm: useForm({
                formState: true,
                formSuccessMsg: 'Form was submitted successfully.',
                formFailMsg: 'There was an error submitting this form.',
                student_id: this.result.id,
                institution_id: null,
                application_year: null,
                ssd: null,
                sed: null,
                program_of_study: null,
                credential: null,
                parent_last_name: null,
                parent_first_name: null,
                parent_employee_id: null,
                parent_department_id: null,
                parent_address: null,
                parent_city: null,
                parent_province: null,
                parent_postal_code: null,
                parent_phone_number: null,
                parent_email: null,
                parent_ministry: null,
                parent_branch: null,
                parent_job_title: null,
                high_school_average: 0,
                post_secondary_average: 0,
                total_score: 0,
                rank: 0,
                seven_fifty_word_essay: 0,
                high_school_transcript: 0,
                post_secondary_transcript: 0,
                student_reference_letter: 0,
                communication_skills: 0,
                enrollment_confirmed: 0,
                forward_to_committee: 0,
                comment: '',
                three_years_in_gov: false,
                sfas_date: '',
                receive_date: '',
                status_code: 0,
                other_org: 0,
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
                if(vm.newAppForm.schoolsListHidden === false){
                    vm.newAppForm.school = {name:''};
                    vm.newAppForm.schoolsListHidden = true;
                    vm.schoolsList = vm.schools;
                }
            }, 300);
        },
        assignSchool: function (school) {
            this.newAppForm.institution_id = school.id;
            this.newAppForm.school = school;
            this.newAppForm.schoolsListHidden = true;
            this.schoolsList = this.schools;
        },
        filterActiveSchools: function (e) {
            this.newAppForm.schoolsListHidden = false;
            let search = e.target.value.toLowerCase();
            if(search.length > 2){
                this.schoolsList = this.schools.filter(obj => {
                    if(obj.name == null)
                        return false;
                    return obj.name.toLowerCase().indexOf(search) >= 0;
                } );
            }
        },
        back: function()
        {
            window.history.back();
        },
        showApp: function (index)
        {
            this.activeAppIndex = index;
            this.activeApp = this.result.applications[index];
        },

        newApplication: function ()
        {
            let vm = this;
            this.newAppForm.formState = '';
            this.newAppForm.post('/plsc/applications', {
                onSuccess: () => {
                    $("#newAppModal").modal('hide')
                        .on('hidden.bs.modal', function () {
                            vm.newAppForm.reset();
                            vm.newAppForm.formState = true;
                        });
                },
                onError: () => {
                    this.newAppForm.formState = false;
                },
                preserveState: true
            });
        },


    },

    watch: {
        result: {
            handler(newValue, oldValue) {
                this.activeApp = null;
                if(this.activeAppIndex != null && this.result.applications != null){
                    this.activeApp = this.result.applications[this.activeAppIndex];
                }

                this.editForm = JSON.parse(JSON.stringify(newValue));

            },
            deep: true
        }
    },
    mounted() {
        this.editForm = JSON.parse(JSON.stringify(this.result));
        this.schoolsList = this.schools;
    }
}
</script>
