<style scoped>
.st0{fill-rule:evenodd;clip-rule:evenodd;}
</style>
<template>
    <Head title="LFP - Application Edit" />

    <BreezeAuthenticatedLayout v-bind="$attrs">

            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-3 mt-3">
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
                            <div class="card-body">
                                <ApplicationEditStudentTab :student="student"></ApplicationEditStudentTab>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9 mt-3 mb-5">
                        <div class="card">
                            <div v-if="editForm != null" class="card-header">
                                Edit Application
<!--                                <button v-if="activeTab==='payment' && editForm.status === 'Approved'" type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newPaymentModal">New Payment</button>-->
                            </div>
                            <div class="card-body">

                                <ul class="nav nav-tabs mb-3" id="myStudentTab" role="tablist">
                                    <li @click="switchActiveTab('form')" class="nav-item" role="presentation">
                                        <button class="nav-link" :class="activeTab==='form' ? 'active':''" id="form-tab" data-bs-toggle="tab" data-bs-target="#form-tab-pane" type="button" role="tab" aria-controls="form-tab-pane" aria-selected="false">LFP Application</button>
                                    </li>
                                    <li @click="switchActiveTab('payment')" class="nav-item" role="presentation">
                                        <button class="nav-link" :class="activeTab==='payment' ? 'active':''" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment-tab-pane" type="button" role="tab" aria-controls="payment-tab-pane" aria-selected="false">Payments</button>
                                    </li>
<!--                                    <li @click="switchActiveTab('apps')" class="nav-item" role="presentation">-->
<!--                                        <button class="nav-link" :class="activeTab==='apps' ? 'active':''" id="apps-tab" data-bs-toggle="tab" data-bs-target="#apps-tab-pane" type="button" role="tab" aria-controls="apps-tab-pane" aria-selected="false">SABC Apps</button>-->
<!--                                    </li>-->
                                </ul>
                                <div class="tab-content" id="myStudentTabContent">
                                    <div class="tab-pane fade" :class="activeTab==='form' ? 'active show':''" id="form-tab-pane" role="tabpanel" aria-labelledby="form-tab" tabindex="2">
                                        <ApplicationEditFormTab v-if="activeTab==='form'" :utils="utils" :result="result" :app="app"></ApplicationEditFormTab>
                                    </div>
<!--                                    <div class="tab-pane fade" :class="activeTab==='apps' ? 'active show':''" id="apps-tab-pane" role="tabpanel" aria-labelledby="apps-tab" tabindex="1">-->
<!--                                        <ApplicationEditAppsTab v-if="activeTab==='apps'" :result="result" :apps="apps"></ApplicationEditAppsTab>-->
<!--                                    </div>-->
                                    <div class="tab-pane fade" :class="activeTab==='payment' ? 'active show':''" id="payment-tab-pane" role="tabpanel" aria-labelledby="payment-tab" tabindex="4">
                                        <ApplicationEditPaymentsTab v-if="activeTab==='payment'" :utils="utils" :payments="result.payments"></ApplicationEditPaymentsTab>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

<!--        <div class="modal modal-lg fade" id="newPaymentModal" tabindex="-1" aria-labelledby="newPaymentModalLabel" aria-hidden="true">-->
<!--            <div class="modal-dialog">-->
<!--                <div class="modal-content">-->
<!--                    <div class="modal-header">-->
<!--                        <h5 class="modal-title" id="newPaymentModalLabel">New Payment</h5>-->
<!--                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
<!--                    </div>-->
<!--                    <form @submit.prevent="newPayment">-->
<!--                        <div class="modal-body">-->
<!--                            <div class="card-body">-->
<!--                                <div class="row g-3">-->

<!--                                    <div class="col-md-4">-->
<!--                                        <BreezeLabel for="inputPaymentDate" class="form-label" value="Payment Date" />-->
<!--                                        <BreezeInput type="date" min="2019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputPaymentDate" v-model="newPaymentForm.payment_date" />-->
<!--                                    </div>-->

