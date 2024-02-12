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

            <div class="col-md-6">
                <BreezeLabel for="inputLastName" class="form-label" value="Last Name" />
                <BreezeInput type="text" class="form-control" id="inputLastName" v-model="editForm.last_name" />
            </div>
            <div class="col-md-6">
                <BreezeLabel for="inputFirstName" class="form-label" value="First Name" />
                <BreezeInput type="text" class="form-control" id="inputFirstName" v-model="editForm.first_name" />
            </div>

            <div class="col-md-4">
                <BreezeLabel for="inputEmail" class="form-label" value="Email" />
                <BreezeInput type="email" class="form-control" id="inputEmail" v-model="editForm.email" />
            </div>


            <div class="col-md-4">
                <BreezeLabel for="inputBirth" class="form-label" value="Birth Date" />
                <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputBirth" v-model="editForm.birth_date" />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputGender" class="form-label" value="Gender" />
                <BreezeSelect class="form-select" id="inputGender" v-model="editForm.gender">
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                    <option value="O">Other</option>
                </BreezeSelect>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputPen" class="form-label" value="PEN" />
                <BreezeInput type="text" class="form-control" id="inputPen" v-model="editForm.pen" />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputSin" class="form-label" value="SIN" />
                <BreezeInput type="number" class="form-control" id="inputSin" v-model="editForm.sin" />
            </div>

            <div class="col-md-4">
                <BreezeLabel for="inputCitizenship" class="form-label" value="Citizenship" />
                <BreezeSelect class="form-select" id="inputCitizenship" v-model="editForm.citizenship">
                    <option value="Canadian Citizen">Canadian Citizen</option>
                    <option value="Permanent Resident">Permanent Resident</option>
                    <option value="Protected Person">Protected Person</option>
                    <option value="Other">Other</option>
                </BreezeSelect>
            </div>
            <div class="col-md-12">
                <BreezeLabel for="inputAddress" class="form-label" value="Address" />
                <BreezeInput type="text" class="form-control" id="inputAddress" v-model="editForm.address" />
            </div>
            <div class="col-md-6">
                <BreezeLabel for="inputBCResident" class="form-label" value="BC Resident" />
                <BreezeSelect class="form-select" id="inputBCResident" v-model="editForm.bc_resident">
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </BreezeSelect>
            </div>

            <div class="col-md-6">
                <BreezeLabel for="inputAge" class="form-label" value="Age" />
                <BreezeInput type="number" class="form-control bg-light" id="inputAge" v-model="editForm.age" readonly="readonly" disabled />
            </div>

            <div class="col-md-12">
                <BreezeLabel for="indigeneity" class="form-label" value="Indigeneity" />
                <div v-for="type in indigeneity_types" :key="type.id" class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" :id="type.id" :value="type.id" v-model="indigeneity_data" />
                    <label class="form-check-label" :for="type.id">{{ type.title }}</label>
                </div>
            </div>

            <div class="col-md-12">
                <BreezeLabel for="inputComment" class="form-label" value="Comment" />
                <textarea rows="4" class="form-control" id="inputComment" v-model="editForm.comment"></textarea>
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
            <button type="submit" class="btn me-2 btn-outline-success" :disabled="editForm.processing">Update Student</button>
<!--            <Link @click="back" class="btn btn-outline-primary float-end" href="#">Back</Link>-->
        </div>

        <FormSubmitAlert :form-state="editForm.formState"
                         :success-msg="'Student record was updated successfully.'"></FormSubmitAlert>

    </form>

</template>
<script>
import {Link, useForm} from '@inertiajs/vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'StudentEditStudentTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect, FormSubmitAlert
    },
    props: {
        result: Object,
        now: String,
        countries: Object,
        provinces: Object,
        indigeneity_types: Object

    },
    data() {
        return {
            noChanges: true,
            editForm: null,
            indigeneity_data: []
        }
    },
    methods: {
        updateStudent: function ()
        {
            this.editForm = useForm({
                id: this.editForm.id,
                last_name: this.editForm.last_name,
                first_name: this.editForm.first_name,
                birth_date: this.editForm.birth_date,
                email: this.editForm.email,
                gender: this.editForm.gender,
                pen: this.editForm.pen,
                sin: this.editForm.sin,
                age: this.editForm.age,
                address: this.editForm.address,
                citizenship: this.editForm.citizenship,
                bc_resident: this.editForm.bc_resident,
                indigeneity: this.indigeneity_data,
                comment: this.editForm.comment,
            });

            this.editForm.formState = '';
            this.editForm.put('/twp/students/' + this.result.id, {
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
        this.editForm = this.result;
        this.indigeneity_data = this.result.indigeneity.map((i) => i.id)
    }
}

</script>
