<template>

    <AuthenticatedLayout v-bind="$attrs">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            LFP Intake Search
                        </div>
                        <div class="card-body">
                            <IntakeSearchBox page="/lfp/intakes/" />
                        </div>
                    </div>
                </div>
                <div v-if="intakeForm != null" class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            LFP Edit Intake
                            <div class="float-end">
                                <span class="me-1">Status:</span>
                                <input @click="toggleStatus('Pending')" type="radio" class="btn-check" name="intake_status" id="intake_status_pending" autocomplete="off" :checked="intakeForm.intake_status === 'Pending'">
                                <label class="btn btn-outline-primary btn-sm me-1" for="intake_status_pending">Pending</label>

                                <input @click="toggleStatus('Ready')" type="radio" class="btn-check" name="intake_status" id="intake_status_ready" autocomplete="off" :checked="intakeForm.intake_status === 'Ready'">
                                <label class="btn btn-outline-success btn-sm me-1" for="intake_status_ready">Ready</label>

                                <input @click="toggleStatus('Registered')" type="radio" class="btn-check" name="intake_status" id="intake_status_registered" autocomplete="off" :checked="intakeForm.intake_status === 'Registered'">
                                <label class="btn btn-outline-success btn-sm me-1" for="intake_status_registered">Registered</label>

                                <input @click="toggleStatus('Denied')" type="radio" class="btn-check" name="intake_status" id="intake_status_denied" autocomplete="off" :checked="intakeForm.intake_status === 'Denied'">
                                <label class="btn btn-outline-warning btn-sm" for="intake_status_denied">Denied</label>

                            </div>
                        </div>
                        <form class="card-body" @submit.prevent="storeIntake">
                            <div class="row g-3">

                                <div class="col-md-3">
                                    <BreezeLabel for="inputFirstName" class="form-label" value="First Name" />
                                    <BreezeInput type="text" class="form-control" id="inputFirstName" v-model="intakeForm.first_name" :disabled="isFormDisabled" />
                                </div>
                                <div class="col-md-3">
                                    <BreezeLabel for="inputLastName" class="form-label" value="Last Name" />
                                    <BreezeInput type="text" class="form-control" id="inputLastName" v-model="intakeForm.last_name" :disabled="isFormDisabled" />
                                </div>
                                <div class="col-md-3">
                                    <BreezeLabel for="inputSin" class="form-label" value="SIN" />
                                    <BreezeInput type="text" class="form-control" id="inputSin"
                                                 @change="formatSin" maxlength="9"
                                                 v-model.trim="intakeForm.sin" :disabled="isFormDisabled" />
                                </div>
                                <div class="col-md-3">
                                    <BreezeLabel for="inputReceiveDate" class="form-label" value="Receive Date" />
                                    <BreezeInput type="date" min="2019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputReceiveDate" v-model="intakeForm.receive_date" :disabled="isFormDisabled" />
                                </div>


                                <div class="col-md-3">
                                    <BreezeLabel for="inputRepaymentStartDate" class="form-label" value="Repayment Start Date" />
                                    <BreezeInput type="date" min="2019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputRepaymentStartDate" v-model="intakeForm.repayment_start_date" :disabled="isFormDisabled" />
                                </div>
                                <div class="col-md-3">
                                    <BreezeLabel for="inputAmountOwing" class="form-label" value="Amount Owing" />
                                    <div class="input-group">
                                        <div class="input-group-text">$</div>
                                        <BreezeInput type="number" step="0.001" class="form-control" id="inputAmountOwing" v-model="intakeForm.amount_owing" :disabled="isFormDisabled" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <BreezeLabel for="selectInGoodStanding" class="form-label" value="In Good Standing" />
                                    <BreezeSelect class="form-select" id="selectInGoodStanding" v-model="intakeForm.in_good_standing" :disabled="isFormDisabled">
                                        <option value="Yes">Yes</option>
                                        <option value="Delinquent">Delinquent</option>
                                        <option value="Bankruptcy">Bankruptcy</option>
                                    </BreezeSelect>
                                </div>
                                <div class="col-md-3">
                                    <BreezeLabel for="inputProposedRegistrationDate" class="form-label" value="Prop. Reg. Date" />
                                    <BreezeInput type="date" min="2019-01-01" max="2040-12-31" placeholder="Proposed registration date YYYY-MM-DD" class="form-control" id="inputProposedRegistrationDate" v-model="intakeForm.proposed_registration_date" :disabled="isFormDisabled" />
                                </div>

                                <div class="col-md-3">
                                    <BreezeLabel for="selectProfession" class="form-label" value="Profession" />
                                    <BreezeSelect class="form-select" id="selectProfession" v-model="intakeForm.profession" :disabled="isFormDisabled">
                                        <option v-for="u in utils['Profession']" :value="u">{{ u }}</option>
                                    </BreezeSelect>
                                </div>
                                <div class="col-md-3">
                                    <BreezeLabel for="selectEmployer" class="form-label" value="Employer" />
                                    <BreezeSelect class="form-select" id="selectEmployer" v-model="intakeForm.employer" :disabled="isFormDisabled">
                                        <option v-for="u in utils['Employer']" :value="u">{{ u }}</option>
                                    </BreezeSelect>
                                </div>
                                <div class="col-md-3">
                                    <BreezeLabel for="selectCommunity" class="form-label" value="Community" />
                                    <BreezeSelect class="form-select" id="selectCommunity" v-model="intakeForm.community" :disabled="isFormDisabled">
                                        <option v-for="u in utils['Community']" :value="u">{{ u }}</option>
                                    </BreezeSelect>
                                </div>

                                <div class="col-md-3">
                                    <BreezeLabel for="selectEmploymentStatus" class="form-label" value="Employment Status" />
                                    <BreezeSelect class="form-select" id="selectEmploymentStatus" v-model="intakeForm.employment_status" :disabled="isFormDisabled">
                                        <option v-for="u in utils['Employment Status']" :value="u">{{ u }}</option>
                                    </BreezeSelect>
                                </div>

                                <div v-if="intakeForm.intake_status === 'Denied'" class="col-md-12">
                                    <BreezeLabel for="selectDenialReason" class="form-label" value="Denial Reason" />
                                    <BreezeSelect class="form-select" id="selectDenialReason" v-model="intakeForm.denial_reason" :disabled="isFormDisabled">
                                        <option v-for="u in utils['Remove Reason']" :value="u">{{ u }}</option>
                                    </BreezeSelect>
                                </div>

                                <div class="col-md-12">
                                    <BreezeLabel for="inputComments" class="form-label" value="Comments" />
                                    <textarea class="form-control" id="inputComments" v-model="intakeForm.comment" rows="3" :disabled="isFormDisabled">{{ intakeForm.comment }}</textarea>
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
                                <button type="submit" class="btn me-2 btn-outline-success" :disabled="intakeForm.processing">Update Application</button>
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
    name: 'IntakeEdit',
    components: {
        AuthenticatedLayout, IntakeSearchBox, Head, Link, BreezeInput, BreezeSelect, BreezeLabel, FormSubmitAlert
    },
    props: {
        utils: Object,
        result: Object,
    },
    data() {
        return {
            noChanges: true,
            intakeForm: null,
            isFormDisabled: false,
        }
    },
    methods: {
        formatSin() {
            let sin = this.intakeForm.sin + "";
            sin = sin.replace(/\D/g, '');
            this.intakeForm.sin = sin.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3');
        },
        toggleStatus: function(status)
        {
            this.intakeForm.intake_status = status;

            //quick update for status
            let f = useForm({
                intake_status: status,
                id: this.result.id,
                sin: this.result.sin,
                first_name: this.result.first_name,
                last_name: this.result.last_name
            });
            f.put('/lfp/intakes/' + this.result.id, {});
        },
        storeIntake: function ()
        {
            this.intakeForm.formState = null;
            this.intakeForm.put('/lfp/intakes/' + this.result.id, {
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
    mounted(){
        this.intakeForm = useForm(JSON.parse(JSON.stringify(this.result)));
        this.intakeForm.formState = '';
        this.isFormDisabled = this.intakeForm.intake_status === 'Registered';
        this.formatSin(); //sin stored with no spaces
    }

}
</script>
