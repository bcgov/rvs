<template>
    <div>
        <Head title="VSS - Case Funding" />

        <BreezeAuthenticatedLayout>

            <div class="mt-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    VSS Case Search
                                </div>
                                <div class="card-body">
                                    <CaseSearchBox />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    VSS Case Comments
                                    <span v-if="result != null" class="badge rounded-pill text-bg-primary float-end">{{ result.incident_status }}</span>
                                </div>
                                <template v-if="result != null">
                                    <form @submit.prevent="updateAllComments">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <table>
                                                        <tr>
                                                            <th scope="row">SIN:</th>
                                                            <td class="ps-1">{{ result.sin }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th scope="row">Year of Audit:</th>
                                                            <td class="ps-1">{{ result.year_of_audit }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Date Opened:</th>
                                                            <td class="ps-1">{{ result.open_date }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-lg-8">
                                                    <table>
                                                        <tr>
                                                            <th scope="row">Last Name:</th>
                                                            <td class="ps-1">{{ result.last_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">First Name:</th>
                                                            <td class="ps-1">{{ result.first_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">School:</th>
                                                            <td class="ps-1">{{ result.institution.institution_name }}</td>
                                                        </tr>

                                                    </table>
                                                </div>
                                            </div>
                                            <table v-if="newRows.length > 0 || (result.comments != null && result.comments.length > 0)" class="mt-3 table table-responsive">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">User ID</th>
                                                        <th scope="col">Comment</th>
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(row, i) in result.comments">
                                                        <th scope="row">{{ row.comment_date }}</th>
                                                        <td>{{ row.staff_user_id }}</td>
                                                        <template v-if="$attrs['auth'].user.access_type === 'A' || $attrs['auth'].user.user_id === row.staff_user_id">
                                                            <td>
                                                                <textarea @change="updateComments(i)" rows="9" v-model="row.comment_text" class="form-control"></textarea>
                                                            </td>
                                                            <td><button @click="deleteComment(i,row)" type="button" class="btn-close m-auto" aria-label="Close"></button></td>
                                                        </template>
                                                        <template v-else>
                                                            <td><textarea rows="9" class="form-control" readonly aria-readonly="true">{{row.comment_text}}</textarea></td>
                                                            <td></td>
                                                        </template>

                                                    </tr>

                                                    <tr v-for="(row, i) in newRows">
                                                        <th scope="row">{{ now }}</th>
                                                        <td>{{ $attrs['auth'].user.user_id }}</td>
                                                        <td>
                                                            <textarea rows="9" v-model="row.comment_text" class="form-control"></textarea>
                                                        </td>
                                                        <td></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                            <h1 v-else class="alert alert-warning text-center mt-4">No Comments</h1>

                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn me-2" :class="noChanges ? 'btn-outline-success' : 'btn-success'" :disabled="noChanges">Save</button>
                                            <button @click="newComment" type="button" class="btn btn-outline-info">Add New Comment</button>
                                            <Link @click="back" class="btn btn-outline-primary float-end" href="#">Back</Link>
                                            <Link :href="'/vss/case-funding/' + result.id" class="btn btn-outline-dark float-end me-2">Funds</Link>
                                            <Link :href="'/vss/cases/' + result.id" class="btn btn-outline-danger float-end me-2">Edit Case</Link>
                                        </div>
                                    </form>
                                </template>
                                <h1 v-else class="lead">No results</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="showSuccessMsg" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="updateSuccessAlert" class="alert alert-success alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="100">
                    <div class="">
                        <div class="toast-body">
                            Case Comments were updated successfully.
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>


        </BreezeAuthenticatedLayout>
    </div>

</template>
<script>

import BreezeAuthenticatedLayout from '../Layouts/Authenticated.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import CaseSearchBox from '../Components/CaseSearch.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeSelect from '@/Components/Select.vue';
import { router } from '@inertiajs/vue3';

export default {
    name: 'CaseComment',
    components: {
        BreezeAuthenticatedLayout, CaseSearchBox, Head, Link, BreezeInput, BreezeSelect
    },
    props: {
        result: Object,
        comments: Object,
        now: String,
        staff: Object
    },
    data() {
        return {
            noChanges: true,
            searchType: '',
            searchResults: '',
            updates: [],
            newRows: [],
            showSuccessMsg: false,
        }
    },
    methods: {
        deleteComment: function (index, row)
        {
            if(confirm("Are you sure you want to delete this Case Comment?")){
                const form = useForm({});
                form.delete('/vss/case-comment/' + row.id, {
                    onSuccess: () => {
                        this.showSuccessAlert();
                    }
                });
            }
        },
        updateAllComments: function ()
        {
            const form = useForm({
                old_rows: this.result.comments,
                new_rows: this.newRows
            });
            form.put('/vss/case-comment/' + this.result.id, {
                onSuccess: () => {
                    this.showSuccessAlert();
                }
            });
        },
        showSuccessAlert: function ()
        {
            this.showSuccessMsg = true;
            let vm = this;
            setTimeout(function (){
                vm.showSuccessMsg = false;
                vm.noChanges = true;
                Inertia.get('/vss/case-comment/' + vm.result.id, {preserveState: true});

            }, 4000);
        },
        newComment: function(){
            this.newRows.push({
                'comment_text': ''
            });
            this.noChanges = false;
        },
        back: function()
        {
            window.history.back();
        },
        updateComments: function (index)
        {
            this.noChanges = false;
        },
    },
    watch: {
        updated: function(newVal, oldVal){

        }
    },
    computed: {

    }
}
</script>
