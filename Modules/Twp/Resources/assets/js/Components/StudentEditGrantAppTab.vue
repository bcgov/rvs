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
    <p v-if="grantForms.length === 0" class="text-center leading-5">No Grant Apps.</p>

    <div v-else class="card">
        <div class="card-header">Grant App History</div>
        <div class="card-body">
            <div class="accordion" id="accordionGrantAppHistory">
                <div v-for="(grant, i) in grantForms" class="accordion-item">
                    <h2 class="accordion-header" :id="'heading'+i">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse'+i" aria-expanded="true" :aria-controls="'collapse'+i">
                            {{ formatDate(grant.created_at) }}
                        </button>
                    </h2>
                    <div :id="'collapse'+i" class="accordion-collapse collapse" :aria-labelledby="'heading'+i" data-bs-parent="#accordionGrantAppHistory">
                        <div class="accordion-body">
                            <form @submit.prevent="updateGrant(i)" :key="i">

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <BreezeLabel :for="'inputReceivedDate'+i" class="form-label" value="Received Date" />
                                        <BreezeInput type="text" class="form-control" :id="'inputReceivedDate'+i" :value="grant.received_date" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel :for="'inputAppStatus'+i" class="form-label" value="Application Status" />
                                        <BreezeInput type="text" class="form-control" :id="'inputAppStatus'+i" :value="grant.grant_status" />
                                    </div>
                                    <div class="col-md-4">
                                        <BreezeLabel :for="'inputAmount'+i" class="form-label" value="Amount" />
                                        <div class="input-group">
                                            <div class="input-group-text">$</div>
                                            <input type="text" class="form-control" :id="'inputAmount'+i" v-model="grant.grant_amount">
                                        </div>

                                    </div>
                                    <div class="col-md-12">
                                        <BreezeLabel :for="'inputComments'+i" class="form-label" value="Comments" />
                                        <textarea class="form-control" :id="'inputComments'+i" rows="3" v-model="grant.grant_comments">{{ grant.grant_comments }}</textarea>
                                    </div>

                                    <div class="col-md-2">
                                        <BreezeLabel class="form-label" value="&nbsp;" />
                                        <button type="submit" class="btn me-2 btn-outline-success" :disabled="!grant.isDirty">Save</button>
                                    </div>


                                </div>


                                <div v-if="Object.keys(grant.errors).length > 0" class="row">
                                    <div class="col-12">
                                        <div class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-for="msg in grant.errors" v-html="msg"></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <FormSubmitAlert :form-state="grant.formState"
                                                 :success-msg="'Grant App record was updated successfully.'"></FormSubmitAlert>
                            </form>

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
    name: 'StudentEditGrantAppTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect, FormSubmitAlert
    },
    props: {
        result: Object,
        twpStudentId: String|Number|null,
    },
    data() {
        return {
            noChanges: true,
            grantForms: [],
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

        updateGrant: function (index)
        {
            this.grantForms[index].formState = '';
            this.grantForms[index].put('/twp/grants/' + this.grantForms[index].id, {
                onSuccess: () => {
                    this.grantForms[index].formState = true;
                    this.noChanges = true;
                },
                onError: () => {
                    this.grantForms[index].formState = false;
                },
                // preserveState: false,

            });
        },

    },
    mounted() {
        for(let i=0; i<this.result.length; i++){
            this.grantForms.push(useForm(this.result[i]));
        }
    }
}

</script>
