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

            <div class="col-md-4">
                <BreezeLabel for="inputFirstName" class="form-label" value="First Name" />
                <BreezeInput type="text" class="form-control" id="inputFirstName" :value="editForm.first_name" disabled readonly />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputLastName" class="form-label" value="Last Name" />
                <BreezeInput type="text" class="form-control" id="inputLastName" :value="editForm.last_name" disabled readonly />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputSin" class="form-label" value="SIN" />
                <BreezeInput type="text" class="form-control" id="inputSin" :value="editForm.sin" disabled readonly />
            </div>

            <div class="col-md-6">
                <BreezeLabel for="selectDirectLend" class="form-label" value="Direct Lend" />
                <BreezeSelect class="form-select" id="selectDirectLend" v-model="editForm.direct_lend">
                    <option value="BCSL Service Bureau">BCSL Service Bureau</option>
                    <option value="N/A">N/A</option>
                    <option value="None">None</option>
                </BreezeSelect>
            </div>
            <div class="col-md-6">
                <BreezeLabel for="selectRiskSharing" class="form-label" value="Risk Sharing/Guaranteed" />
                <BreezeSelect class="form-select" id="selectRiskSharing" v-model="editForm.risk_sharing_guaranteed">
                    <option value="Bank of Nova Scotia">Bank of Nova Scotia</option>
                    <option value="CIBC">CIBC</option>
                    <option value="Royal Bank of Canada">Royal Bank of Canada</option>
                </BreezeSelect>
            </div>

            <div class="col-md-4">
                <BreezeLabel for="inputDLO" class="form-label" value="Direct Lend Outstanding Balance" />
                <div class="input-group">
                    <div class="input-group-text">$</div>
                    <BreezeInput type="number" step="0.001" class="form-control" id="inputDLO" v-model="editForm.direct_lend_outstanding_balance" />
                </div>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputRSLO" class="form-label" value="Risk Sharing Outstanding Balance" />
                <div class="input-group">
                    <div class="input-group-text">$</div>
                    <BreezeInput type="number" step="0.001" class="form-control" id="inputRSLO" v-model="editForm.risk_sharing_outstanding_balance" />
                </div>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputGLO" class="form-label" value="Guaranteed Outstanding Balance" />
                <div class="input-group">
                    <div class="input-group-text">$</div>
                    <BreezeInput type="number" step="0.001" class="form-control" id="inputGLO" v-model="editForm.guaranteed_outstanding_balance" />
                </div>
            </div>

            <div class="col-md-4">
                <BreezeLabel for="inputReceiveDate" class="form-label" value="Receive Date" />
                <BreezeInput type="date" min="1990-01-01" max="2040-12-31" class="form-control" id="inputReceiveDate" v-model="editForm.receive_date" />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputEffectiveDate" class="form-label" value="Effective Date" />
                <BreezeInput type="date" min="1990-01-01" max="2040-12-31" class="form-control" id="inputEffectiveDate" v-model="editForm.effective_date" />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputProcessDate" class="form-label" value="Process Date" />
                <BreezeInput type="date" min="1990-01-01" max="2040-12-31" class="form-control" id="inputProcessDate" v-model="editForm.process_date" />
            </div>

            <div class="col-md-4">
                <BreezeLabel for="selectProfession" class="form-label" value="Profession" />
                <BreezeSelect class="form-select" id="selectProfession" v-model="editForm.profession">
                    <option value="Nurse">Nurse</option>
                    <option value="Licensed Practical Nurse">Licensed Practical Nurse</option>
                    <option value="Physiotherapist">Physiotherapist</option>
                    <option value="Medical Laboratory Technologist">Medical Laboratory Technologist</option>
                    <option value="Technology education teacher">Technology education teacher</option>
                    <option value="Pharmacist">Pharmacist</option>
                    <option value="Doctor">Doctor</option>
                    <option value="Midwife">Midwife</option>
                    <option value="Audiologist">Audiologist</option>
                    <option value="Pathologist">Pathologist</option>
                    <option value="Occupational therapist">Occupational therapist</option>
                    <option value="Nurse practitioner">Nurse practitioner</option>
                    <option value="School psychologist">School psychologist</option>
                    <option value="Teacher of deaf">Teacher of deaf</option>
                    <option value="Teacher of visually impaired">Teacher of visually impaired</option>
                    <option value="Speech pathologist">Speech pathologist</option>
                    <option value="Diagnostic Medical Sonographer">Diagnostic Medical Sonographer</option>
                    <option value="Polysomnographer">Polysomnographer</option>
                    <option value="Respiratory Therapist">Respiratory Therapist</option>
                </BreezeSelect>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="selectEmployer" class="form-label" value="Employer" />
                <BreezeSelect class="form-select" id="selectEmployer" v-model="editForm.employer">
                    <option value="Health Authority">Health Authority</option>
                    <option value="School District">School District</option>
                    <option value="Private">Private</option>
                    <option value="First Nations">First Nations</option>
                    <option value="Health Authority Affiliate">Health Authority Affiliate</option>
                    <option value="Other">Other</option>
                </BreezeSelect>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="selectEmploymentStatus" class="form-label" value="Employment Status" />
                <BreezeSelect class="form-select" id="selectEmploymentStatus" v-model="editForm.employment_status">
                    <option value="Casual">Casual</option>
                    <option value="Part Time">Part Time</option>
                    <option value="Full Time">Full Time</option>
                </BreezeSelect>
            </div>

            <div class="col-md-12">
                <div class="card">
                <div class="card-header">Why did you choose to practice in an underserved community?</div>
                <div class="card-body">
                    <div class="form-check">
                        <label for="checkboxWhy1" class="form-check-label">Always lived in the community in which I am practising.</label>
                        <input type="checkbox" class="form-check-input" id="checkboxWhy1" v-model="editForm.why_choose1" />
                    </div>
                    <div class="form-check">
                        <label for="checkboxWhy2" class="form-check-label">Career opportunities/advancement.</label>
                        <input type="checkbox" class="form-check-input" id="checkboxWhy2" v-model="editForm.why_choose2" />
                    </div>
                    <div class="form-check">
                        <label for="checkboxWhy3" class="form-check-label">Felt I could make the greatest contribution in an underserved community.</label>
                        <input type="checkbox" class="form-check-input" id="checkboxWhy3" v-model="editForm.why_choose3" />
                    </div>
                    <div class="form-check">
                        <label for="checkboxWhy4" class="form-check-label">Other (please provide reason)</label>
                        <input type="checkbox" class="form-check-input" id="checkboxWhy4" v-model="editForm.why_choose4" />
                    </div>
                    <div v-if="editForm.why_choose4" class="">
                        <BreezeLabel for="inputOtherReason" class="form-label" value="Process Date" />
                        <BreezeInput type="text" class="form-control" id="inputOtherReason" v-model="editForm.other_reason" />
                    </div>
                    <div class="form-check">
                        <label for="checkboxWhy5" class="form-check-label">Incentive to have BC student loan forgiven under loan forgiveness program.</label>
                        <input type="checkbox" class="form-check-input" id="checkboxWhy5" v-model="editForm.why_choose5" />
                    </div>
                </div>
                    </div>
            </div>

            <div class="col-md-4">
                <BreezeLabel for="selectCommunity" class="form-label" value="Community" />
                <BreezeSelect class="form-select" id="selectCommunity" v-model="editForm.employment_status">
                    <option value="100 Mile House">100 Mile House</option>
                    <option value="Alert Bay / Namgis First Nation">Alert Bay / Namgis First Nation</option>
                    <option value="Ashcroft / Cache Creek / Ashcroft Indian Band /Bonaparte Indian Band / Oregon Jack Creek Indian Band">Ashcroft / Cache Creek / Ashcroft Indian Band /Bonaparte Indian Band / Oregon Jack Creek Indian Band</option>
                    <option value="Burns Lake / Francois Lake">Burns Lake / Francois Lake</option>
                    <option value="Castlegar">Castlegar</option>
                    <option value="Chase / Scotch Creek / Adams Lake Indian Band / Little Shuswap Indian Band / Neskonlith Indian Band">Chase / Scotch Creek / Adams Lake Indian Band / Little Shuswap Indian Band / Neskonlith Indian Band</option>
                    <option value="Cranbrook / ?aq'am (St. Mary's)">Cranbrook / ?aq'am (St. Mary's)</option>
                    <option value="Creston / Lower Kootenay Band">Creston / Lower Kootenay Band</option>
                    <option value="Dawson Creek">Dawson Creek</option>
                    <option value="Fernie">Fernie</option>
                    <option value="Fort St John / Taylor">Fort St John / Taylor</option>
                    <option value="Gold River / Mowachaht-Muchalaht First Nation">Gold River / Mowachaht-Muchalaht First Nation</option>
                    <option value="Golden">Golden</option>
                    <option value="Greenwood / Midway / Rock Creek">Greenwood / Midway / Rock Creek</option>
                    <option value="Invermere / Windermere / ?Akisq'nuk (Akisqnuk) / Shuswap Band">Invermere / Windermere / ?Akisq'nuk (Akisqnuk) / Shuswap Band</option>
                    <option value="Kaslo">Kaslo</option>
                    <option value="Kimberley">Kimberley</option>
                    <option value="Kitimat">Kitimat</option>
                    <option value="Lillooet / Cayoose Creek Indian Band (Sekw'el'was) / Lillooet Indian Band (T'it'q'et) / Xaxli'p First Nation">Lillooet / Cayoose Creek Indian Band (Sekw'el'was) / Lillooet Indian Band (T'it'q'et) / Xaxli'p First Nation</option>
                    <option value="Masset / Old Masset Village Council">Masset / Old Masset Village Council</option>
                    <option value="Merritt / Coldwater Indian Band / Lower Nicola Indian Band / Upper Nicola Band">Merritt / Coldwater Indian Band / Lower Nicola Indian Band / Upper Nicola Band</option>
                    <option value="Nelson">Nelson</option>
                    <option value="New Denver">New Denver</option>
                    <option value="Port Hardy / Gwa'sala-Nakwazda'xw / Kwakiutl First Nation (Kwakwaka'wakw) / Tlatlasikwala First Nation">Port Hardy / Gwa'sala-Nakwazda'xw / Kwakiutl First Nation (Kwakwaka'wakw) / Tlatlasikwala First Nation</option>
                    <option value="Prince Rupert">Prince Rupert</option>
                    <option value="Princeton">Princeton</option>
                    <option value="Queen Charlotte / Skidegate Band">Queen Charlotte / Skidegate Band</option>
                    <option value="Quesnel">Quesnel</option>
                    <option value="Revelstoke">Revelstoke</option>
                    <option value="Salmo">Salmo</option>
                    <option value="Smithers">Smithers</option>
                    <option value="Sparwood">Sparwood</option>
                    <option value="Terrace / Kitselas First Nation / Kitsumkalum Band">Terrace / Kitselas First Nation / Kitsumkalum Band</option>
                    <option value="Tofino /Tla-O-Qui-Aht First Nations">Tofino /Tla-O-Qui-Aht First Nations</option>
                    <option value="Trail / Rossland / Fruitvale">Trail / Rossland / Fruitvale</option>
                    <option value="Ucluelet / Toquaht Nation / Ucluetlet First Nation (Yuułuʔiłʔatḥ)">Ucluelet / Toquaht Nation / Ucluetlet First Nation (Yuułuʔiłʔatḥ)</option>
                    <option value="Vanderhoof">Vanderhoof</option>
                    <option value="Williams Lake / Soda Creek Indian Band (Xatsull First Nation)">Williams Lake / Soda Creek Indian Band (Xatsull First Nation)</option>
                </BreezeSelect>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="selectAwardStatus" class="form-label" value="Award Status" />
                <BreezeSelect class="form-select" id="selectAwardStatus" v-model="editForm.status">
                    <option value="Approved">Approved</option>
                    <option value="Denied">Denied</option>
                    <option value="Other">Other</option>
                </BreezeSelect>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="selectRemoveReason" class="form-label" value="Remove Reason" />
                <BreezeSelect class="form-select" id="selectRemoveReason" v-model="editForm.declined_removed_reason">
                    <option value="Under Audit">Under Audit</option>
                    <option value="BCSL in Default">BCSL in Default</option>
                    <option value="Missing Employment doc after 1st year of employment">Missing Employment doc after 1st year of employment</option>
                    <option value="Removed from program because now In full time studies">Removed from program because now In full time studies</option>
                    <option value="Applicant grad prior to Aug/00">Applicant grad prior to Aug/00</option>
                    <option value="Application not signed or Missing info never received">Application not signed or Missing info never received</option>
                    <option value="No outstanding BC Student Loan">No outstanding BC Student Loan</option>
                    <option value="Applicant not in elig occupation">Applicant not in elig occupation</option>
                    <option value="Applicant not grad after Dec 1/04">Applicant not grad after Dec 1/04</option>
                    <option value="App no longer employed in underserved community">App no longer employed in underserved community</option>
                    <option value="Not employed with Pub Health Care Fac">Not employed with Pub Health Care Fac</option>
                    <option value="Applicant not employed in underserved community">Applicant not employed in underserved community</option>
                    <option value="Applicant outside of BC">Applicant outside of BC</option>
                    <option value="Type A">Type A</option>
                </BreezeSelect>
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
    },
    data() {
        return {
            noChanges: true,
            editForm: '',

        }
    },
    methods: {

        updateApplication: function ()
        {

            this.editForm.formState = null;

                this.editForm.put('/twp/applications/' + this.result.id, {
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
        this.editForm = JSON.parse(JSON.stringify(this.result));
        this.editForm.formSuccessMsg = 'Form was submitted successfully.';
        this.editForm.formFailMsg = 'There was an error submitting this form.';

    }
}

</script>
