<template>
    <div class="card mb-4">
        <div class="card-header">Ministry</div>

        <form v-if="results != null && editForm != null" @submit.prevent="editArea">

            <div class="card-body">


                <div class="card-header mb-3">
                    <div class="row mb-3">
                        <div class="col-md-3 ">
                            <strong>Branch</strong>
                            <p>Ministry branch information.</p>
                        </div>
                        <div class="col-md-9">
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <BreezeLabel for="ministry" value="Ministry Name"/>
                                    <BreezeInput id="ministry" class="form-control" type="text"
                                                 v-model="editForm.ministry" :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-6">
                                    <BreezeLabel for="ministry_branch" value="Ministry Branch"/>
                                    <BreezeInput id="ministry_branch" class="form-control" type="text"
                                                 v-model="editForm.ministry_branch" :disabled="editForm.processing"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <BreezeLabel for="ministry_address" value="Address"/>
                                    <BreezeInput id="ministry_address" class="form-control" type="text"
                                                 v-model="editForm.ministry_address" :disabled="editForm.processing"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <BreezeLabel for="ministry_city" value="City"/>
                                    <BreezeInput id="ministry_city" class="form-control" type="text"
                                                 v-model="editForm.ministry_city" :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-4">
                                    <BreezeLabel for="ministry_province" value="Province"/>
                                    <BreezeSelect id="ministry_province" class="form-control"
                                                  v-model="editForm.ministry_prov" :disabled="editForm.processing">
                                        <option v-for="province in provinces" :value="province.province_code">
                                            {{ province.province_code }}
                                        </option>
                                    </BreezeSelect>
                                </div>
                                <div class="col-lg-4">
                                    <BreezeLabel for="ministry_postal" value="Postal Code"/>
                                    <BreezeInput id="ministry_postal" class="form-control" type="text"
                                                 v-model="editForm.ministry_postal" :disabled="editForm.processing"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <BreezeLabel for="ministry_tele_victoria" value="Tel - Victoria"/>
                                    <BreezeInput id="ministry_tele_victoria" class="form-control" type="text"
                                                 v-model="editForm.ministry_tele_victoria"
                                                 :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-3">
                                    <BreezeLabel for="ministry_tele_lower" value="Tele - Lower Mainland"/>
                                    <BreezeInput id="ministry_tele_lower" class="form-control" type="text"
                                                 v-model="editForm.ministry_tele_lower"
                                                 :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-3">
                                    <BreezeLabel for="ministry_tele_toll_free" value="Tele - Toll-Free"/>
                                    <BreezeInput id="ministry_tele_toll_free" class="form-control" type="text"
                                                 v-model="editForm.ministry_tele_toll_free"
                                                 :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-3">
                                    <BreezeLabel for="ministry_TTY_line" value="TTY Line"/>
                                    <BreezeInput id="ministry_TTY_line" class="form-control" type="text"
                                                 v-model="editForm.ministry_TTY_line" :disabled="editForm.processing"/>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end of section -->

                <div class="card-header mb-3">

                    <div class="row mb-3">
                        <div class="col-md-3 ">
                            <strong>Location</strong>
                            <p>Ministry location information.</p>
                        </div>
                        <div class="col-md-9">
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <BreezeLabel for="ministry_location" value="Location Address"/>
                                    <BreezeInput id="ministry_location" class="form-control" type="text"
                                                 v-model="editForm.ministry_location" :disabled="editForm.processing"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <BreezeLabel for="ministry_location_city" value="City"/>
                                    <BreezeInput id="ministry_location_city" class="form-control" type="text"
                                                 v-model="editForm.ministry_location_city"
                                                 :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-4">
                                    <BreezeLabel for="ministry_location_prov" value="Province"/>
                                    <BreezeSelect id="ministry_location_prov" class="form-control"
                                                  v-model="editForm.ministry_location_prov"
                                                  :disabled="editForm.processing">
                                        <option v-for="province in provinces" :value="province.province_code">
                                            {{ province.province_code }}
                                        </option>
                                    </BreezeSelect>

                                </div>
                                <div class="col-lg-4">
                                    <BreezeLabel for="ministry_location_postal" value="Postal Code"/>
                                    <BreezeInput id="ministry_location_postal" class="form-control" type="text"
                                                 v-model="editForm.ministry_location_postal"
                                                 :disabled="editForm.processing"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <BreezeLabel for="ministry_location_fax" value="Fax"/>
                                    <BreezeInput id="ministry_location_fax" class="form-control" type="text"
                                                 v-model="editForm.ministry_fax"
                                                 :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-6">
                                    <BreezeLabel for="ministry_location_tele_toll_free" value="Tele - Toll-Free"/>
                                    <BreezeInput id="ministry_location_tele_toll_free" class="form-control" type="text"
                                                 v-model="editForm.ministry_location_tele_toll_free"
                                                 :disabled="editForm.processing"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end of section -->

                <div class="card-header mb-3">
                    <div class="row mb-3">
                        <div class="col-md-3 ">
                            <strong>Administrator</strong>
                            <p>YEAF administrator information</p>
                        </div>
                        <div class="col-md-9">
                            <div class="row mb-3">
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <BreezeLabel for="contact_fname" value="First Name"/>
                                    <BreezeInput id="contact_fname" class="form-control" type="text"
                                                 v-model="editForm.contact_fname" :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-4">
                                    <BreezeLabel for="contact_lname" value="Last Name"/>
                                    <BreezeInput id="contact_lname" class="form-control" type="text"
                                                 v-model="editForm.contact_lname" :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-4">
                                    <BreezeLabel for="contact_tele" value="Telephone"/>
                                    <BreezeInput id="contact_tele" class="form-control" type="text"
                                                 v-model="editForm.contact_tele" :disabled="editForm.processing"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <BreezeLabel for="contact_title" value="Title"/>
                                    <BreezeInput id="contact_title" class="form-control" type="text"
                                                 v-model="editForm.contact_title" :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-4">
                                    <BreezeLabel for="contact_dept" value="Unit/Department"/>
                                    <BreezeInput id="contact_dept" class="form-control" type="text"
                                                 v-model="editForm.contact_dept" :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-4">
                                    <BreezeLabel for="contact_branch" value="Branch"/>
                                    <BreezeInput id="contact_branch" class="form-control" type="text"
                                                 v-model="editForm.contact_branch" :disabled="editForm.processing"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end of section -->

                <div class="card-header mb-3">
                    <div class="row mb-3">
                        <div class="col-md-3 ">
                            <strong>Organization</strong>
                            <p>Organization information</p>
                        </div>
                        <div class="col-md-9">
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <BreezeLabel for="org" value="Organization Name"/>
                                    <BreezeInput id="org" class="form-control" type="text" v-model="editForm.org"
                                                 :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-3">
                                    <BreezeLabel for="org_fname" value="First Name"/>
                                    <BreezeInput id="org_fname" class="form-control" type="text"
                                                 v-model="editForm.org_fname" :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-3">
                                    <BreezeLabel for="org_lname" value="Last Name"/>
                                    <BreezeInput id="org_lname" class="form-control" type="text"
                                                 v-model="editForm.org_lname" :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-3">
                                    <BreezeLabel for="org_fax" value="Fax"/>
                                    <BreezeInput id="org_fax" class="form-control" type="text"
                                                 v-model="editForm.org_fax" :disabled="editForm.processing"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end of section -->

                <div class="card-header mb-3">
                    <div class="row mb-3">
                        <div class="col-md-3 ">
                            <strong>Primary User</strong>
                            <p>Primary user information</p>
                        </div>
                        <div class="col-md-9">
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <BreezeLabel for="user_fname" value="First Name"/>
                                    <BreezeInput id="user_fname" class="form-control" type="text"
                                                 v-model="editForm.user_fname" :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-6">
                                    <BreezeLabel for="user_lname" value="Last Name"/>
                                    <BreezeInput id="user_lname" class="form-control" type="text"
                                                 v-model="editForm.user_lname" :disabled="editForm.processing"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <BreezeLabel for="user_branch" value="Branch"/>
                                    <BreezeInput id="user_branch" class="form-control" type="text"
                                                 v-model="editForm.user_branch" :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-4">
                                    <BreezeLabel for="user_tele" value="Telephone"/>
                                    <BreezeInput id="user_tele" class="form-control" type="text"
                                                 v-model="editForm.user_tele" :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-4">
                                    <BreezeLabel for="user_fax" value="Fax"/>
                                    <BreezeInput id="user_fax" class="form-control" type="text"
                                                 v-model="editForm.user_fax" :disabled="editForm.processing"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end of section -->

                <div class="card-header mb-3">
                    <div class="row mb-3">
                        <div class="col-md-3 ">
                            <strong>Application</strong>
                            <p>Application information</p>
                        </div>
                        <div class="col-md-9">
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <BreezeLabel for="application_name" value="Application Name"/>
                                    <BreezeInput id="application_name" class="form-control" type="text"
                                                 v-model="editForm.application_name" :disabled="editForm.processing"/>
                                </div>
                                <div class="col-lg-6">
                                    <BreezeLabel for="application_abbreviation" value="Application Abbreviation"/>
                                    <BreezeInput id="application_abbreviation" class="form-control" type="text"
                                                 v-model="editForm.application_abbreviation"
                                                 :disabled="editForm.processing"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end of section -->


                <div v-if="editForm.errors != undefined" class="row">
                    <div class="col-12">
                        <div v-if="editForm.hasErrors == true" class="alert alert-danger mt-3">
                            <ul>
                                <li v-for="err in editForm.errors"><small>{{ err }}</small></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn me-2 btn-outline-success"
                        :disabled="editForm.processing || editForm.isDirty === false">Save Changes
                </button>
            </div>
            <FormSubmitAlert :form-state="editForm.formState" :success-msg="editForm.formSuccessMsg" :fail-msg="editForm.formFailMsg"></FormSubmitAlert>

        </form>
        <h1 v-else class="lead">No results</h1>

    </div>

</template>
<script>
import {Link, useForm} from '@inertiajs/vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from '@/Components/Select.vue';
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'MaintenanceMinistry',
    components: {
        BreezeSelect, BreezeInput, BreezeLabel, Link, FormSubmitAlert
    },
    props: {
        results: Object,
        provinces: Object,
    },
    data() {
        return {
            editForm: '',
        }
    },
    methods: {
        editArea: function () {
            this.editForm.formState = '';
            this.editForm.post('/yeaf/maintenance/ministry/' + this.editForm.id, {
                onSuccess: () => {
                    this.editForm.reset();
                    this.editForm.formState = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.editForm.formState = false;
                },
                preserveState: false
            });
        },

    },
    mounted() {
        this.editForm = useForm(this.results);
    }
}

</script>
