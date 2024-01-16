<style scoped>
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>
    <Head title="Students" />

    <BreezeAuthenticatedLayout v-bind="$attrs">

        <div class="mt-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 mt-3">
                        <div class="card">
                            <div class="card-header">
                                YEAF Students Search
                            </div>
                            <div class="card-body">
                                <StudentSearchBox />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 mt-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                YEAF Students
                                <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newStudentModal">New Student</button>
                            </div>
                            <div class="card-body">
                                <div v-if="results != null && results.data.length > 0" class="table-responsive pb-3">
                                    <table class="table table-striped">
                                        <thead>
                                        <StudentsHeader></StudentsHeader>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, i) in results.data">
                                            <td scope="row"><Link :href="'/yeaf/students/' + row.id">{{ row.sin }}</Link></td>
                                            <td>{{ row.first_name }}</td>
                                            <td>{{ row.last_name}}</td>
                                            <td>{{ formatMoney(row.life) }}</td>
                                            <td>
                                                <span v-if="(row.overaward_amount - row.overaward_deducted_amount) < 0" class="badge rounded-pill text-bg-danger fs-6">{{ countOveraward(row.overaward_amount, row.overaward_deducted_amount) }}</span>
                                                <span v-else>{{ countOveraward(row.overaward_amount, row.overaward_deducted_amount) }}</span>
                                            </td>
                                            <td>
                                                <span v-if="row.investigate" class="badge rounded-pill text-bg-danger fs-6">Yes</span>
                                                <span v-else class="badge rounded-pill text-bg-success fs-6">No</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <BreezePagination :links="results.links" :active-page="results.current_page" />
                                </div>
                                <h1 v-else class="lead">No results</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal modal-lg fade" id="newStudentModal" tabindex="-1" aria-labelledby="newStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newStudentModalLabel">Add New Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form data-test="true" v-if="newStudentForm != null" @submit.prevent="newStudent">
                        <div class="modal-body">
                            <div class="card-body">


                                <div class="row g-3">

                                    <div class="col-md-4">
                                        <BreezeLabel for="inputSin" class="form-label" value="SIN" />
                                        <BreezeInput type="text" class="form-control" id="inputSin" v-model="newStudentForm.sin" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="inputLastName" class="form-label" value="Last Name" />
                                        <BreezeInput type="text" class="form-control" id="inputLastName" v-model="newStudentForm.last_name" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="inputFirstName" class="form-label" value="First Name" />
                                        <BreezeInput type="text" class="form-control" id="inputFirstName" v-model="newStudentForm.first_name" />
                                    </div>

                                    <div class="col-md-8">
                                        <BreezeLabel for="inputAddress" class="form-label" value="Address" />
                                        <BreezeInput type="text" class="form-control" id="inputAddress" v-model="newStudentForm.address" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="inputCity" class="form-label" value="City" />
                                        <BreezeInput type="text" class="form-control" id="inputCity" v-model="newStudentForm.city" />
                                    </div>

                                    <div class="col-md-4">
                                        <BreezeLabel for="inputProvince" class="form-label" value="Prov/State" />
                                        <BreezeSelect class="form-select" id="inputProvince" v-model="newStudentForm.province">
                                            <option v-for="(province,j) in provinces" :value="province.province_code">{{ province.province_name }}</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="inputPostal" class="form-label" value="Postal/Zip" />
                                        <BreezeInput type="text" class="form-control" id="inputPostal" maxlength="7" v-model="newStudentForm.postal_code" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="inputCountry" class="form-label" value="Country" />
                                        <BreezeSelect class="form-select" id="inputCountry" v-model="newStudentForm.country">
                                            <option v-for="(country,j) in countries" :value="country.country_code">{{ country.country_name }}</option>
                                        </BreezeSelect>
                                    </div>

                                    <div class="col-md-3">
                                        <BreezeLabel for="inputTele" class="form-label" value="Telephone" />
                                        <BreezeInput @keyup="formatPhoneNumber" type="text" class="form-control" id="inputTele" maxlength="13" v-model="newStudentForm.tele" />
                                    </div>
                                    <div class="col-md-3">
                                        <BreezeLabel for="inputEmail" class="form-label" value="Email" />
                                        <BreezeInput type="email" class="form-control" id="inputEmail" v-model="newStudentForm.email" />
                                    </div>
                                    <div class="col-md-3">
                                        <BreezeLabel for="inputBirth" class="form-label" value="Birth Date" />
                                        <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputBirth" v-model="newStudentForm.birth_date" />
                                    </div>
                                    <div class="col-md-3">
                                        <BreezeLabel for="inputGender" class="form-label" value="Gender" />
                                        <BreezeSelect class="form-select" id="inputGender" v-model="newStudentForm.gender">
                                            <option></option>
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                        </BreezeSelect>
                                    </div>

                                    <div class="col-md-6">
                                        <BreezeLabel for="inputInstStudentNumber" class="form-label" value="Institution Student #" />
                                        <BreezeInput type="text" class="form-control" id="inputInstStudentNumber" v-model="newStudentForm.institution_student_number" />
                                    </div>
                                    <div class="col-md-6">
                                        <BreezeLabel for="inputPend" class="form-label" value="PEN" />
                                        <BreezeInput type="text" class="form-control" id="inputPend" v-model="newStudentForm.pen" />
                                    </div>


                                    <div v-if="newStudentForm.errors != undefined" class="row">
                                        <div class="col-12">
                                            <div v-if="newStudentForm.hasErrors == true" class="alert alert-danger mt-3">
                                                <ul>
                                                    <li v-for="err in newStudentForm.errors">{{ err }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>


                                </div>




                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="newStudentForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="newStudentForm.formState" :success-msg="newStudentForm.formSuccessMsg" :fail-msg="newStudentForm.formFailMsg"></FormSubmitAlert>
                    </form>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>

</template>
<script>
import BreezeAuthenticatedLayout from '../Layouts/Authenticated.vue';
import StudentSearchBox from '../Components/StudentSearch.vue';
import StudentsHeader from '../Components/StudentsHeader.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import BreezeInput from "../Components/Input";
import BreezeSelect from "../Components/Select";
import BreezeLabel from "../Components/Label";
import BreezePagination from "../Components/Pagination";
import FormSubmitAlert from "../Components/FormSubmitAlert";

export default {
    name: 'Students',
    components: {
        BreezeAuthenticatedLayout, StudentSearchBox, StudentsHeader, Head, Link, BreezeInput, BreezeSelect, BreezeLabel, BreezePagination, FormSubmitAlert
    },
    props: {
        results: Object,
        countries: Object,
        provinces: Object,

    },
    data() {
        return {
            newStudentForm: null,
            newStudentFormData: {
                formState: true,
                formSuccessMsg: 'Form was submitted successfully.',
                formFailMsg: 'There was an error submitting this form.',

                last_name: '',
                first_name: '',
                sin: '',
                birth_date: '',
                address: '',
                city: '',
                province: '',
                postal_code: '',
                country: '',
                tele: '',
                email: '',
                gender: '',
                pen: '',
                institution_student_number: '',
            },

        }
    },
    methods: {
        countOveraward: function(a, b){
            let c = a - b;
            return this.formatMoney(c);
        },
        formatMoney: function (a){
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(a);
        },
        formatPhoneNumber: function() {
            let cleaned = ('' + this.newStudentForm.tele).replace(/\D/g, '');
            let match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
            if (match) {
                this.newStudentForm.tele = '(' + match[1] + ') ' + match[2] + '-' + match[3];
            }
        },
        newStudent: function ()
        {
            this.newStudentForm.formState = '';
            this.newStudentForm.post('/yeaf/students', {
                onSuccess: (response) => {
                    $("#newStudentModal").modal('hide');
                    this.newStudentForm.reset(this.newStudentFormData);
                    this.$inertia.visit('/yeaf/students/' + response.props.student.id);
                },
                onFailure: () => {
                },
                onError: () => {
                    this.newStudentForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },
    },
    mounted() {
        this.newStudentForm = useForm(this.newStudentFormData);
        // let vm = this;
        // setTimeout(function (){ vm.generateTestValues(); }, 3000);
    }
}
</script>
