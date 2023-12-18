<template>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button @click="switchSearchTerm('byName')" class="nav-link active" id="name-tab" data-bs-toggle="tab" data-bs-target="#name-tab-pane" type="button" role="tab" aria-controls="name-tab-pane" aria-selected="false">Name</button>
        </li>

    </ul>
    <div class="tab-content" id="myTabContent">

        <div v-if="searchType === 'byName'" class="tab-pane fade show active" id="name-tab-pane" role="tabpanel" aria-labelledby="name-tab" tabindex="1">
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
    filter_type: '',
};
const nameForm = useForm(nameFormTemplate);
const nameFormSubmit = () => {
    nameForm.filter_type = 'name';
    nameForm.get(props.page, {
        onFinish: () => nameForm.reset('inputLastName', 'inputFirstName'),
    });
};

</script>
