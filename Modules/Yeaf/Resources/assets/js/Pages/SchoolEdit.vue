
<template>
        <Head title="YEAF - School Edit" />

        <BreezeAuthenticatedLayout v-bind="$attrs">

            <div class="mt-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    YEAF School Search
                                </div>
                                <div class="card-body">
                                    <SchoolSearchBox />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 mt-3 mb-5">

                            <div class="card">
                                <div v-if="result != null" class="card-header">
                                    YEAF Edit School
                                </div>
                                <div class="card-body">

                                    <form v-if="editForm != null" @submit.prevent="updateSchool">
                                        <div class="row g-3">

                                            <div class="col-md-4">
                                                <BreezeLabel for="inputInstitutionName" class="form-label" value="Institution Name" />
                                                <BreezeInput aria-readonly="true" type="text" class="form-control" id="inputInstitutionName" v-model="editForm.name" />
                                            </div>

                                            <div class="col-md-8">
                                                <BreezeLabel for="inputAddress" class="form-label" value="Address" />
                                                <BreezeInput type="text" class="form-control" id="inputAddress" v-model="editForm.address" />
                                            </div>
                                            <div class="col-md-4">
                                                <BreezeLabel for="inputCity" class="form-label" value="City" />
                                                <BreezeInput type="text" class="form-control" id="inputCity" v-model="editForm.city" />
                                            </div>

                                            <div class="col-md-4">
                                                <BreezeLabel for="inputProvince" class="form-label" value="Prov/State" />
                                                <BreezeSelect class="form-select" id="inputProvince" v-model="editForm.province">
                                                    <option v-for="(province,j) in provinces" :value="province.province_code">{{ province.province_name }}</option>
                                                </BreezeSelect>
                                            </div>
                                            <div class="col-md-4">
                                                <BreezeLabel for="inputPostal" class="form-label" value="Postal/Zip" />
                                                <BreezeInput type="text" class="form-control" id="inputPostal" maxlength="7" v-model="editForm.postal_code" />
                                            </div>
                                            <div class="col-md-4">
                                                <BreezeLabel for="inputCountry" class="form-label" value="Country" />
                                                <BreezeSelect class="form-select" id="inputCountry" v-model="editForm.country">
                                                    <option v-for="(country,j) in countries" :value="country.country_code">{{ country.country_name }}</option>
                                                </BreezeSelect>
                                            </div>

                                            <div class="col-md-4">
                                                <BreezeLabel for="inputTele" class="form-label" value="Telephone" />
                                                <BreezeInput @keyup="formatPhoneNumber" type="text" class="form-control" id="inputTele" maxlength="13" v-model="editForm.tele" />
                                            </div>
                                            <div class="col-md-4">
                                                <BreezeLabel for="inputFax" class="form-label" value="Fax" />
                                                <BreezeInput @keyup="formatPhoneNumber" type="text" class="form-control" id="inputFax" maxlength="13" v-model="editForm.fax" />
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
                                            <button type="submit" class="btn me-2 btn-outline-success float-end" :disabled="editForm.processing">Update School</button>
                                            <Link @click="back" class="btn btn-outline-primary" href="#">Back</Link>
                                        </div>

                                        <FormSubmitAlert :form-state="editForm.formState"
                                                         :success-msg="'School record was updated successfully.'"></FormSubmitAlert>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>



        </BreezeAuthenticatedLayout>

</template>
<script>
import {computed} from "vue";

import BreezeAuthenticatedLayout from '../Layouts/Authenticated.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import SchoolSearchBox from '../Components/SchoolSearch.vue';
import BreezeInput from '../Components/Input.vue';
import BreezeLabel from '../Components/Label.vue';
import BreezeSelect from "../Components/Select";
import FormSubmitAlert from "../Components/FormSubmitAlert";

export default {
    name: 'SchoolEdit',
    components: {
        BreezeAuthenticatedLayout, SchoolSearchBox, Head, BreezeInput, BreezeLabel, Link, BreezeSelect, useForm, FormSubmitAlert
    },
    props: {
        result: Object,
        now: String,
        countries: Object,
        provinces: Object,

    },
    data() {
        return {
            editForm: null,
        }
    },
    methods: {
        back: function()
        {
            window.history.back();
        },
        formatPhoneNumber: function() {
            let cleaned = ('' + this.editForm.tele).replace(/\D/g, '');
            let match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
            if (match) {
                this.editForm.tele = '(' + match[1] + ') ' + match[2] + '-' + match[3];
            }
        },
        updateSchool: function ()
        {
            this.editForm = useForm({
                id: this.result.id,

                institution_id: this.editForm.institution_id,
                name: this.editForm.name,
                address: this.editForm.address,
                city: this.editForm.city,
                province: this.editForm.province,
                postal_code: this.editForm.postal_code,
                country: this.editForm.country,
                tele: this.editForm.tele,
                fax: this.editForm.fax,

            });

            this.editForm.formState = '';
            this.editForm.put('/yeaf/institutions/' + this.result.id, {
                onSuccess: () => {
                    this.editForm.formState = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.editForm.formState = false;
                },
                preserveState: true,
            });
        },
    },
    mounted() {
        this.editForm = this.result;
    }
}
</script>
