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
    <p v-if="result.comments.length === 0" class="text-center leading-5">No Comments.</p>
    <ul v-else class="list-group list-group-flush">
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-2"><strong>Date</strong></div>
                <div class="col-md-2"><strong>User</strong></div>
                <div class="col-md-8"><strong>Comment</strong></div>
            </div>
        </li>
        <li v-for="(comment, j) in result.comments" class="list-group-item">
            <div class="row">
                <div class="col-md-2">{{ comment.created_human_date }}</div>
                <div class="col-md-2">{{ comment.user_id }}</div>
                <div class="col-md-8">{{ comment.comment_text }}</div>
            </div>
        </li>
    </ul>

</div>



</template>
<script>
import {Link} from '@inertiajs/vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";

export default {
    name: 'StudentEditCommentTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect
    },
    props: {
        result: Object,

        all_staff: Object,
    },
    data() {
        return {
        }
    },
    mounted() {
        this.grantForms = JSON.parse(JSON.stringify(this.result.grants));
    }
}

</script>
