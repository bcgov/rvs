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

            <div class="col-md-6">
                <BreezeLabel for="inputEmail" class="form-label" value="Email" />
                <BreezeInput type="email" class="form-control" id="inputEmail" v-model="editForm.email" />
            </div>
            <div class="col-md-6">
                <BreezeLabel for="inputPhoneNumber" class="form-label" value="Phone Number" />
                <BreezeInput type="text" class="form-control" id="inputPhoneNumber" v-model="editForm.phone_number" />
            </div>

            <div class="col-md-4">
                <BreezeLabel for="inputSin" class="form-label" value="SIN" />
                <BreezeInput type="number" class="form-control" id="inputSin" v-model="editForm.sin" />
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

            <div class="col-md-12">
                <BreezeLabel for="inputAddress1" class="form-label" value="Address 1" />
                <BreezeInput type="text" class="form-control" id="inputAddress1" v-model="editForm.address1" />
            </div>
            <div class="col-md-12">
                <BreezeLabel for="inputAddress2" class="form-label" value="Address 2" />
                <BreezeInput type="text" class="form-control" id="inputAddress2" v-model="editForm.address2" />
            </div>
            <div class="col-md-6">
                <BreezeLabel for="inputCountry" class="form-label" value="Country" />
                <BreezeInput type="text" class="form-control" id="inputCountry" v-model="editForm.country" />
            </div>
            <div class="col-md-6">
                <BreezeLabel for="inputProvince" class="form-label" value="Province" />
                <BreezeInput type="text" class="form-control" id="inputProvince" v-model="editForm.province" />
            </div>
            <div class="col-md-6">
                <BreezeLabel for="inputCity" class="form-label" value="City" />
                <BreezeInput type="text" class="form-control" id="inputCity" v-model="editForm.city" />
            </div>
            <div class="col-md-6">
                <BreezeLabel for="inputPostalCode" class="form-label" value="Postal Code" />
                <BreezeInput type="text" class="form-control" id="inputPostalCode" v-model="editForm.postal_code" />
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
        provinces: Object
    },
    data() {
        return {
            noChanges: true,
            editForm: null
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
                comment: this.editForm.comment,

                address1: this.editForm.address1,
                address2: this.editForm.address2,
                country: this.editForm.country,
                province: this.editForm.province,
                city: this.editForm.city,
                postal_code: this.editForm.postal_code,
                phone_number: this.editForm.phone_number,

            });

            this.editForm.formState = '';
            this.editForm.put('/plsc/students/' + this.result.id, {
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
    }
}

</script>
