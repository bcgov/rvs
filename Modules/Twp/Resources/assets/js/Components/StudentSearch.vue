<template>
<!--    <Link :href="href" :class="classes">-->
<!--        <slot />-->
<!--    </Link>-->

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button @click="switchSearchTerm('byName')" class="nav-link active" id="name-tab" data-bs-toggle="tab" data-bs-target="#name-tab-pane" type="button" role="tab" aria-controls="name-tab-pane" aria-selected="false">Name</button>
        </li>
        <li class="nav-item" role="presentation">
            <button @click="switchSearchTerm('bySchool')" class="nav-link" id="shcool-tab" data-bs-toggle="tab" data-bs-target="#school-tab-pane" type="button" role="tab" aria-controls="school-tab-pane" aria-selected="true">School</button>
        </li>

    </ul>
    <div class="tab-content" id="myTabContent">

        <div v-if="searchType === 'byName'" class="tab-pane fade show active" id="name-tab-pane" role="tabpanel" aria-labelledby="name-tab" tabindex="1">
            <form @submit.prevent="nameFormSubmit" class="m-3">
                <div class="row mb-3 align-items-center">
                    <BreezeLabel class="col-sm-4 col-md-4 col-form-label text-md-end text-nowrap" for="inputLastName" value="Last Name" />
                    <div class="col-sm-8 col-md-6">
                        <BreezeInput type="text" id="inputLastName" class="form-control" v-model="nameForm.filter_lname" />
                    </div>
                </div>
                <div class="row mb-3">
                    <BreezeLabel class="col-sm-4 col-md-4 col-form-label text-md-end text-nowrap" for="inputFirstName" value="First Name" />
                    <div class="col-sm-8 col-md-6">
                        <BreezeInput type="text" id="inputFirstName" class="form-control" v-model="nameForm.filter_fname" />
                    </div>
                </div>
                <div class="row mb-3">
                    <BreezeLabel class="col-sm-4 col-md-4 col-form-label text-md-end text-nowrap" for="inputAliasName" value="Alias Name" />
                    <div class="col-sm-8 col-md-6">
                        <BreezeInput type="text" id="inputAliasName" class="form-control" v-model="nameForm.filter_aname" />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 offset-md-4">
                        <BreezeButton class="btn btn-primary" :class="{ 'opacity-25': nameForm.processing }" :disabled="nameForm.processing">
                            Search
                        </BreezeButton>
                    </div>
                </div>
            </form>
        </div>

        <div v-if="searchType === 'bySchool'" class="tab-pane fade show active" id="school-tab-pane" role="tabpanel" aria-labelledby="school-tab" tabindex="0">
            <form @submit.prevent="schoolFormSubmit" class="m-3">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <BreezeLabel for="inputSchool" value="School" />
                    </div>
                    <div class="col-auto">
                        <BreezeSelect class="form-select" id="inputInstitutionName" v-model="schoolForm.filter_school">
                            <template v-for="school in schools">
                                <option v-if="school.active_flag === true" :value="school.id">{{ school.name }}</option>
                            </template>
                        </BreezeSelect>
                    </div>
                    <div class="col-auto">
                        <BreezeButton class="btn btn-primary" :class="{ 'opacity-25': schoolForm.processing }" :disabled="schoolForm.processing">
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
import { useForm } from '@inertiajs/vue3';

let searchType = ref('byName');
let schoolId = ref('');

const props = defineProps({
    schools: Object,
    page: String,
});

onMounted(async () => {

});

const switchSearchTerm = function (type){
    searchType.value = type;
    Object.assign(nameForm, nameFormTemplate);
}

const nameFormTemplate = {
    filter_fname: '',
    filter_lname: '',
    filter_aname: '',
    filter_type: '',
};
const nameForm = useForm(nameFormTemplate);
const nameFormSubmit = () => {
    nameForm.filter_type = 'name';
    nameForm.get(props.page, {
        onFinish: () => nameForm.reset('inputLastName', 'inputFirstName', 'inputAliasName'),
    });
};


const schoolFormTemplate = {
    filter_school: '',
    filter_type: '',
};
const schoolForm = useForm(schoolFormTemplate);
const schoolFormSubmit = () => {
    schoolForm.filter_type = 'school';
    schoolForm.get(props.page, {
        onFinish: () => schoolForm.reset('inputInstitutionName'),
    });
};


</script>