<!--                                    <div class="col-md-4">-->
<!--                                        <BreezeLabel for="inputLend" class="form-label" value="Direct Lend Amount" />-->
<!--                                        <div class="input-group">-->
<!--                                            <div class="input-group-text">$</div>-->
<!--                                            <input type="number" step="0.001" class="form-control" id="inputLend" v-model="newPaymentForm.direct_lend_payment_amount" />-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="col-md-4">-->
<!--                                        <BreezeLabel for="inputRisk" class="form-label" value="Risk Sharing Amount" />-->
<!--                                        <div class="input-group">-->
<!--                                            <div class="input-group-text">$</div>-->
<!--                                            <input type="number" step="0.001" class="form-control" id="inputRisk" v-model="newPaymentForm.risk_sharing_payment_amount" />-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="col-md-4">-->
<!--                                        <BreezeLabel for="inputGur" class="form-label" value="Guaranteed Amount" />-->
<!--                                        <div class="input-group">-->
<!--                                            <div class="input-group-text">$</div>-->
<!--                                            <input type="number" step="0.001" class="form-control" id="inputGur" v-model="newPaymentForm.guaranteed_payment_amount" />-->
<!--                                        </div>-->
<!--                                    </div>-->

<!--                                    <div class="col-md-4">-->
<!--                                        <BreezeLabel for="inputLendInterest" class="form-label" value="Direct Lend Interest" />-->
<!--                                        <div class="input-group">-->
<!--                                            <div class="input-group-text">$</div>-->
<!--                                            <input type="number" step="0.001" class="form-control" id="inputLendInterest" v-model="newPaymentForm.direct_lend_interest_payment_amount" />-->
<!--                                        </div>-->
<!--                                    </div>-->

<!--                                    <div class="col-md-4">-->
<!--                                        <BreezeLabel for="inputRiskInterest" class="form-label" value="Risk Sharing Interest" />-->
<!--                                        <div class="input-group">-->
<!--                                            <div class="input-group-text">$</div>-->
<!--                                            <input type="number" step="0.001" class="form-control" id="inputRiskInterest" v-model="newPaymentForm.risk_sharing_interest_payment_amount" />-->
<!--                                        </div>-->
<!--                                    </div>-->


<!--                                    <div class="col-md-4">-->
<!--                                        <BreezeLabel for="inputInSfas" class="form-label" value="Entered in SFAS Date" />-->
<!--                                        <BreezeInput type="date" min="1019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputInSfas" v-model="newPaymentForm.entered_in_sfas_date" />-->
<!--                                    </div>-->
<!--                                    <div class="col-md-2">-->
<!--                                        <BreezeLabel for="inputAmountIssued" class="form-label" value="Amount Issued" />-->
<!--                                        <BreezeInput type="text" step="0.001" class="form-control" id="inputAmountIssued" v-model="newPaymentForm.amount_issued" />-->
<!--                                    </div>-->
<!--                                    <div class="col-md-2">-->
<!--                                        <BreezeLabel for="inputHours" class="form-label" value="Reported Hours" />-->
<!--                                        <BreezeInput type="number" class="form-control" id="inputHours" v-model="newPaymentForm.reported_hours" />-->
<!--                                    </div>-->
<!--                                    <div class="col-md-4">-->
<!--                                        <label for="checkboxEmploymentLetter" class="form-check-label">Employment Letter Provided?</label>-->
<!--                                        <div class="form-check">-->
<!--                                            <input type="checkbox" class="form-check-input" id="checkboxEmploymentLetter" v-model="newPaymentForm.employment_letter_provided" />-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="col-md-4">-->
<!--                                        <BreezeLabel for="inputeditAnniversaryDate" class="form-label" value="Anniversary Date" />-->
<!--                                        <BreezeInput type="date" min="2019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputeditAnniversaryDate" v-model="newPaymentForm.anniversary_date" />-->
<!--                                    </div>-->

