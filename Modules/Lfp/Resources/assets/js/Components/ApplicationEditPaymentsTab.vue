<style scoped>

[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>

    <div v-if="result != null && result.length > 0" class="table-responsive pb-3">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Payment Date</th>
                <th scope="col">Direct Lend</th>
                <th scope="col">Direct Lend Interest</th>
                <th scope="col">Risk Sharing</th>
                <th scope="col">Risk Sharing Interest</th>
                <th scope="col">Guaranteed</th>
                <th scope="col">Comments</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(row, i) in result">
                <th scope="row"><a href="#" @click="updatePayment(i)">{{ row.payment_date }}</a></th>
                <td>${{ row.direct_lend_payment_amount }}</td>
                <td>${{ row.direct_lend_interest_payment_amount }}</td>
                <td>${{ row.risk_sharing_payment_amount }}</td>
                <td>${{ row.risk_sharing_interest_payment_amount }}</td>
                <td>${{ row.guaranteed_payment_amount }}</td>
                <td>{{ row.comment }}</td>
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
                                    <BreezeInput type="date" min="1019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputeditPaymentDate" v-model="editPaymentForm.payment_date" />
                                </div>

                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditLend" class="form-label" value="Direct Lend Amount" />
                                    <div class="input-group">
                                        <div class="input-group-text">$</div>
                                        <input type="number" step="0.001" class="form-control" id="inputeditLend" v-model="editPaymentForm.direct_lend_payment_amount" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditRisk" class="form-label" value="Risk Sharing Amount" />
                                    <div class="input-group">
                                        <div class="input-group-text">$</div>
                                        <input type="number" step="0.001" class="form-control" id="inputeditRisk" v-model="editPaymentForm.risk_sharing_payment_amount" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditGur" class="form-label" value="Guaranteed Amount" />
                                    <div class="input-group">
                                        <div class="input-group-text">$</div>
                                        <input type="number" step="0.001" class="form-control" id="inputeditGur" v-model="editPaymentForm.guaranteed_payment_amount" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditLendInterest" class="form-label" value="Direct Lend Interest" />
                                    <div class="input-group">
                                        <div class="input-group-text">$</div>
                                        <input type="number" step="0.001" class="form-control" id="inputeditLendInterest" v-model="editPaymentForm.direct_lend_interest_payment_amount" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditRiskInterest" class="form-label" value="Risk Sharing Interest" />
                                    <div class="input-group">
                                        <div class="input-group-text">$</div>
                                        <input type="number" step="0.001" class="form-control" id="inputeditRiskInterest" v-model="editPaymentForm.risk_sharing_interest_payment_amount" />
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditInSfas" class="form-label" value="Entered in SFAS Date" />
                                    <BreezeInput type="date" min="1019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputeditInSfas" v-model="editPaymentForm.entered_in_sfas_date" />
                                </div>

                                <div class="col-md-2">
                                    <BreezeLabel for="inputAmountIssued" class="form-label" value="Amount Issued" />
                                    <BreezeInput type="text" step="0.001" class="form-control" id="inputAmountIssued" v-model="editPaymentForm.amount_issued" />
                                </div>
                                <div class="col-md-2">
                                    <BreezeLabel for="inputHours" class="form-label" value="Reported Hours" />
                                    <BreezeInput type="number" class="form-control" id="inputHours" v-model="editPaymentForm.reported_hours" />
                                </div>
                                <div class="col-md-4">
                                    <label for="checkboxEmploymentLetter" class="form-check-label">Employment Letter Provided?</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkboxEmploymentLetter" v-model="editPaymentForm.employment_letter_provided" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditAnniversaryDate" class="form-label" value="Anniversary Date" />
                                    <BreezeInput type="date" min="2019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputeditAnniversaryDate" v-model="editPaymentForm.anniversary_date" />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditPaymentReport" class="form-label" value="Reconciled with Payment Report" />
                                    <BreezeInput type="date" min="2019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputeditPaymentReport" v-model="editPaymentForm.reconciled_with_payment_report_date" />
                                </div>
                                <div class="col-md-4">
                                    <BreezeLabel for="inputeditGalaxyReport" class="form-label" value="Reconciled with Galaxy Date" />
                                    <BreezeInput type="date" min="2019-01-01" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputeditGalaxyReport" v-model="editPaymentForm.reconciled_with_galaxy_date" />
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
        result: Object,
    },
    data() {
        return {
            editPaymentForm: '',

        }
    },
    methods: {
        updatePayment: function(i)
        {
            this.editPaymentForm = useForm(this.result[i]);
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
}

</script>
