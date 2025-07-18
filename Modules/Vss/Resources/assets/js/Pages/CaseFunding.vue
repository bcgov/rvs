<template>
    <Head title="Case Funding" />

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
                                VSS Case Funding
                                <span v-if="result != null" class="badge rounded-pill text-bg-primary float-end">{{ result.incident_status }}</span>
                            </div>
                            <template v-if="result != null">
                                <form @submit.prevent="updateAllFunds">
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
                                        <table v-if="newRows.length > 0 || (result.funds != null && result.funds.length > 0)" class="mt-3 table table-responsive">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col">Application</th>
                                                    <th scope="col">Funding Type</th>
                                                    <th scope="col">Date Funds<br/>Recouped</th>
                                                    <th scope="col">Over Award</th>
                                                    <th scope="col">Prevented Funding</th>
                                                    <th scope="col">Total</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(row, i) in result.funds">
                                                    <th scope="row">
                                                        <BreezeInput @change="updateFunds(i)" class="form-control" type="number" placeholder="Application Number" v-model="row.application_number" />
                                                    </th>
                                                    <td>
                                                        <BreezeSelect @change="updateFunds(i)" class="form-select" v-model="row.funding_type.funding_type">
                                                            <option v-for="(f,j) in funds" :value="f.funding_type">{{ f.funding_type }} | {{ f.description }}</option>
                                                        </BreezeSelect>
                                                    </td>
                                                    <td>
                                                        <BreezeInput @change="updateFunds(i)" class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="row.fund_entry_date" />
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <BreezeInput @change="updateFunds(i)" type="number" class="form-control" v-model="row.over_award" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <BreezeInput @change="updateFunds(i)" type="number" class="form-control" v-model="row.prevented_funding" />
                                                        </div>
                                                    </td>
                                                    <td>${{ formatMoney( rowTotal(row.over_award, row.prevented_funding) ) }}</td>
                                                    <td><button @click="deleteFund(i,row)" type="button" class="btn-close m-auto" aria-label="Close"></button></td>
                                                </tr>

                                                <tr v-for="(row, i) in newRows">
                                                    <th scope="row">
                                                        <BreezeInput class="form-control" type="number" placeholder="Application Number" v-model="row.application_number" />
                                                    </th>
                                                    <td>
                                                        <BreezeSelect class="form-select" v-model="row.funding_type">
                                                            <option v-for="(f,j) in funds" :value="f.funding_type">{{ f.funding_type }} | {{ f.description }}</option>
                                                        </BreezeSelect>
                                                    </td>
                                                    <td>
                                                        <BreezeInput class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="row.fund_entry_date" />
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <BreezeInput type="number" class="form-control" v-model="row.over_award" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <BreezeInput type="number" class="form-control" v-model="row.prevented_funding" />
                                                        </div>
                                                    </td>
                                                    <td>${{ formatMoney( rowTotal(row.over_award, row.prevented_funding) ) }}</td>
                                                </tr>

                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <th scope="row"></th>
                                                    <td></td>
                                                    <td>Totals:</td>
                                                    <td><strong>${{ totalOverAward }}</strong></td>
                                                    <td><strong>${{ totalPrevented }}</strong></td>
                                                    <td><strong>${{ totals }}</strong></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <h1 v-else class="alert alert-warning text-center mt-4">No funds</h1>

                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn me-2" :class="noChanges ? 'btn-outline-success' : 'btn-success'" :disabled="noChanges">Save</button>
                                        <button @click="newFund" type="button" class="btn btn-outline-info">Add New Fund</button>
                                        <Link @click="back" class="btn btn-outline-primary float-end" href="#">Back</Link>
                                        <Link :href="'/vss/case-comment/' + result.id" class="btn btn-outline-dark float-end me-2">Comments</Link>
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
                        Case Funding record was updated successfully.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>


    </BreezeAuthenticatedLayout>

</template>
<script>

import BreezeAuthenticatedLayout from '../Layouts/Authenticated.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import CaseSearchBox from '../Components/CaseSearch.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeSelect from '@/Components/Select.vue';
import { router } from '@inertiajs/vue3';

export default {
    name: 'CaseFunding',
    components: {
        BreezeAuthenticatedLayout, CaseSearchBox, Head, Link, BreezeInput, BreezeSelect
    },
    props: {
        result: Object,
        funds: Object,
        now: String,
        schools: Object
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
        deleteFund: function (index, row)
        {
            if(confirm("Are you sure you want to delete this Case Funding record?")){
                const form = useForm({});
                form.delete('/vss/case-funding/' + row.id, {
                    onSuccess: () => {
                        this.showSuccessAlert();
                    }
                });
            }
        },
        updateAllFunds: function ()
        {
            const form = useForm({
                old_rows: this.result.funds,
                new_rows: this.newRows
            });
            form.put('/vss/case-funding/' + this.result.id, {
                onSuccess: () => {
                    this.showSuccessAlert();
                }
            });
            // form.wasSuccessful();
        },
        showSuccessAlert: function ()
        {
            this.showSuccessMsg = true;
            let vm = this;
            setTimeout(function (){
                vm.showSuccessMsg = false;
                vm.noChanges = true;
                router.get('/vss/case-funding/' + vm.result.id, {preserveState: true});

            }, 5000);
        },
        newFund: function(){
            this.newRows.push({
                'application_number': this.result.application_number,
                'funding_type': '',
                'fund_entry_date': this.now,
                'over_award': 0,
                'prevented_funding': 0
            });
            this.noChanges = false;
        },
        back: function()
        {
            window.history.back();
        },
        updateFunds: function (index)
        {
            if(this.updates.slice(index).length === 0){
                this.updates.push(index);
            }
            this.noChanges = false;
        },

        formatMoney: function (value, decimals = 2, separator = ','){
            // console.log(value);
            let result = value;
            if(value != null){
                result = parseFloat(value);
                result = result.toFixed(decimals);
                result = result.toString();
                if(separator) {
                    result = result.replace(/\B(?=(\d{3})+(?!\d))/g, separator);
                }
            }

            return result;
        },
        rowTotal: function (clmnA, clmnB){
            return parseFloat(clmnA) + parseFloat(clmnB);
        },
    },
    watch: {
        updated: function(newVal, oldVal){

        }
    },
    computed: {
        totalOverAward: function (){
            let total = 0;
            for(let i=0; i<this.result.funds.length; i++){
                total += parseFloat(this.result.funds[i].over_award);
            }
            return total.toLocaleString();
        },
        totalPrevented: function (){
            let total = 0;
            for(let i=0; i<this.result.funds.length; i++){
                total += parseFloat(this.result.funds[i].prevented_funding);
            }
            return total.toLocaleString();
        },
        totals: function (){
            let total = 0;
            for(let i=0; i<this.result.funds.length; i++){
                total += parseFloat(this.result.funds[i].over_award) + parseFloat(this.result.funds[i].prevented_funding);
            }
            return total.toLocaleString();
        },

    }
}
</script>