<!--                                    <div class="col-md-4">-->
<!--                                        <BreezeLabel for="inputPaymentReport" class="form-label" value="Reconciled with Payment Report" />-->
<!--                                        <BreezeInput type="date" min="2019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputPaymentReport" v-model="newPaymentForm.reconciled_with_payment_report_date" />-->
<!--                                    </div>-->
<!--                                    <div class="col-md-4">-->
<!--                                        <BreezeLabel for="inputGalaxyReport" class="form-label" value="Reconciled with Galaxy Date" />-->
<!--                                        <BreezeInput type="date" min="2019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputGalaxyReport" v-model="newPaymentForm.reconciled_with_galaxy_date" />-->
<!--                                    </div>-->

<!--                                    <div class="col-md-12">-->
<!--                                        <BreezeLabel for="inputComments" class="form-label" value="Comments" />-->
<!--                                        <textarea class="form-control" id="inputComments" v-model="newPaymentForm.comment" rows="3">{{ newPaymentForm.comment }}</textarea>-->
<!--                                    </div>-->

<!--                                </div>-->

<!--                                <div v-if="newPaymentForm.errors != undefined" class="row">-->
<!--                                    <div class="col-12">-->
<!--                                        <div v-if="newPaymentForm.hasErrors == true" class="alert alert-danger mt-3">-->
<!--                                            <ul>-->
<!--                                                <li v-for="err in newPaymentForm.errors"><small>{{ err }}</small></li>-->
<!--                                            </ul>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="modal-footer">-->
<!--                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="newPaymentForm.processing">Submit</button>-->
<!--                        </div>-->
<!--                    </form>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->



    </BreezeAuthenticatedLayout>

</template>
<script>

import BreezeAuthenticatedLayout from '../Layouts/Authenticated.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ApplicationEditFormTab from "../Components/ApplicationEditFormTab.vue";
import ApplicationEditStudentTab from "../Components/ApplicationEditStudentTab.vue";
import ApplicationEditAppsTab from "../Components/ApplicationEditAppsTab.vue";
import ApplicationEditPaymentsTab from "../Components/ApplicationEditPaymentsTab";
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'ApplicationEdit',
    components: {
        ApplicationEditFormTab,
        ApplicationEditStudentTab,
        ApplicationEditAppsTab,
        ApplicationEditPaymentsTab,
        BreezeAuthenticatedLayout, Head, BreezeInput, BreezeLabel, Link, BreezeSelect, useForm, FormSubmitAlert
    },
    props: {
        result: Object,
        student: Object,
        app: Object,
        now: String,
        payments: Object,
        utils: Object
    },
    data() {
        return {
            editForm: null,
            activeTab: 'form',
            // newPaymentForm: useForm({
            //     lfp_id: '',
            //     payment_date: '',
            //     direct_lend_payment_amount: '',
            //     direct_lend_interest_payment_amount: '',
            //     risk_sharing_payment_amount: '',
            //     risk_sharing_interest_payment_amount: '',
            //     guaranteed_payment_amount: '',
            //     entered_in_sfas_date: '',
            //     amount_issued: '',
            //     reported_hours: '',
            //     employment_letter_provided: '',
            //     reconciled_with_payment_report_date: '',
            //     reconciled_with_galaxy_date: '',
            //     anniversary_date: '',
            //     comment: '',
            // }),
        }
    },
    methods: {

        // newPayment: function ()
        // {
        //     let vm = this;
        //     this.newPaymentForm.formState = '';
        //     this.newPaymentForm.post('/lfp/payments', {
        //         onSuccess: () => {
        //             $("#newPaymentModal").modal('hide')
        //                 .on('hidden.bs.modal', function () {
        //
        //                     vm.newPaymentForm.reset();
        //                     vm.activeTab = 'payment';
        //                     vm.newPaymentForm.formState = true;
        //                 });
        //         },
        //         onError: () => {
        //             this.newPaymentForm.formState = false;
        //         },
        //         preserveState: true
        //     });
        // },

        back: function()
        {
            window.history.back();
        },

        switchActiveTab: function (tab)
        {
            this.activeTab = tab;
        },

    },


    mounted() {
        this.editForm = JSON.parse(JSON.stringify(this.result));
        // this.newPaymentForm.lfp_id = this.editForm.id;
    }
}
</script>
