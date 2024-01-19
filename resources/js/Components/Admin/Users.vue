<template>
    <div class="card">
        <div class="card-header">
            <div>Staff Maintenance</div>
        </div>

        <div class="card-body">
            <div v-if="results != null" class="table-responsive pb-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">User ID</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Type of Access</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, i) in results">
                            <th scope="row">
                                <button type="button" class="btn btn-link" @click="edit(i)">{{ row.user_id }}</button>
                            </th>
                            <td>{{ row.first_name }}</td>
                            <td>{{ row.last_name}}</td>
                            <td>{{ row.email }}</td>
                            <td>
                                <template v-for="role in row.roles">
                                    <div>{{ role.name }}</div>
                                </template>
                            </td>
                            <td>
                                <span v-if="row.disabled" class="badge rounded-pill text-bg-danger">Disabled</span>
                                <span v-else class="badge rounded-pill text-bg-success">Active</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h1 v-else class="lead">No results</h1>
        </div>

    </div>

    <div class="modal modal-lg fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form v-if="editUserForm != null" @submit.prevent="editUser">
                    <div class="modal-body">
                        <div class="card-body">

                            <div>
                                <BreezeLabel for="user_id" value="User ID" />
                                <BreezeInput id="user_id" type="text" class="mt-1 block w-full bg-indigo-50" v-model="editUser.user_id" disabled="disabled" />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="first_name" value="First Name" />
                                <BreezeInput id="first_name" type="text" class="mt-1 block w-full bg-indigo-50" v-model="editUser.first_name" disabled="disabled" />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="last_name" value="Last Name" />
                                <BreezeInput id="last_name" type="text" class="mt-1 block w-full bg-indigo-50" v-model="editUser.last_name" disabled="disabled" />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="email" value="Email" />
                                <BreezeInput id="email" type="email" class="mt-1 block w-full bg-indigo-50" v-model="editUser.email" disabled="disabled" />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="tele" value="Telephone #" />
                                <BreezeInput id="tele" type="number" class="mt-1 block w-full" v-model="editUser.tele" required />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="access_type" value="Access Type" />
                                <BreezeSelect id="access_type" type="text" class="mt-1 block w-full" v-model="editUser.access_type" required>
                                    <option value="A">Admin</option>
                                    <option value="U">User</option>
                                </BreezeSelect>
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="disabled" value="Status" />
                                <BreezeSelect id="disabled" class="mt-1 block w-full" v-model="editUser.disabled" required>
                                    <option value="false">Active</option>
                                    <option value="true">Disabled</option>
                                </BreezeSelect>
                            </div>


                            <div v-if="editUser.errors != undefined" class="row">
                                <div class="col-12">
                                    <div v-if="editUser.hasErrors == true" class="alert alert-danger mt-3">
                                        <ul>
                                            <li v-for="err in editUser.errors"><small>{{ err }}</small></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn me-2 btn-outline-success" :disabled="editUser.processing">Submit</button>
                    </div>
                    <FormSubmitAlert :form-state="editUser.formState" :success-msg="editUser.formSuccessMsg" :fail-msg="editUser.formFailMsg"></FormSubmitAlert>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import { Link, useForm } from '@inertiajs/vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeSelect from '@/Components/Select.vue';
import BreezeLabel from '@/Components/Label.vue';
import FormSubmitAlert from '@/Components/FormSubmitAlert.vue';

export default {
    name: 'AdminUsers',
    components: {
        BreezeInput, Link, BreezeSelect, BreezeLabel, FormSubmitAlert
    },
    props: {
        results: Object,
        editUser: ''
    },
    data() {
        return {
        }
    },
    methods: {
        edit: function (i){
            this.editUser = useForm(this.results[i]);
            $("#editUserModal").modal('show');

        }
    }
}

</script>
