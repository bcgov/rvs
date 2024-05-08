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
    <div>
        <table v-if="student != null && student.length > 0">
            <tr v-for="(v, k, i) in student[0]">
                <th>{{k}}:</th>
                <td>{{v}}</td>
            </tr>
        </table>
    </div>

</template>
<script>
import {Link, useForm} from '@inertiajs/vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'ApplicationEditStudentTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect, FormSubmitAlert
    },
    props: {
        student: Object,
    },
    data() {
        return {
        }
    },
    methods: {

    },
    watch: {

    },
    mounted() {
    }
}

</script>
