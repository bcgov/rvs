<style scoped>
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>
    <Head title="Schools" />

    <BreezeAuthenticatedLayout v-bind="$attrs">

        <div class="mt-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 mt-3">
                        <div class="card">
                            <div class="card-header">
                                YEAF School Search
                            </div>
                            <div class="card-body">
                                <SchoolSearchBox />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9 mt-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                YEAF Schools
                                <span v-if="filterName !== null" class="badge rounded-pill text-bg-info">{{ filterName }} <svg @click="clearFilter" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-x d-inline" viewBox="0 0 16 16">
  <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"></path>
</svg></span>

                                <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newSchoolModal">New School</button>

                            </div>
                            <div class="card-body">
                                <div v-if="results != null && results.data.length > 0" class="table-responsive pb-3">
                                    <table class="table table-striped">
                                        <thead>
                                        <SchoolsHeader></SchoolsHeader>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, i) in results.data">
                                            <td scope="row"><Link :href="'/yeaf/institutions/' + [row.id]">{{ row.name }}</Link></td>
                                            <td>{{ row.city }}</td>
                                            <td></td>
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


        <div class="modal modal-lg fade" id="newSchoolModal" tabindex="-1" aria-labelledby="newSchoolModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newSchoolModalLabel">Add New School</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form v-if="newSchoolForm != null" @submit.prevent="newSchool">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-4">
                                        <BreezeLabel for="newName" class="form-label" value="Institution Name *" />
                                        <BreezeInput type="text" class="form-control" id="newName" v-model="newSchoolForm.name" />
                                    </div>
                                    <div class="col-md-8">
                                        <BreezeLabel for="newAddress" class="form-label" value="Address *" />
                                        <BreezeInput type="text" class="form-control" id="newAddress" v-model="newSchoolForm.address" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="newCity" class="form-label" value="City *" />
                                        <BreezeInput type="text" class="form-control" id="newCity" v-model="newSchoolForm.city" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="newProvince" class="form-label" value="Prov/State" />
                                        <BreezeSelect class="form-select" id="newProvince" v-model="newSchoolForm.province">
                                            <option v-for="(province,j) in provinces" :value="province.province_code">{{ province.province_name }}</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="newPostal" class="form-label" value="Postal/Zip" />
                                        <BreezeInput type="text" class="form-control" id="newPostal" v-model="newSchoolForm.postal_code" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="newCountry" class="form-label" value="Country" />
                                        <BreezeSelect class="form-select" id="newCountry" v-model="newSchoolForm.country">
                                            <option v-for="(country,j) in countries" :value="country.country_code">{{ country.country_name }}</option>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="newTele" class="form-label" value="Telephone" />
                                        <BreezeInput type="text" class="form-control" id="newTele" v-model="newSchoolForm.tele" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel for="newFax" class="form-label" value="Fax" />
                                        <BreezeInput type="text" class="form-control" id="newFax" v-model="newSchoolForm.fax" />
                                    </div>
                                </div>

                                <div v-if="newSchoolForm.errors != undefined" class="row">
                                    <div class="col-12">
                                        <div v-if="newSchoolForm.hasErrors == true" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="err in newSchoolForm.errors"><small>{{ err }}</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="newSchoolForm.processing">Submit</button>
                        </div>
                        <FormSubmitAlert :form-state="newSchoolForm.formState" :success-msg="newSchoolForm.formSuccessMsg" :fail-msg="newSchoolForm.formFailMsg"></FormSubmitAlert>
                    </form>
                </div>
            </div>
        </div>

    </BreezeAuthenticatedLayout>

</template>
<script>
import BreezeAuthenticatedLayout from '../Layouts/Authenticated.vue';
import SchoolSearchBox from '../Components/SchoolSearch.vue';
import SchoolsHeader from '../Components/SchoolsHeader.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import BreezeInput from "../Components/Input";
import BreezeSelect from "../Components/Select";
import BreezeLabel from "../Components/Label";
import BreezePagination from "../Components/Pagination";
import FormSubmitAlert from "../Components/FormSubmitAlert";

export default {
    name: 'Schools',
    components: {
        BreezeAuthenticatedLayout, SchoolSearchBox, SchoolsHeader, Head, Link, BreezeInput, BreezeSelect, BreezeLabel, BreezePagination, FormSubmitAlert
    },
    props: {
        results: Object,
        countries: Object,
        provinces: Object,
        filterName: String | null,
    },
    data() {
        return {
            newSchoolForm: null,
            newSchoolFormData: {
                name: '', address: '', city: '', province: '', postal_code: '', country: '', tele: '', fax: '', formState: true,
                formSuccessMsg: 'Form was submitted successfully.',
                formFailMsg: 'There was an error submitting this form.',
            },
        }
    },
    methods: {
        clearFilter: function (){
            window.location.href = "/yeaf/institutions";
        },
        newSchool: function ()
        {
            this.newSchoolForm.formState = '';
            this.newSchoolForm.post('/yeaf/institutions', {
                onSuccess: (response) => {
                    $("#newSchoolModal").modal('hide');
                    this.newSchoolForm.reset(this.newSchoolFormData);
                    this.$inertia.visit('institutions/' + response.props.school.id);
                },
                onFailure: () => {
                },
                onError: () => {
                    this.newSchoolForm.formState = false;
                },
                preserveState: true,
                preserveScroll: true,
            });
        },

    },
    mounted() {
        this.newSchoolForm = useForm(this.newSchoolFormData);
    }
}
</script>
