<style scoped>
tr {
    padding-bottom: 7px;
    display: block;
}
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>
    <form v-if="editForm != null" @submit.prevent="updateApplication">
        <div class="row g-3">

            <div class="col-md-3">
                <BreezeLabel for="inputSin" class="form-label" value="SIN" />
                <BreezeInput type="text" class="form-control" id="inputSin" :value="student.sin" disabled readonly />
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputFirstName" class="form-label" value="First Name" />
                <BreezeInput type="text" class="form-control" id="inputFirstName" :value="student.first_name" disabled readonly />
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputLastName" class="form-label" value="Last Name" />
                <BreezeInput type="text" class="form-control" id="inputLastName" :value="student.last_name" disabled readonly />
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputFullNameAlias" class="form-label" value="Full Name Alias" />
                <BreezeInput type="text" class="form-control" id="inputFullNameAlias" v-model="editForm.full_name_alias" />
            </div>

            <div class="col-md-4">
                <BreezeLabel for="inputDLO" class="form-label" value="Direct Lend Outstanding Balance" />
                <div class="input-group">
                    <div class="input-group-text">$</div>
                    <BreezeInput type="number" step="0.001" class="form-control" id="inputDLO" :value="readOnlyApp.pl_dire_forcalc_outstdg_amt" disabled readonly />
                </div>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputRSLO" class="form-label" value="Risk Sharing Outstanding Balance" />
                <div class="input-group">
                    <div class="input-group-text">$</div>
                    <BreezeInput type="number" step="0.001" class="form-control" id="inputRSLO" :value="readOnlyApp.pl_risk_forcalc_outstanding_amt" disabled readonly />
                </div>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputGLO" class="form-label" value="Guaranteed Outstanding Balance" />
                <div class="input-group">
                    <div class="input-group-text">$</div>
                    <BreezeInput type="number" step="0.001" class="form-control" id="inputGLO" :value="readOnlyApp.pl_guar_forcalc_outstanding_amt" disabled readonly />
                </div>
            </div>

            <div class="col-md-4">
                <BreezeLabel for="inputReceiveDate" class="form-label" value="Enrolment Date" />
                <BreezeInput type="text" class="form-control" id="inputReceiveDate" :value="readOnlyApp.pl_app_received_dte" disabled readonly />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputEntryDate" class="form-label" value="Entry Date" />
                <BreezeInput type="text" class="form-control" id="inputEntryDate" :value="readOnlyApp.pl_app_entry_dte" disabled readonly />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputStatusDate" class="form-label" value="Status Change Date" />
                <BreezeInput type="text" class="form-control" id="inputStatusDate" :value="readOnlyApp.pl_app_status_dte" disabled readonly />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputTypeBDate" class="form-label" value="Type B Date" />
                <BreezeInput type="text" class="form-control" id="inputTypeBDate" :value="readOnlyApp.pl_type_b_dte" disabled readonly />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputAppStatus" class="form-label" value="Status Code" />
                <BreezeInput type="text" class="form-control" id="inputAppStatus" :value="readOnlyApp.pl_app_status_code" disabled readonly />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputNoYears" class="form-label" value="Number of Years" />
                <BreezeInput type="text" class="form-control" id="inputNoYears" :value="readOnlyApp.no_of_years" disabled readonly />
            </div>


            <div class="col-md-6">
                <BreezeLabel for="selectDirectLend" class="form-label" value="Direct Lend" />
                <BreezeSelect class="form-select" id="selectDirectLend" v-model="editForm.direct_lend">
                    <option v-for="u in utils['Direct Lend']" :value="u">{{ u }}</option>
                </BreezeSelect>
            </div>
            <div class="col-md-6">
                <BreezeLabel for="selectRiskSharing" class="form-label" value="Risk Sharing/Guaranteed" />
                <BreezeSelect class="form-select" id="selectRiskSharing" v-model="editForm.risk_sharing_guaranteed">
                    <option v-for="u in utils['Risk Sharing/Guaranteed']" :value="u">{{ u }}</option>
                </BreezeSelect>
            </div>

            <div class="col-md-4">
                <BreezeLabel for="selectProfession" class="form-label" value="Profession" />
                <BreezeSelect class="form-select" id="selectProfession" v-model="editForm.profession">
                    <option v-for="u in utils['Profession']" :value="u">{{ u }}</option>
                </BreezeSelect>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="selectEmployer" class="form-label" value="Employer" />
                <BreezeSelect class="form-select" id="selectEmployer" v-model="editForm.employer">
                    <option v-for="u in utils['Employer']" :value="u">{{ u }}</option>
                </BreezeSelect>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="selectEmploymentStatus" class="form-label" value="Employment Status" />
                <BreezeSelect class="form-select" id="selectEmploymentStatus" v-model="editForm.employment_status">
                    <option v-for="u in utils['Employment Status']" :value="u">{{ u }}</option>
                </BreezeSelect>
            </div>

            <div class="col-md-6">
                <BreezeLabel for="selectCommunity" class="form-label" value="Community" />
                <BreezeSelect class="form-select" id="selectCommunity" v-model="editForm.community">
                    <option v-for="u in utils['Community']" :value="u">{{ u }}</option>
                </BreezeSelect>
            </div>
            <div class="col-md-6">
                <BreezeLabel for="selectRemoveReason" class="form-label" value="Remove Reason" />
                <BreezeSelect class="form-select" id="selectRemoveReason" v-model="editForm.declined_removed_reason">
                    <option v-for="u in utils['Remove Reason']" :value="u">{{ u }}</option>
                </BreezeSelect>
            </div>

            <div class="col-md-12">
                <BreezeLabel for="inputEditComments" class="form-label" value="Comments" />
                <textarea class="form-control" id="inputEditComments" v-model="editForm.comment" rows="3">{{ editForm.comment }}</textarea>
            </div>

            <div v-if="needsUpdate" class="row">
                <div class="col-12">
                    <div class="alert alert-warning mt-3">
                        <ul>
                            <li>Your form has some Intake data that has not been saved yet. Please click on Update Application to save that data.</li>
                        </ul>
                    </div>
                </div>
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
            <button type="submit" class="btn me-2 btn-outline-success" :disabled="editForm.processing">Update Application</button>
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
    name: 'ApplicationEditFormTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect, FormSubmitAlert
    },
    props: {
        result: Object,
        app: Object|null,
        utils: Object,
        student: Object,
    },
    data() {
        return {
            noChanges: true,
            editForm: '',
            readOnlyApp: '',
            needsUpdate: false,
        }
    },
    methods: {

        updateApplication: function ()
        {

            this.editForm.formState = null;
            this.editForm = useForm(this.editForm);

            this.editForm.put('/lfp/applications/update/' + this.result.id, {
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
        if(this.app[0] != null){
            this.readOnlyApp = this.app[0];
        }
        this.editForm = JSON.parse(JSON.stringify(this.result));
        this.editForm.formSuccessMsg = 'Form was submitted successfully.';
        this.editForm.formFailMsg = 'There was an error submitting this form.';

        // If we have an intake and the application is missing some data from the intake, then pre-populate
        if(this.result.intake != null){
            if(this.editForm.profession == null){
                this.editForm.profession = this.result.intake.profession;
                this.needsUpdate = true;
            }
            if(this.editForm.employer == null){
                this.editForm.employer = this.result.intake.employer;
                this.needsUpdate = true;
            }
            if(this.editForm.employment_status == null){
                this.editForm.employment_status = this.result.intake.employment_status;
                this.needsUpdate = true;
            }
            if(this.editForm.community == null){
                this.editForm.community = this.result.intake.community;
                this.needsUpdate = true;
            }
            if(this.editForm.comment == null){
                this.editForm.comment = this.result.intake.comment;
                this.needsUpdate = true;
            }
        }

    }
}

</script>
