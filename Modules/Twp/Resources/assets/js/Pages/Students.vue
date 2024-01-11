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
                                TWP Students Search
                            </div>
                            <div class="card-body">
                                <StudentSearchBox :schools="schools" page="/twp/students/" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 mt-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                TWP Students
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
                                            <td><Link :href="'/twp/students/' + row.id">{{ row.last_name }}</Link></td>
                                            <td>{{ row.first_name}}</td>
                                            <td>{{ row.birth_date}}</td>
                                            <td>{{ row.pen}}</td>
                                            <td>{{ row.sin}}</td>
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
                    <form data-test="true" v-if="newStudentForm != null" @submit.prevent="newStudent" autocomplete="off">
                        <div class="modal-body">
                            <div class="card-body">

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <BreezeLabel for="inputLastName" class="form-label" value="Last Name" />
                                        <BreezeInput type="text" class="form-control" id="inputLastName" v-model="newStudentForm.last_name" />
                                    </div>
                                    <div class="col-md-6">
                                        <BreezeLabel for="inputFirstName" class="form-label" value="First Name" />
                                        <BreezeInput type="text" class="form-control" id="inputFirstName" v-model="newStudentForm.first_name" />
                                    </div>
                                    <div class="col-md-6">
                                        <BreezeLabel for="inputEmail" class="form-label" value="Email" />
                                        <BreezeInput type="email" class="form-control" id="inputEmail" v-model="newStudentForm.email" />
                                    </div>

                                    <div class="col-md-6">
                                        <BreezeLabel for="inputBirth" class="form-label" value="Birth Date" />
                                        <BreezeInput type="date" max="2040-12-31" placeholder="YYYY-MM-DD" class="form-control" id="inputBirth" v-model="newStudentForm.birth_date" />
                                    </div>

                                    <div class="col-md-4">
                                        <BreezeLabel for="inputSin" class="form-label" value="SIN" />
                                        <BreezeInput type="number" class="form-control" id="inputSin" v-model="newStudentForm.sin" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="inputPen" class="form-label" value="PEN" />
                                        <BreezeInput type="text" class="form-control" id="inputPen" v-model="newStudentForm.pen" />
                                    </div>

                                    <div class="col-md-4">
                                        <BreezeLabel for="inputCitizenship" class="form-label" value="Citizenship" />
                                        <BreezeSelect class="form-select" id="inputCitizenship" v-model="newStudentForm.citizenship">
                                            <option value="Canadian Citizen">Canadian Citizen</option>
                                            <option value="Permanent Resident">Permanent Resident</option>
                                            <option value="Protected Person">Protected Person</option>
                                            <option value="Other">Other</option>
                                        </BreezeSelect>
                                    </div>

                                    <div class="col-md-12">
                                        <BreezeLabel for="inputAddress" class="form-label" value="Address" />
                                        <BreezeInput type="text" class="form-control" id="inputAddress" v-model="newStudentForm.address" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="inputBCResident" class="form-label" value="BC Resident" />
                                        <BreezeSelect class="form-select" id="inputBCResident" v-model="newStudentForm.bc_resident">
                                            <option value="true">Yes</option>
                                            <option value="false">No</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="inputIndigeneity" class="form-label" value="Indigeneity" />
                                        <BreezeSelect class="form-select" id="inputIndigeneity" v-model="newStudentForm.indigeneity">
                                            <option value="No">No</option>
                                            <option value="First Nations">First Nations</option>
                                            <option value="Metis">Metis</option>
                                            <option value="Inuit">Inuit</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="inputGender" class="form-label" value="Gender" />
                                        <BreezeSelect class="form-select" id="inputGender" v-model="newStudentForm.gender">
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                            <option value="O">Other</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-md-12">
                                        <BreezeLabel for="inputComment" class="form-label" value="Comment" />
                                        <textarea rows="4" class="form-control" id="inputComment" v-model="newStudentForm.comment"></textarea>
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
import BreezeInput from "@/Components/Input";
import BreezeSelect from "@/Components/Select";
import BreezeLabel from "@/Components/Label";
import BreezePagination from "@/Components/Pagination";
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'Students',
    components: {
        BreezeAuthenticatedLayout, StudentSearchBox, StudentsHeader, Head, Link, BreezeInput, BreezeSelect, BreezeLabel, BreezePagination, FormSubmitAlert
    },
    props: {
        results: Object,
        countries: Object,
        provinces: Object,
        schools: Object,
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
                birth_date: '',
                email: '',
                gender: '',
                pen: '',
                sin: '',
                address: '',
                institution_student_number: '',
                citizenship: '',
                bc_resident: '',
                indigeneity: '',
                comment: '',

            },
        }
    },
    methods: {
        newStudent: function ()
        {
            this.newStudentForm.formState = '';
            this.newStudentForm.post('/twp/students', {
                onSuccess: (response) => {
                    $("#newStudentModal").modal('hide');
                    this.newStudentForm.reset(this.newStudentFormData);
                    this.$inertia.visit('/twp/students/' + response.props.student.id);
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
        //let vm = this;
        //setTimeout(function (){ vm.generateTestValues(); }, 3000);
    }
}
</script>
