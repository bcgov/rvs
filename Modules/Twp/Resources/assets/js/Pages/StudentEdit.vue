<style scoped>
.st0{fill-rule:evenodd;clip-rule:evenodd;}
</style>
<template>
        <Head title="TWP - Student Edit" />

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
                                    <StudentEditStudentTab :result="editForm" :indigeneity_types="indigeneity_types"></StudentEditStudentTab>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-header">
                                    Applications
                                    <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newTwpAppModal">New TWP App</button>

                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li v-for="(app, i) in result.applications" class="list-group-item d-flex justify-content-between align-items-start">
                                            <button @click="showApp(i)" type="button" class="btn btn-link">
                                                <span v-if="app.received_date != null">{{ app.received_date }}</span>
                                                <span v-else>Missing Received Date</span>
                                            </button>
                                            <span v-if="app.application_status === 'APPROVED'" class="badge rounded-pill text-bg-success">{{app.application_status}}</span>
                                            <span v-else-if="app.application_status === 'IN PROGRESS'" class="badge rounded-pill text-bg-info">{{app.application_status}}</span>
                                            <span v-else-if="app.application_status === 'DENIED'" class="badge rounded-pill text-bg-danger">{{app.application_status}}</span>
                                            <span v-else class="badge rounded-pill text-bg-primary">{{app.application_status}}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div v-if="activeApp != null" class="col-md-8 mt-3 mb-5">
                            <div class="card">
                                <div v-if="editForm != null" class="card-header">
                                    Edit Application
                                    <div v-if="activeTab==='plsc-app' && lettersEnabled !== false" class="btn-group float-end ms-1">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Letters
                                        </button>
                                        <ul class="dropdown-menu" style="">
                                            <li v-if="lettersEnabled==='denied'"><button class="dropdown-item" type="button" @click="downloadStudentLetter('student_denied')">Student Denied</button></li>
                                            <li v-if="lettersEnabled==='denied'"><button class="dropdown-item" type="button" @click="downloadSchool">School Denied</button></li>
                                            <li v-if="lettersEnabled==='success' || lettersEnabled==='success_under_age' || lettersEnabled==='success_with_exception'"><button class="dropdown-item" type="button" @click="downloadTransfer">Student Transfer</button></li>
                                            <li v-if="lettersEnabled==='success' || lettersEnabled==='success_under_age'"><button class="dropdown-item" type="button" @click="downloadStudentLetter('student_success')">Student Successful</button></li>
                                            <li v-if="lettersEnabled==='success_under_age'"><a class="dropdown-item" :href="'/twp/students-letters/student_success_under_age/' + activeApp.id" target="_blank">Under Age Student Successful</a></li>
                                        </ul>
                                    </div>
                                    <button v-if="activeTab==='plsc-app' && editForm.application != null && editForm.application.application_status === 'APPROVED' && activeApp.program == null" type="button" class="btn btn-warning btn-sm float-end">Missing Program</button>
                                    <button v-if="activeTab==='plsc-app' && editForm.application != null && editForm.application.application_status === 'APPROVED' && editForm.age < 19" type="button" class="btn btn-warning btn-sm float-end">Under 19</button>
                                    <button v-if="activeTab==='payments'" type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newPaymentModal">New Payment</button>
                                    <button v-if="activeTab==='grant-app'" type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newGrantAppModal">New Grant App</button>
                                </div>
                                <div class="card-body" v-if="activeApp != null">

                                    <ul class="nav nav-tabs mb-3" id="myStudentTab" role="tablist">
                                        <li @click="switchActiveTab('twp-app')" class="nav-item" role="presentation">
                                            <button class="nav-link" :class="activeTab==='twp-app' ? 'active':''" id="twp-app-tab" data-bs-toggle="tab" data-bs-target="#twp-app-tab-pane" type="button" role="tab" aria-controls="twp-app-tab-pane" aria-selected="false">TWP App</button>
                                        </li>
                                        <li @click="switchActiveTab('grant-app')" class="nav-item" role="presentation">
                                            <button class="nav-link" :class="activeTab==='grant-app' ? 'active':''" id="grant-app-tab" data-bs-toggle="tab" data-bs-target="#grant-app-tab-pane" type="button" role="tab" aria-controls="grant-app-tab-pane" aria-selected="false">Grant App</button>
                                        </li>
                                        <li @click="switchActiveTab('program')" class="nav-item" role="presentation">
                                            <button class="nav-link" :class="activeTab==='program' ? 'active':''" id="program-tab" data-bs-toggle="tab" data-bs-target="#program-tab-pane" type="button" role="tab" aria-controls="program-tab-pane" aria-selected="false">Program</button>
                                        </li>
                                        <li v-if="activeApp.program != null" @click="switchActiveTab('payments')" class="nav-item" role="presentation">
                                            <button class="nav-link" :class="activeTab==='payments' ? 'active':''" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments-tab-pane" type="button" role="tab" aria-controls="payments-tab-pane" aria-selected="false">Payments</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myStudentTabContent">
                                        <div class="tab-pane fade" :class="activeTab==='twp-app' ? 'active show':''" id="twp-app-tab-pane" role="tabpanel" aria-labelledby="twp-app-tab" tabindex="1">
                                            <StudentEditTwpAppTab v-if="activeTab==='twp-app'" :reasons="reasons" :twpStudentId="activeApp.student_id" :result="activeApp"></StudentEditTwpAppTab>
                                        </div>
                                        <div class="tab-pane fade" :class="activeTab==='grant-app' ? 'active show':''" id="grant-app-tab-pane" role="tabpanel" aria-labelledby="grant-app-tab" tabindex="2">
                                            <StudentEditGrantAppTab v-if="activeTab==='grant-app'" :twpStudentId="activeApp.student_id" :result="activeApp.grants"></StudentEditGrantAppTab>
                                        </div>
                                        <div class="tab-pane fade" :class="activeTab==='program' ? 'active show':''" id="program-tab-pane" role="tabpanel" aria-labelledby="program-tab" tabindex="3">
                                            <StudentEditProgramTab v-if="activeTab==='program'" :twpStudentId="activeApp.student_id" :twpApplicationId="activeApp.id" :result="activeApp.program" :schools="schools"></StudentEditProgramTab>
                                        </div>
                                        <div v-if="activeApp.program != null" class="tab-pane fade" :class="activeTab==='payments' ? 'active show':''" id="payments-tab-pane" role="tabpanel" aria-labelledby="payments-tab" tabindex="4">
                                            <StudentEditPaymentTab v-if="activeTab==='payments'" :twpStudentId="activeApp.student_id" :pTypes="p_types" :result="activeApp.payments" :program="activeApp.program"></StudentEditPaymentTab>
                                        </div>
                                    </div>

                                </div>
                                <h1 v-else class="lead">No results</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal modal-lg fade" id="newTwpAppModal" tabindex="-1" aria-labelledby="newTwpAppModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newTwpAppModalLabel">New TWP App</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form @submit.prevent="newTwp">
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <BreezeLabel for="inputReceivedDate" class="form-label" value="Received Date" />
                                            <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputReceivedDate" v-model="newTwpForm.received_date" />
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="inputApplicationStatus" class="form-label" value="Application Status" />
                                            <BreezeSelect class="form-select" id="inputApplicationStatus" v-model="newTwpForm.application_status">
                                                <option value="APPROVED">Approved</option>
                                                <option value="DENIED">Denied</option>
                                                <option value="IN PROGRESS">In Progress</option>
                                                <option value="APPROVED ON EXCEPTION">Approved on Exception</option>
                                                <option value="WITHDRAWN">Withdrawn</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="inputStudentNumber" class="form-label" value="Inst. Student #" />
                                            <BreezeInput type="text" class="form-control" id="inputStudentNumber" v-model="newTwpForm.institution_student_number" />
                                        </div>


                                    </div>

                                    <div v-if="newTwpForm.errors != undefined" class="row">
                                        <div class="col-12">
                                            <div v-if="newTwpForm.hasErrors == true" class="alert alert-danger mt-3">
                                                <ul>
                                                    <li v-for="err in newTwpForm.errors"><small>{{ err }}</small></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn me-2 btn-outline-success" :disabled="newTwpForm.processing">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal modal-lg fade" id="newGrantAppModal" tabindex="-1" aria-labelledby="newGrantAppModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newGrantAppModalLabel">New Grant App</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form @submit.prevent="newGrant">
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <BreezeLabel for="inputReceivedDate" class="form-label" value="Received Date" />
                                            <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputReceivedDate" v-model="newGrantForm.received_date" />
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="inputApplicationStatus" class="form-label" value="Application Status" />
                                            <BreezeSelect class="form-select" id="inputApplicationStatus" v-model="newGrantForm.grant_status">
                                                <option value="APPROVED">Approved</option>
                                                <option value="DENIED">Denied</option>
                                                <option value="IN PROGRESS">In Progress</option>
                                                <option value="APPROVED ON EXCEPTION">Approved on Exception</option>
                                                <option value="WITHDRAWN">Withdrawn</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="inputAmount" class="form-label" value="Amount" />
                                            <div class="input-group">
                                                <div class="input-group-text">$</div>
                                                <input type="text" class="form-control" id="inputAmount" v-model="newGrantForm.grant_amount" />
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <BreezeLabel for="inputComments" class="form-label" value="Comments" />
                                            <textarea class="form-control" id="inputComments" v-model="newGrantForm.grant_comments" rows="3">{{ newGrantForm.grant_comments }}</textarea>
                                        </div>

                                    </div>

                                    <div v-if="newGrantForm.errors != undefined" class="row">
                                        <div class="col-12">
                                            <div v-if="newGrantForm.hasErrors == true" class="alert alert-danger mt-3">
                                                <ul>
                                                    <li v-for="err in newGrantForm.errors"><small>{{ err }}</small></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn me-2 btn-outline-success" :disabled="newGrantForm.processing">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal modal-lg fade" id="newPaymentModal" tabindex="-1" aria-labelledby="newPaymentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newPaymentModalLabel">Add New Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form @submit.prevent="newPayment">
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <BreezeLabel for="newPaymentDate" class="form-label" value="Payment Date" />
                                            <BreezeInput type="date" max="2040-12-31" class="form-control" id="newPaymentDate" v-model="newPaymentForm.payment_date" />
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="newPaymentAmount" class="form-label" value="Payment Amount" />
                                            <div class="input-group">
                                                <div class="input-group-text">$</div>
                                                <input type="text" class="form-control" id="newPaymentAmount" v-model="newPaymentForm.payment_amount">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="newPaymentType" class="form-label" value="Payment Type" />
                                            <BreezeSelect class="form-select" id="newPaymentType" v-model="newPaymentForm.payment_type_id">
                                                <option>Select Payment Type</option>
                                                <option v-for="type in p_types" :value="type.id">{{ type.title }}</option>
                                            </BreezeSelect>
                                        </div>
                                    </div>

                                    <div v-if="newPaymentForm.errors != undefined" class="row">
                                        <div class="col-12">
                                            <div v-if="newPaymentForm.hasErrors == true" class="alert alert-danger mt-3">
                                                <ul>
                                                    <li v-for="err in newPaymentForm.errors"><small>{{ err }}</small></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn me-2 btn-outline-success" :disabled="newPaymentForm.processing">Submit</button>
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
import StudentEditProgramTab from "../Components/StudentEditProgramTab.vue";
import StudentEditTwpAppTab from "../Components/StudentEditTwpAppTab.vue";
import StudentEditGrantAppTab from "../Components/StudentEditGrantAppTab.vue";
import StudentEditPaymentTab from "../Components/StudentEditPaymentTab.vue";
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'StudentEdit',
    components: {
        StudentEditStudentTab,
        StudentEditTwpAppTab,
        StudentEditGrantAppTab,
        StudentEditProgramTab,
        StudentEditPaymentTab,
        BreezeAuthenticatedLayout, Head, BreezeInput, BreezeLabel, Link, BreezeSelect, useForm, FormSubmitAlert
    },
    props: {
        result: Object,
        now: String,
        reasons: Object,
        provinces: Object,
        p_types: Object,
        indigeneity_types: Object,
        schools: Object,
        batches: Object,
        ineligibles: Object,
        active_staff: Object,
        all_staff: Object,
    },
    data() {
        return {
            editForm: null,
            activeTab: 'twp-app',
            activeApp: null,
            activeAppIndex: null,
            newPaymentForm: useForm({
                student_id: '',
                application_id: '',
                payment_type_id: '',
                program_id: null,
                payment_date: '',
                payment_amount: '',
            }),
            schoolLetterForm: useForm({
                contact_name: null,
                contact_email: null,
            }),
            studentTransferForm: useForm({
                contact_name: null,
                contact_email: null,
            }),
            studentSuccessForm: useForm({
                contact_name: null,
                contact_email: null,
            }),
            newTwpForm: useForm({
                formState: true,
                formSuccessMsg: 'Form was submitted successfully.',
                formFailMsg: 'There was an error submitting this form.',
                student_id: this.result.id,
                received_date: null,
                application_status: null,
                institution_student_number: null
            }),
            newGrantForm: useForm({
                formState: true,
                formSuccessMsg: 'Form was submitted successfully.',
                formFailMsg: 'There was an error submitting this form.',
                id: null,
                student_id: '',
                application_id: '',

                received_date: '',
                grant_status: '',
                grant_comments: '',
                grant_amount: 0,
            }),
            lettersEnabled: false,
        }
    },
    methods: {

        back: function()
        {
            window.history.back();
        },
        showApp: function (index)
        {
            this.activeTab = 'twp-app';

            this.activeAppIndex = index;
            this.activeApp = this.result.applications[index];

            this.newPaymentForm.student_id = this.result.id;
            this.newGrantForm.student_id = this.result.id;
            this.newPaymentForm.application_id = this.activeApp.id;
            this.newGrantForm.application_id = this.activeApp.id;
            if(this.activeApp.program != null){
                this.newPaymentForm.program_id = this.activeApp.program.id;

                if(this.activeApp.application_status === 'APPROVED'){
                    this.lettersEnabled = 'success';
                    if(this.result.age < 19){
                        this.lettersEnabled = 'success_under_age';
                    }
                }
                if(this.activeApp.application_status === "APPROVED ON EXCEPTION"){
                    this.lettersEnabled = 'success_with_exception';
                }
                if(this.activeApp.application_status === 'DENIED'){
                    this.lettersEnabled = 'denied';
                }
            }else{
                this.lettersEnabled = false;
            }
        },
        downloadStudentLetter: async function (type)
        {
            let form = useForm({});
            let downloadEnabled = true;
            if(type === 'student_success'){
                this.studentSuccessForm.contact_name = prompt("Please enter contact name");
                this.studentSuccessForm.contact_email = prompt("Please enter contact email");
                if(this.studentSuccessForm.contact_name == "" || this.studentSuccessForm.contact_email == ""){
                    downloadEnabled = false;
                }
                form = this.studentSuccessForm;

            }

            if(downloadEnabled){
                try {
                    const response = await axios.post('/twp/students-letters/' + type + '/' + this.activeApp.id, form, {
                        responseType: 'arraybuffer'
                    });
                    const blob = new Blob([response.data], { type: 'application/pdf' });
                    const url = window.URL.createObjectURL(blob);
                    const filename = response.headers['content-disposition'].split('filename=')[1].replace(/"/g, '');
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } catch (error) {
                    console.error(error);
                }
            }
        },
        downloadTransfer: async function ()
        {
            this.studentTransferForm.contact_name = prompt("Please enter contact name");
            this.studentTransferForm.contact_email = prompt("Please enter contact email");

            if((this.studentTransferForm.contact_name && this.studentTransferForm.contact_name.trim() !== "") &&
                (this.studentTransferForm.contact_email && this.studentTransferForm.contact_email.trim() !== "")){
                try {
                    const response = await axios.post('/twp/students-transfer-letters/' + this.activeApp.id, this.studentTransferForm, {
                        responseType: 'arraybuffer'
                    });
                    const blob = new Blob([response.data], { type: 'application/pdf' });
                    const url = window.URL.createObjectURL(blob);
                    const filename = response.headers['content-disposition'].split('filename=')[1].replace(/"/g, '');
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } catch (error) {
                    console.error(error);
                }
            }
        },
        downloadSchool: async function ()
        {
            this.schoolLetterForm.contact_name = prompt("Please enter contact name");
            this.schoolLetterForm.contact_email = prompt("Please enter contact email");
            if((this.schoolLetterForm.contact_name && this.schoolLetterForm.contact_name.trim() !== "") &&
                (this.schoolLetterForm.contact_email && this.schoolLetterForm.contact_email.trim() !== "")){

                try {
                    const response = await axios.post('/twp/students-letters/' + this.activeApp.id, this.schoolLetterForm, {
                        responseType: 'arraybuffer'
                    });
                    const blob = new Blob([response.data], { type: 'application/pdf' });
                    const url = window.URL.createObjectURL(blob);
                    const filename = response.headers['content-disposition'].split('filename=')[1].replace(/"/g, '');
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } catch (error) {
                    console.error(error);
                }
            }
        },
        switchActiveTab: function (tab)
        {
            this.activeTab = tab;
        },
        newTwp: function ()
        {
            let vm = this;
            this.newTwpForm.formState = '';
            this.newTwpForm.post('/twp/applications', {
                onSuccess: () => {
                    $("#newTwpAppModal").modal('hide')
                        .on('hidden.bs.modal', function () {
                            vm.newTwpForm.reset();
                            vm.activeTab = 'twp-app';
                            vm.newTwpForm.formState = true;
                        });
                },
                onError: () => {
                    this.newTwpForm.formState = false;
                },
                preserveState: true
            });
        },

        newGrant: function ()
        {
            let vm = this;
            this.newGrantForm.formState = '';
            this.newGrantForm.post('/twp/grants', {
                onSuccess: () => {
                    $("#newGrantAppModal").modal('hide')
                        .on('hidden.bs.modal', function () {
                            vm.newGrantForm.reset();
                            vm.activeTab = 'twp-app';
                            vm.newGrantForm.formState = true;
                        });
                },
                onError: () => {
                    this.newGrantForm.formState = false;
                },
                preserveState: true
            });
        },

        newPayment: function ()
        {
            let vm = this;
            this.newPaymentForm.formState = '';
            this.newPaymentForm.post('/twp/payments', {
                onSuccess: () => {
                    $("#newPaymentModal").modal('hide')
                        .on('hidden.bs.modal', function () {

                            vm.newPaymentForm.reset();
                            vm.activeTab = 'twp-app';
                            vm.newPaymentForm.formState = true;
                        });
                },
                onError: () => {
                    this.newGrantForm.formState = false;
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

                    if(this.activeApp.program != null){
                        this.newPaymentForm.program_id = this.activeApp.program.id;
                    }
                }

                this.editForm = JSON.parse(JSON.stringify(newValue));

                this.lettersEnabled = false;
                if(this.activeApp != null && this.activeApp.program != null && this.activeApp.program.institution != null){
                    if(this.activeApp.application_status === 'APPROVED'){
                        this.lettersEnabled = 'success';
                        if(this.editForm.age < 19){
                            this.lettersEnabled = 'success_under_age';
                        }
                    }
                    if(this.activeApp.application_status === "APPROVED ON EXCEPTION"){
                        this.lettersEnabled = 'success_with_exception';
                    }
                    if(this.activeApp.application_status === 'DENIED'){
                        this.lettersEnabled = 'denied';
                    }
                }
            },
            deep: true
        }
    },
    mounted() {
        this.editForm = JSON.parse(JSON.stringify(this.result));
    }
}
</script>
