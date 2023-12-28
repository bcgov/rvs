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
                            </div>
                            <div class="card-body">

                                <ul class="nav nav-tabs mb-3" id="myStudentTab" role="tablist">
                                    <li @click="switchActiveTab('form')" class="nav-item" role="presentation">
                                        <button class="nav-link" :class="activeTab==='form' ? 'active':''" id="form-tab" data-bs-toggle="tab" data-bs-target="#form-tab-pane" type="button" role="tab" aria-controls="form-tab-pane" aria-selected="false">LFP Application</button>
                                    </li>
                                    <li @click="switchActiveTab('payment')" class="nav-item" role="presentation">
                                        <button class="nav-link" :class="activeTab==='payment' ? 'active':''" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment-tab-pane" type="button" role="tab" aria-controls="payment-tab-pane" aria-selected="false">Payments</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myStudentTabContent">
                                    <div class="tab-pane fade" :class="activeTab==='form' ? 'active show':''" id="form-tab-pane" role="tabpanel" aria-labelledby="form-tab" tabindex="2">
                                        <ApplicationEditFormTab v-if="activeTab==='form'" :student="student[student.length - 1]" :utils="utils" :result="result" :app="app"></ApplicationEditFormTab>
                                    </div>
                                    <div class="tab-pane fade" :class="activeTab==='payment' ? 'active show':''" id="payment-tab-pane" role="tabpanel" aria-labelledby="payment-tab" tabindex="4">
                                        <ApplicationEditPaymentsTab v-if="activeTab==='payment'" :utils="utils" :payments="result.payments"></ApplicationEditPaymentsTab>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


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
