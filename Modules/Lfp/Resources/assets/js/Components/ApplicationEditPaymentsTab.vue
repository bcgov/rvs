<style scoped>

[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>

    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total BCSL to be paid</h5>
                    <p class="card-text fs-2">${{app[0].pl_dire_forcalc_outstdg_amt}}</p>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Principal Paid</h5>
                    <p class="card-text fs-2">${{principalPaid}}</p>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Principal Outstanding</h5>
                    <p class="card-text fs-2">${{principalOutstanding}}</p>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <div v-if="payments != null && payments.length > 0" class="table-responsive pb-3">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Direct Lend Before Pay</th>
                <th scope="col">Direct Lend</th>
                <th scope="col">Risk Sharing</th>
                <th scope="col">Guaranteed</th>
                <th scope="col">Status Code</th>
                <th scope="col">Anniversary Date</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(row, i) in payments">
                <template v-if="row.sfas_payment_attr != null">
                    <td><a href="#" @click="updatePayment(i)">${{ $formatMoney(row.sfas_payment_attr.pl_dire_bcsl_before_pay_amt) }}</a></td>
                    <td>${{ $formatMoney(row.sfas_payment_attr.pl_dire_principal_pay_amt) }}</td>
                    <td>${{ $formatMoney(row.sfas_payment_attr.pl_risk_principal_pay_amt) }}</td>
                    <td>${{ $formatMoney(row.sfas_payment_attr.pl_guar_principal_pay_amt) }}</td>
                    <td>{{ row.sfas_payment_attr.pl_payment_status_code }}</td>
                    <td>{{ cleanDate(row.sfas_payment_attr.pl_anniversary_dte) }}</td>
                </template>
            </tr>
            </tbody>
        </table>
    </div>
    <div v-else class="text-center">No Payments</div>

    <div class="modal modal-lg fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div v-if="editPaymentForm != ''" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaymentModalLabel">Edit Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="editPayment">
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditPaymentDate" class="form-label" value="Payment Date" />
                                    <BreezeInput type="text" class="form-control" id="inputeditPaymentDate" :value="editPaymentForm.sfas_payment_attr.pl_payment_dte" readonly disabled />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditRptIntfDate" class="form-label" value="Report Intf. Date" />
                                    <BreezeInput type="text" class="form-control" id="inputeditRptIntfDate" v-model="editPaymentForm.sfas_payment_attr.pl_rpt_intf_dte" readonly disabled />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditRptPaidDate" class="form-label" value="Report Paid Date" />
                                    <BreezeInput type="text" class="form-control" id="inputeditRptPaidDate" v-model="editPaymentForm.sfas_payment_attr.pl_rpt_paid_dte" readonly disabled />
                                </div>

                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditEmplDate" class="form-label" value="Empl. Verify Date" />
                                    <BreezeInput v-if="editPaymentForm.sfas_payment_attr != null" type="text" class="form-control" id="inputeditEmplDate" v-model="editPaymentForm.sfas_payment_attr.pl_employment_verif_dte" readonly disabled />
                                    <BreezeInput v-else type="text" class="form-control" id="inputeditEmplDate" v-model="editPaymentForm.sfas_payment_attr.pl_employment_verif_dte" readonly disabled />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditEmplAtAnniver" class="form-label" value="Employed at Anniversary?" />
                                    <BreezeInput v-if="editPaymentForm.sfas_payment_attr != null" type="text" class="form-control" id="inputeditEmplAtAnniver" v-model="editPaymentForm.sfas_payment_attr.pl_employed_at_anniver_flg" readonly disabled />
                                    <BreezeInput v-else type="text" class="form-control" id="inputeditEmplAtAnniver" v-model="editPaymentForm.sfas_payment_attr.pl_employed_at_anniver_flg" readonly disabled />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditHrsService" class="form-label" value="Hrs of Service" />
                                    <BreezeInput v-if="editPaymentForm.sfas_payment_attr != null" type="text" class="form-control" id="inputeditHrsService" v-model="editPaymentForm.sfas_payment_attr.hrs_of_service" readonly disabled />
                                    <BreezeInput v-else type="text" class="form-control" id="inputeditHrsService" v-model="editPaymentForm.sfas_payment_attr.hrs_of_service" readonly disabled />
                                </div>

                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditStatusUpdateDte" class="form-label" value="Status Update Date" />
                                    <BreezeInput v-if="editPaymentForm.sfas_payment_attr != null" type="text" class="form-control" id="inputeditStatusUpdateDte" v-model="editPaymentForm.sfas_payment_attr.pl_payment_status_dte" readonly disabled />
                                    <BreezeInput v-else type="text" class="form-control" id="inputeditStatusUpdateDte" v-model="editPaymentForm.sfas_payment_attr.pl_payment_status_dte" readonly disabled />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel for="inputAnnivDate" class="form-label" value="Prop. Anniv. Date" />
                                    <BreezeInput type="date" min="2000-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputAnnivDate" v-model="editPaymentForm.anniversary_date" readonly disabled />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel for="inputSfasPayStatus" class="form-label" value="SFAS Status" />
                                    <BreezeInput type="text" class="form-control" id="inputSfasPayStatus" v-model="editPaymentForm.sfas_pay_status" readonly disabled />
                                </div>

                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditPaymentReport" class="form-label" value="Reconciled with Payment Report" />
                                    <BreezeInput type="date" min="2019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputeditPaymentReport" v-model="editPaymentForm.reconciled_with_payment_report_date" />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditGalaxyReport" class="form-label" value="Reconciled with Galaxy Date" />
                                    <BreezeInput type="date" min="2019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputeditGalaxyReport" v-model="editPaymentForm.reconciled_with_galaxy_date" />
                                </div>

                                <div class="col-md-4">
                                    <BreezeLabel for="inputPropPayDate" class="form-label" value="Prop. Pay Date" />
                                    <BreezeInput type="date" min="2000-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputPropPayDate" v-model="editPaymentForm.proposed_pay_date" />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel for="inputPropPayAmount" class="form-label" value="Prop. Pay Amount" />
                                    <BreezeInput type="number" step="0.01" class="form-control" id="inputPropPayAmount" v-model="editPaymentForm.proposed_pay_amount" />
                                </div>

                                <div class="col-md-4">
                                    <BreezeLabel for="inputPropHrs" class="form-label" value="Prop. Hrs. of Service" />
                                    <BreezeInput type="number" class="form-control" id="inputPropHrs" v-model="editPaymentForm.proposed_hrs_of_service" />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel for="inputOcPayStatus" class="form-label" value="OpenShift Status" />
                                    <BreezeSelect class="form-select" id="inputOcPayStatus" v-model="editPaymentForm.oc_pay_status">
                                        <option></option>
                                        <option value="Pending">Pending</option>
                                        <option value="Ready">Ready</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Denial Pending">Denial Pending</option>
                                    </BreezeSelect>
                                </div>


                                <!--                                'anniversary_date' => 'date_format:Y-m-d|nullable',
'proposed_pay_date' => 'date_format:Y-m-d|nullable',-->
                                <!--                                'proposed_pay_amount' => 'numeric|nullable',
                                'proposed_hrs_of_service' => 'numeric|nullable',-->
                                <!--                                'sfas_pay_status' => 'nullable',
                                'oc_pay_status' => 'nullable',-->

                                <div class="col-md-3">
                                    <BreezeLabel for="selectCommunity" class="form-label" value="Community" />
                                    <BreezeSelect class="form-select" id="selectCommunity" v-model="editPaymentForm.community">
                                        <option v-for="u in utils['Community']" :value="u">{{ u }}</option>
                                    </BreezeSelect>
                                </div>
                                <div class="col-md-3">
                                    <BreezeLabel for="selectProfession" class="form-label" value="Profession" />
                                    <BreezeSelect class="form-select" id="selectProfession" v-model="editPaymentForm.profession">
                                        <option v-for="u in utils['Profession']" :value="u">{{ u }}</option>
                                    </BreezeSelect>
                                </div>
                                <div class="col-md-3">
                                    <BreezeLabel for="selectEmployer" class="form-label" value="Employer" />
                                    <BreezeSelect class="form-select" id="selectEmployer" v-model="editPaymentForm.employer">
                                        <option v-for="u in utils['Employer']" :value="u">{{ u }}</option>
                                    </BreezeSelect>
                                </div>
                                <div class="col-md-3">
                                    <BreezeLabel for="selectEmploymentStatus" class="form-label" value="Employment Status" />
                                    <BreezeSelect class="form-select" id="selectEmploymentStatus" v-model="editPaymentForm.employment_status">
                                        <option v-for="u in utils['Employment Status']" :value="u">{{ u }}</option>
                                    </BreezeSelect>
                                </div>


                                <div class="col-md-12">
                                    <BreezeLabel for="inputeditComments" class="form-label" value="Comments" />
                                    <textarea class="form-control" id="inputeditComments" v-model="editPaymentForm.comment" rows="3">{{ editPaymentForm.comment }}</textarea>
                                </div>

                            </div>

                            <div v-if="editPaymentForm.errors != undefined" class="row">
                                <div class="col-12">
                                    <div v-if="editPaymentForm.hasErrors == true" class="alert alert-danger mt-3">
                                        <ul>
                                            <li v-for="err in editPaymentForm.errors"><small>{{ err }}</small></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn me-2 btn-outline-success" :disabled="editPaymentForm.processing">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Link, useForm} from '@inertiajs/vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'ApplicationEditPaymentsTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect, FormSubmitAlert
    },
    props: {
        payments: Object,
        utils: Object,
        app: Object
    },
    data() {
        return {
            editPaymentForm: '',

        }
    },
    methods: {
        cleanDate: function(d)
        {
            if(d == null) return d;
            let date = d.split(" ");
            return date[0]
        },
        updatePayment: function(i)
        {
            this.editPaymentForm = useForm(this.payments[i]);
            $("#editPaymentModal").modal('show');
        },
        editPayment: function ()
        {
            let vm = this;
            this.editPaymentForm.formState = '';
            this.editPaymentForm.put('/lfp/payments/' + this.editPaymentForm.id, {
                onSuccess: () => {
                    $("#editPaymentModal").modal('hide')
                        .on('hidden.bs.modal', function () {

                            vm.editPaymentForm.reset();
                            vm.activeTab = 'payment';
                            vm.editPaymentForm.formState = true;
                        });
                },
                onError: () => {
                    this.editPaymentForm.formState = false;
                },
                preserveState: true
            });
        },
    },
    computed: {
        principalPaid() {
            let total = 0;
            for(let i=0; i<this.payments.length; i++){
                if(this.payments[i].sfas_payment_attr && this.payments[i].sfas_payment_attr.pl_payment_status_code === 'PAID')
                    total += parseFloat(this.payments[i].sfas_payment_attr.pl_dire_principal_pay_amt);
            }
            return parseFloat(total.toFixed(3));
        },
        principalOutstanding() {
            const result = parseFloat(this.app[0].pl_dire_forcalc_outstdg_amt) - this.principalPaid;
            return parseFloat(result.toFixed(3));
        },
    },
}

</script>
