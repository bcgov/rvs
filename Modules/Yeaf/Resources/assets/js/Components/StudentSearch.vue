<template>
<!--    <Link :href="href" :class="classes">-->
<!--        <slot />-->
<!--    </Link>-->

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button @click="switchSearchTerm('bySin')" class="nav-link active" id="sin-tab" data-bs-toggle="tab" data-bs-target="#sin-tab-pane" type="button" role="tab" aria-controls="sin-tab-pane" aria-selected="true">SIN</button>
        </li>
        <li class="nav-item" role="presentation">
            <button @click="switchSearchTerm('byName')" class="nav-link" id="name-tab" data-bs-toggle="tab" data-bs-target="#name-tab-pane" type="button" role="tab" aria-controls="name-tab-pane" aria-selected="false">Name</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="sin-tab-pane" role="tabpanel" aria-labelledby="sin-tab" tabindex="0">
            <form @submit.prevent="sinFormSubmit" class="m-3">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <BreezeLabel for="inputSin" value="SIN" />
                    </div>
                    <div class="col-auto">
                        <BreezeInput type="number" id="inputSin" class="form-control" maxlength="9" v-model="sinForm.filter_sin" required />
                    </div>
                    <div class="col-auto">
                        <BreezeButton class="btn btn-primary" :class="{ 'opacity-25': sinForm.processing }" :disabled="sinForm.processing">
                            Search
                        </BreezeButton>
                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane fade" id="name-tab-pane" role="tabpanel" aria-labelledby="name-tab" tabindex="1">
            <form @submit.prevent="nameFormSubmit" class="m-3">
                <div class="row mb-3">
                    <BreezeLabel class="col-auto col-form-label" for="inputLastName" value="Last Name" />
                    <div class="col-auto">
                        <BreezeInput type="text" id="inputLastName" class="form-control" v-model="nameForm.filter_lname" />
                    </div>
                </div>
                <div class="row mb-3">
                    <BreezeLabel class="col-auto col-form-label" for="inputFirstName" value="First Name" />
                    <div class="col-auto">
                        <BreezeInput type="text" id="inputFirstName" class="form-control" v-model="nameForm.filter_fname" />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-auto">
                        <BreezeButton class="btn btn-primary" :class="{ 'opacity-25': nameForm.processing }" :disabled="nameForm.processing">
                            Search
                        </BreezeButton>
                    </div>
                </div>
            </form>
        </div>

    </div>
</template>
<script setup>
import BreezeInput from '@/Components/Input.vue';
import BreezeSelect from '@/Components/Select.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeButton from '@/Components/Button.vue';

import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from "axios";

let searchType = ref('bySin');

onMounted(async () => {

});

const switchSearchTerm = function (type){
    this.searchType = type;
    Object.assign(sinForm, sinFormTemplate);
    Object.assign(nameForm, nameFormTemplate);
}

const sinFormTemplate = {
    filter_sin: '',
};
const sinForm = useForm(sinFormTemplate);
const sinFormSubmit = () => {
    sinForm.get('/yeaf/students', {
        onFinish: () => sinForm.reset('inputSin'),
    });
};

const nameFormTemplate = {
    filter_fname: '',
    filter_lname: '',
};
const nameForm = useForm(nameFormTemplate);
const nameFormSubmit = () => {
    nameForm.get('/yeaf/students', {
        onFinish: () => nameForm.reset('inputLastName', 'inputFirstName'),
    });
};


</script>
