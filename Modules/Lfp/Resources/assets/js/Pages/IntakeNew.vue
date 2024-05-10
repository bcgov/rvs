<template>
    <AuthenticatedLayout v-bind="$attrs">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <Link @click="$back" href="#" class="btn btn-link">

                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 200" width="25">
                                            <g>
                                                <path class="st0" d="M102.5,3.3c4.3,4.3,4.3,9,0,12.6l0,0L26.6,77.6L102.5,153.6c4.3,4.3,4.3,9,0,12.6s-9,4.3-12.6,0L11,89.2   c-4.3-4.3-4.3-9,0-12.6l0,0L89.9,3.3C93.5-1.4,98.2-1.4,102.5,3.3L102.5,3.3z"></path>
                                                <path class="st0" d="M22.3,77.6c0-6.1,5-11.1,11.1-11.1h144.7c30.8,0,55.9,25.1,55.9,55.9v122.9c0,6.1-5,11.1-11.1,11.1   s-11.1-5-11.1-11.1V122.4c0-18.4-15-33.4-33.4-33.4H33.4C27.3,89.2,22.3,83.3,22.3,77.6z"></path>
                                            </g>
                                        </svg>

                                    </Link>

                                    LFP Intake Search
                                </div>
                                <div class="card-body">
                                    <IntakeSearchBox page="/lfp/intakes/" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    LFP New Intake
                                    <div class="float-end">
                                        <span class="me-1">Status:</span>
                                        <input @click="toggleStatus('Pending')" type="radio" class="btn-check" name="intake_status" id="intake_status_pending" autocomplete="off" :checked="intakeForm.intake_status === 'Pending'">
                                        <label class="btn btn-outline-primary btn-sm me-1" for="intake_status_pending">Pending</label>

                                        <input @click="toggleStatus('Ready')" type="radio" class="btn-check" name="intake_status" id="intake_status_ready" autocomplete="off" :checked="intakeForm.intake_status === 'Ready'">
                                        <label class="btn btn-outline-success btn-sm me-1" for="intake_status_ready">Ready</label>

                                        <input @click="toggleStatus('Registered')" type="radio" class="btn-check" name="intake_status" id="intake_status_registered" autocomplete="off" :checked="intakeForm.intake_status === 'Registered'">
                                        <label class="btn btn-outline-success btn-sm me-1" for="intake_status_registered">Registered</label>

                                        <input @click="toggleStatus('Denied')" type="radio" class="btn-check" name="intake_status" id="intake_status_denied" autocomplete="off" :checked="intakeForm.intake_status === 'Denied'">
                                        <label class="btn btn-outline-warning btn-sm me-1" for="intake_status_denied">Denied</label>

                                        <input @click="toggleStatus('Cancelled')" type="radio" class="btn-check" name="intake_status" id="intake_status_cancelled" autocomplete="off" :checked="intakeForm.intake_status === 'Cancelled'">
                                        <label class="btn btn-outline-danger btn-sm" for="intake_status_cancelled">Cancelled</label>
                                    </div>
                                </div>
                                <form class="card-body" v-if="intakeForm != null" @submit.prevent="storeIntake">
                                    <div class="row g-3">

                                        <div class="col-md-3">
                                            <BreezeLabel for="inputFirstName" class="form-label" value="First Name" />
                                            <BreezeInput type="text" class="form-control" id="inputFirstName" v-model="intakeForm.first_name" />
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputLastName" class="form-label" value="Last Name" />
                                            <BreezeInput type="text" class="form-control" id="inputLastName" v-model="intakeForm.last_name" />
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputSin" class="form-label" value="SIN" />
                                            <BreezeInput type="text" class="form-control" id="inputSin" v-model="intakeForm.sin" />
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputReceiveDate" class="form-label" value="Receive Date" />
                                            <BreezeInput type="date" min="2000-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputReceiveDate" v-model="intakeForm.receive_date" />
                                        </div>


                                        <div class="col-md-3">
                                            <BreezeLabel for="inputRepaymentStartDate" class="form-label" value="Repayment Start Date" />
                                            <BreezeInput type="date" min="2000-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputRepaymentStartDate" v-model="intakeForm.repayment_start_date" />
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputAmountOwing" class="form-label" value="Amount Owing" />
                                            <div class="input-group">
                                                <div class="input-group-text">$</div>
                                                <BreezeInput type="number" step="0.001" class="form-control" id="inputAmountOwing" v-model="intakeForm.amount_owing" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="selectInGoodStanding" class="form-label" value="In Good Standing" />
                                            <BreezeSelect class="form-select" id="selectInGoodStanding" v-model="intakeForm.in_good_standing">
                                                <option value="Yes">Yes</option>
                                                <option value="Delinquent">Delinquent</option>
                                                <option value="Bankruptcy">Bankruptcy</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="inputProposedRegistrationDate" class="form-label" value="Prop. Reg. Date" />
                                            <BreezeInput type="date" min="2019-01-01" max="2040-12-31" placeholder="Proposed registration date YYYY-MM-DD" class="form-control" id="inputProposedRegistrationDate" v-model="intakeForm.proposed_registration_date" />
                                        </div>

                                        <div class="col-md-3">
                                            <BreezeLabel for="selectProfession" class="form-label" value="Profession" />
                                            <BreezeSelect class="form-select" id="selectProfession" v-model="intakeForm.profession">
                                                <option v-for="u in utils['Profession']" :value="u">{{ u }}</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="selectEmployer" class="form-label" value="Employer" />
                                            <BreezeSelect class="form-select" id="selectEmployer" v-model="intakeForm.employer">
                                                <option v-for="u in utils['Employer']" :value="u">{{ u }}</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-3">
                                            <BreezeLabel for="selectCommunity" class="form-label" value="Community" />
                                            <BreezeSelect class="form-select" id="selectCommunity" v-model="intakeForm.community">
                                                <option v-for="u in utils['Community']" :value="u">{{ u }}</option>
                                            </BreezeSelect>
                                        </div>

                                        <div class="col-md-3">
                                            <BreezeLabel for="selectEmploymentStatus" class="form-label" value="Employment Status" />
                                            <BreezeSelect class="form-select" id="selectEmploymentStatus" v-model="intakeForm.employment_status">
                                                <option v-for="u in utils['Employment Status']" :value="u">{{ u }}</option>
                                            </BreezeSelect>
                                        </div>



                                        <div class="col-md-12">
                                            <BreezeLabel for="inputComments" class="form-label" value="Comments" />
                                            <textarea class="form-control" id="inputComments" v-model="intakeForm.comment" rows="3">{{ intakeForm.comment }}</textarea>
                                        </div>

                                        <div v-if="intakeForm.errors != undefined" class="row">
                                            <div class="col-12">
                                                <div v-if="intakeForm.hasErrors == true" class="alert alert-danger mt-3">
                                                    <ul>
                                                        <li v-for="err in intakeForm.errors">{{ err }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-footer mt-3">
                                        <button type="submit" class="btn me-2 btn-outline-success" :disabled="intakeForm.processing">Submit Application</button>
                                    </div>

                                    <FormSubmitAlert :form-state="intakeForm.formState"
                                                     :success-msg="'Intake application record was submitted successfully.'"></FormSubmitAlert>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

        </AuthenticatedLayout>

</template>
<script>

import AuthenticatedLayout from '../Layouts/Authenticated.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import IntakeSearchBox from '../Components/IntakeSearch.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeSelect from '@/Components/Select.vue';
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'IntakeNew',
    components: {
        AuthenticatedLayout, IntakeSearchBox, Head, Link, BreezeInput, BreezeSelect, BreezeLabel, FormSubmitAlert
    },
    props: {
        utils: Object,
    },
    data() {
        return {
            noChanges: true,
            intakeForm: useForm({
                formState: null,
                sin: '',
                first_name: '',
                last_name: '',
                profession: '',
                employer: '',
                employment_status: '',
                in_good_standing: '',
                community: '',
                repayment_start_date: '',
                amount_owing: '',
                receive_date: '',
                proposed_registration_date: '',
                comment: '',
                intake_status: 'Pending'
            }),
        }
    },
    methods: {
        toggleStatus: function(status)
        {
            this.intakeForm.intake_status = status;
        },
        storeIntake: function ()
        {
            this.intakeForm.formState = null;
            this.intakeForm.post('/lfp/intakes', {
                onSuccess: () => {
                    this.intakeForm.formState = true;
                    this.noChanges = true;
                },
                onError: () => {
                    this.intakeForm.formState = false;
                },
            });

        },
    },
}
</script>
