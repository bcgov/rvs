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
    <div>
        <form v-if="editForm != null" @submit.prevent="updateStudent">
            <div class="row g-3">
                <div class="col-md-6">
                    <BreezeLabel for="inputInstitutionName" class="form-label" value="Institution Name" />
                    <BreezeSelect @change="assignInstitution($event)" class="form-select" id="inputInstitutionName" v-model="editForm.institution_name">
                        <template v-for="school in schools">
                            <option v-if="school.active_flag === true" :value="school.name">{{ school.name }}</option>
                        </template>
                    </BreezeSelect>
                </div>
                <div class="col-md-6">
                    <BreezeLabel for="inputCredential" class="form-label" value="Program/Credential Name" />
                    <BreezeInput type="text" class="form-control" id="inputCredential" v-model="editForm.credential" />
                </div>
                <div class="col-md-4">
                    <BreezeLabel for="inputCredentialType" class="form-label" value="Credential Level" />
                    <BreezeSelect class="form-select" id="inputCredentialType" v-model="editForm.credential_type">
                        <option v-for="credential in $attrs.utils['Credential Level']" :key="credential.id" :value="credential.field_name">
                            {{ credential.field_name }}
                        </option>
                    </BreezeSelect>
                </div>
                <div class="col-md-4">
                    <BreezeLabel for="inputStudyField" class="form-label" value="Field of Study" />
                    <BreezeSelect class="form-select" id="inputStudyField" v-model="editForm.study_field">
                        <option v-for="study in $attrs.utils['Field of Study']" :key="study.id" :value="study.field_name">
                            {{ study.field_name }}
                        </option>
                    </BreezeSelect>
                </div>
                <div class="col-md-4">
                    <BreezeLabel for="inputStudentStatus" class="form-label" value="Student Status" />
                    <BreezeSelect class="form-select" id="inputStudentStatus" v-model="editForm.student_status">
                        <option v-for="status in $attrs.utils['Student Status']" :key="status.id" :value="status.field_name">
                            {{ status.field_name }}
                        </option>
                    </BreezeSelect>
                </div>

                <div class="col-md-3">
                    <BreezeLabel for="inputStudyStartDate" class="form-label" value="Study Start Date" />
                    <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputStudyStartDate" v-model="editForm.study_period_start_date" />
                </div>
                <div class="col-md-3">
                    <BreezeLabel for="inputProgramLength" class="form-label" value="Program Length" />
                    <BreezeInput type="number" class="form-control" id="inputCredential" v-model="editForm.program_length" />
                </div>
                <div class="col-md-3">
                    <BreezeLabel for="inputLengthType" class="form-label" value="Program Length Type" />
                    <BreezeSelect class="form-select" id="inputLengthType" v-model="editForm.program_length_type">
                        <option v-for="length in $attrs.utils['Program Length Type']" :key="length.id" :value="length.field_name.toLowerCase()">
                            {{ length.field_name }}
                        </option>
                    </BreezeSelect>
                </div>
                <div class="col-md-3">
                    <BreezeLabel for="inputEstimatedCost" class="form-label" value="Estimated Cost" />
                    <div class="input-group">
                        <div class="input-group-text">$</div>
                        <input type="text" class="form-control" id="inputEstimatedCost" v-model="editForm.total_estimated_cost">
                    </div>
                </div>

                <div class="col-md-12">
                    <BreezeLabel for="inputProgramComments" class="form-label" value="Comments" />
                    <textarea class="form-control" id="inputProgramComments" v-model="editForm.comments" rows="5">{{ editForm.comments }}</textarea>
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
                <button v-if="result==null" type="submit" class="btn me-2 btn-outline-success" :disabled="editForm.processing">Create Program</button>
                <button v-else type="submit" class="btn me-2 btn-outline-success" :disabled="editForm.processing">Update Program</button>
            </div>

            <FormSubmitAlert :form-state="editForm.formState"
                             :success-msg="'Program record was submitted successfully.'"></FormSubmitAlert>

        </form>

        <hr/>
        <div v-if="result != null && result.versions != null && result.versions.length > 0" class="card">
            <div class="card-header">History</div>
            <div class="card-body">
                <div class="accordion" id="accordionHistory">
                    <div v-for="(version, i) in result.versions" class="accordion-item">
                        <h2 class="accordion-header" :id="'heading'+i">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse'+i" aria-expanded="true" :aria-controls="'collapse'+i">
                                {{ formatDate(version.created_at) }}
                            </button>
                        </h2>
                        <div :id="'collapse'+i" class="accordion-collapse collapse" :aria-labelledby="'heading'+i" data-bs-parent="#accordionHistory">
                            <div class="accordion-body">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <BreezeLabel :for="'inputInstitutionName'+i" class="form-label" value="Institution Name" />
                                        <BreezeInput readonly disabled type="text" class="form-control" :id="'inputInstitutionName'+i" :value="version.institution_name" />
                                    </div>
                                    <div class="col-md-6">
                                        <BreezeLabel :for="'inputCredential'+i" class="form-label" value="Program/Credential Name" />
                                        <BreezeInput readonly disabled type="text" class="form-control" :id="'inputCredential'+i" :value="version.credential" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel :for="'inputCredentialType'+i" class="form-label" value="Credential Level" />
                                        <BreezeInput readonly disabled type="text" class="form-control" :id="'inputCredentialType'+i" :value="version.credential_type" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel :for="'inputStudyField'+i" class="form-label" value="Field of Study" />
                                        <BreezeInput readonly disabled type="text" class="form-control" :id="'inputStudyField'+i" :value="version.study_field" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel :for="'inputStudentStatus'+i" class="form-label" value="Student Status" />
                                        <BreezeInput readonly disabled type="text" class="form-control" :id="'inputStudentStatus'+i" :value="version.student_status" />
                                    </div>

                                    <div class="col-md-3">
                                        <BreezeLabel :for="'inputStudyStartDate'+i" class="form-label" value="Study Start Date" />
                                        <BreezeInput readonly disabled type="text" class="form-control" :id="'inputStudyStartDate'+i" :value="version.study_period_start_date" />
                                    </div>
                                    <div class="col-md-3">
                                        <BreezeLabel :for="'inputProgramLength'+i" class="form-label" value="Program Length" />
                                        <BreezeInput readonly disabled type="text" class="form-control" :id="'inputProgramLength'+i" :value="version.program_length" />
                                    </div>
                                    <div class="col-md-3">
                                        <BreezeLabel :for="'inputLengthType'+i" class="form-label" value="Program Length Type" />
                                        <BreezeInput readonly disabled type="text" class="form-control" :id="'inputLengthType'+i" :value="version.program_length_type" />
                                    </div>
                                    <div class="col-md-3">
                                        <BreezeLabel :for="'inputEstimatedCost'+i" class="form-label" value="Estimated Cost" />
                                        <div class="input-group">
                                            <div class="input-group-text">$</div>
                                            <input readonly disabled type="text" class="form-control" :id="'inputEstimatedCost'+i" :value="version.total_estimated_cost">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <BreezeLabel :for="'inputProgramComments'+i" class="form-label" value="Comments" />
                                        <textarea readonly disabled class="form-control" :id="'inputProgramComments'+i" rows="5">{{ version.comments }}</textarea>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

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
    name: 'StudentEditProgramTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect, FormSubmitAlert
    },
    props: {
        result: Object,
        schools: Object,
        twpStudentId: String|Number|null,
        twpApplicationId: String|Number|null,
    },
    data() {
        return {
            editForm: useForm({
                formSuccessMsg: 'Form was submitted successfully.',
                formFailMsg: 'There was an error submitting this form.',
                id: null,
                student_id: this.twpStudentId,
                application_id: this.twpApplicationId,

                institution_name: '',
                institution_twp_id: null,
                study_period_start_date: '',
                credential: '',
                credential_type: '',
                study_field: '',
                program_length: '',
                program_length_type: '',
                total_estimated_cost: '',
                student_status: '',
                comments: '',
            }),
        }
    },
    methods: {
        formatDate: function (value) {
            if(value !== undefined && value !== ''){
                let date = value.split("T");
                let time = date[1].split(".");

                return date[0] + " " + time[0];
            }
            return value;
        },
        assignInstitution: function (e) {
            for(let i=0; i<this.schools.length; i++){
                if(this.schools[i].name === e.target.value){
                    this.editForm.institution_twp_id = this.schools[i].id;
                    break;
                }
            }
        },
        updateStudent: function ()
        {
            this.editForm.formState = '';
            let options = {
                onSuccess: () => {
                    this.editForm.formState = true;
                },
                onError: () => {
                    this.editForm.formState = false;
                },
            };

            if(this.result == null){
                this.editForm.post('/twp/programs', options);
            }else{
                this.editForm.id = this.result.id;
                this.editForm.put('/twp/programs/' + this.result.id, options);
            }

        },

    },
    mounted() {
        if(this.result != null){
            this.editForm = useForm(this.result);
        }
    }
}

</script>
