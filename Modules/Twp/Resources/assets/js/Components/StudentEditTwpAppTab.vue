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
    <form v-if="editForm != null" @submit.prevent="updateStudent">
        <div class="row g-3">
            <div class="col-md-4">
                <BreezeLabel for="inputReceivedDate" class="form-label" value="Received Date" />
                <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputReceivedDate" v-model="editForm.received_date" />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputApplicationStatus" class="form-label" value="Application Status" />
                <BreezeInput v-if="editForm.application_status ==='APPROVED ON APPEAL'" type="text" class="form-control" id="inputApplicationStatus" v-model="editForm.application_status" readonly="readonly"/>
                <BreezeSelect v-else class="form-select" id="inputApplicationStatus" v-model="editForm.application_status">
                    <option value="APPROVED">Approved</option>
                    <option value="DENIED">Denied</option>
                    <option value="IN PROGRESS">In Progress</option>
                    <option value="APPROVED ON EXCEPTION">Approved on Exception</option>
                    <option value="WITHDRAWN">Withdrawn</option>
                </BreezeSelect>
            </div>
<!--            <div class="col-md-4">-->
<!--                <BreezeLabel v-if="editForm.twp_status" for="inputTwpStatus" class="form-label" :value="'TWP Status (' + editForm.twp_status + ')'" />-->
<!--                <BreezeLabel v-else for="inputTwpStatus" class="form-label" value="TWP Status" />-->
<!--                <BreezeSelect class="form-select" id="inputTwpStatus" v-model="editForm.twp_status">-->
<!--                    <option value="Inactive">Inactive</option>-->
<!--                    <option value="In Progress">In Progress</option>-->
<!--                    <option value="Credential Completed">Credential Completed</option>-->
<!--                    <option value="Time in Care">Time in Care</option>-->
<!--                    <option value="No Time in Care">No Time in Care</option>-->
<!--                </BreezeSelect>-->
<!--            </div>-->
            <div class="col-md-4">
                <BreezeLabel for="inputStudentNumber" class="form-label" value="Inst. Student #" />
                <BreezeInput type="text" class="form-control" id="inputStudentNumber" v-model="editForm.institution_student_number" />
            </div>

            <div class="col-md-4">
                <div class="form-check">
                    <label for="inputConfirmEnrol" class="form-check-label">Confirmation of Enrolment Required</label>
                    <input type="checkbox" class="form-check-input" id="inputConfirmEnrol" v-model="editForm.confirmation_enrolment" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <label for="inputApplyTwp" class="form-check-label">Apply for TWP</label>
                    <input type="checkbox" class="form-check-input" id="inputApplyTwp" v-model="editForm.apply_twp" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <label for="inputApplyLfg" class="form-check-label">Apply for Learning for Future Grant</label>
                    <input type="checkbox" class="form-check-input" id="inputApplyLfg" v-model="editForm.apply_lfg" />
                </div>
            </div>

            <div class="col-md-4">
                <BreezeLabel for="inputFaoName" class="form-label" value="FAO Name" />
                <BreezeInput type="text" class="form-control" id="inputFaoName" v-model="editForm.fao_name" />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputFaoEmail" class="form-label" value="FAO Email" />
                <BreezeInput type="text" class="form-control" id="inputFaoEmail" v-model="editForm.fao_email" />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputFaoPhone" class="form-label" value="FAO Phone" />
                <BreezeInput type="text" class="form-control" id="inputFaoPhone" v-model="editForm.fao_phone" />
            </div>


            <div v-if="editForm.application_status === 'DENIED'" class="col-md-12">
                <template v-for="(reason, i) in denial_reasons">
                    <div v-if="reason.active_flag === true" class="form-check form-check-inline">
                        <input @click="updateReason(reason)" class="form-check-input" type="checkbox" :id="'inlineCheckbox'+i" v-model="reason.selected" :checked="checkReason(reason)">
                        <label @click="updateReason(reason)" class="form-check-label" :for="'inlineCheckbox'+i">{{ reason.title }}</label>
                    </div>
                </template>
            </div>

            <div v-if="editForm.exception_comments == ''
            || editForm.exception_comments === 'Location of Study'
            || editForm.exception_comments === 'Time in Care'
            || editForm.exception_comments === 'Legal Status'
            || editForm.exception_comments === 'Age'" class="col-md-12">
                <BreezeLabel for="inputExceptionComments" class="form-label" value="Exception Comment" />
                <BreezeSelect class="form-select" id="inputExceptionComments" v-model="editForm.exception_comments">
                    <option value=""></option>
                    <option value="Location of Study">Location of Study</option>
                    <option value="Time in Care">Time in Care</option>
                    <option value="Legal Status">Legal Status</option>
                    <option value="Age">Age</option>
                </BreezeSelect>
            </div>
            <div v-else class="col-md-12">
                <BreezeLabel for="inputExceptionComments" class="form-label" value="Exception Comments" />
                <BreezeInput type="text" class="form-control" id="inputExceptionComments" v-model="editForm.exception_comments" />
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
            <button v-if="result==null" type="submit" class="btn me-2 btn-outline-success" :disabled="editForm.processing">Create Application</button>
            <button v-else type="submit" class="btn me-2 btn-outline-success" :disabled="editForm.processing">Update Application</button>
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
    name: 'StudentEditTwpAppTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect, FormSubmitAlert
    },
    props: {
        result: Object,
        twpStudentId: String|Number|null,
        reasons: Object,
    },
    data() {
        return {
            noChanges: true,
            denial_reasons: null,
            editForm: useForm({
                formState: true,
                formSuccessMsg: 'Form was submitted successfully.',
                formFailMsg: 'There was an error submitting this form.',
                id: null,
                student_id: this.twpStudentId,
                received_date: '',
                application_status: '',
                twp_status: '',
                denial_reason: '',
                exception_comments: '',
                denial_list: [],
                reasons: '',

                institution_student_number: '',
                apply_twp: '',
                apply_lfg: '',
                confirmation_enrolment: '',
                fao_name: '',
                fao_email: '',
                fao_phone: '',
            }),
        }
    },
    methods: {
        checkReason: function (reason)
        {
            // if(reason.selected == null)
            //     return false;

            let is_checked = false;
            for(let i=0; i<this.editForm.reasons.length; i++){
                if(this.editForm.reasons[i].id === reason.id)
                {
                    is_checked = true;
                    break;
                }
            }
            return is_checked;
        },
        updateReason: function (reason)
        {
            if(reason.selected != null)
            {
                reason.selected = !reason.selected;
            }else{
                reason.selected = false;
            }
        },
        updateStudent: function ()
        {
            let tmpArray = [];
            if(this.denial_reasons != null){
                for(let i=0; i<this.denial_reasons.length; i++){
                    if(this.denial_reasons[i].selected !== undefined && this.denial_reasons[i].selected === true){
                        tmpArray.push(this.denial_reasons[i].id);
                    }
                }
            }

            this.editForm.reasons = tmpArray;
            let options = {
                onSuccess: () => {
                    this.editForm.formState = true;
                    this.noChanges = true;
                },
                onError: () => {
                    this.editForm.formState = false;
                },
            };
            this.editForm.formState = null;

            if(this.result == null){
                this.editForm.post('/twp/applications', options);
            }else{
                this.editForm.id = this.result.id;
                this.editForm.put('/twp/applications/' + this.result.id, options);
            }

        },

        updateData: function ()
        {
            if(this.result != null){
                // this.editForm = this.result;
                let form = JSON.parse(JSON.stringify(this.result));
                for (let prop in form) {
                    if (prop in this.editForm) {
                        this.editForm[prop] = form[prop];
                    }
                }
                this.denial_reasons = JSON.parse(JSON.stringify(this.reasons));

                for(let j=0; j<this.editForm.reasons.length; j++){
                    for(let i=0; i<this.denial_reasons.length; i++){
                        this.denial_reasons[i].selected = false;
                        if(this.denial_reasons[i].id === this.editForm.reasons[j].id){
                            this.denial_reasons[i].selected = true;
                        }
                    }
                }
            }
        }
    },
    watch: {
        result: {
            handler(newValue, oldValue) {
                this.updateData();
            },
            deep: true
        }

    },
    mounted() {
        this.updateData();
    }
}

</script>
