<style scoped>
.blue-cell{
    background-color: #d4e5ff;
}
.yellow-cell{
    background-color: #feffc4;
}
.green-cell{
    background-color: #d2ffcc;
}
</style>
<template>
    <Head title="Reports" />

    <BreezeAuthenticatedLayout v-bind="$attrs">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                TWP - Tuition Waiver Program
            </h2>
        </template>

        <div class="mt-3">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-12 mt-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                Institution Reports

                                <div v-if="results != null" class="btn-group float-end" role="group" aria-label="Reports view">
                                    <button @click="download('download')" type="button" class="btn btn-sm btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-down" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z"/>
                                            <path fill-rule="evenodd" d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="m-3">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <BreezeSelect @change="reportFormSubmit" class="form-select" id="inputInstitutionName" v-model="reportForm.institutionId">
                                                <option value="" selected>--- SELECT SCHOOL ---</option>
                                                <template v-for="school in schools">
                                                    <option v-if="school.active_flag === true" :value="school.id">{{ school.name }}</option>
                                                </template>
                                            </BreezeSelect>
                                        </div>
                                    </div>
                                </form>
                                <div v-if="results != null" class="table-responsive pb-3">




                                    <div class="card mb-3">
                                        <div class="card-header text-center">{{ results.name }} Financial Report</div>
                                        <div class="card-body">
                                            <table id="report" class="table">
                                                <thead>
                                                <tr>
                                                    <th colspan="12" class="text-center blue-cell"><strong>Tuition Waiver Program</strong></th>
                                                </tr>
                                                <tr>
                                                    <th colspan="12" class="text-center blue-cell">{{ results.name }} Financial Report</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="12">Instructions: Please complete any blank fields,
                                                        check provided information for accuracy/changes and provide tuition cost and
                                                        administrative fees. Students not found on the sheet can be added in the blank
                                                        rows at the end of the sheet.
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th colspan="4" class="text-center blue-cell">Student Info</th>
                                                    <th colspan="7" class="text-center yellow-cell">Program Costs</th>
                                                    <th class="text-center green-cell">Financial Info</th>
                                                </tr>
                                                <tr>
                                                    <th class="blue-cell">Name (Last, First)</th>
                                                    <th class="blue-cell">Alias Name</th>
                                                    <th class="blue-cell">Date of Birth (yyyy-mm-dd)</th>
                                                    <th class="blue-cell">Student #</th>
                                                    <th class="blue-cell">PEN #</th>

                                                    <th class="yellow-cell">Name of Institution</th>
                                                    <th class="yellow-cell">Study Period Start Date (yyyy-mm-dd)</th>
                                                    <th class="yellow-cell">Credential</th>
                                                    <th class="yellow-cell">Program Length</th>
                                                    <th class="yellow-cell">Total Estimated Program Cost</th>
                                                    <th class="yellow-cell">Student Status</th>
                                                    <th class="yellow-cell">Comments</th>

                                                    <th class="green-cell">Tuition and Mandatory Fees ($)<br/><small>{{reportDate}}</small></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <template v-for="(row, i) in results.programs">
                                                        <tr v-if="row.student_status == 'Attending' || row.student_status == 'Hiatus'">
                                                            <td class="blue-cell">{{ row.student.last_name }}, {{ row.student.first_name }}</td>
                                                            <td class="blue-cell">{{ row.student.alias_name }}</td>
                                                            <td class="blue-cell">{{ row.student.birth_date }}</td>
                                                            <td class="blue-cell">{{ row.student.institution_student_number }}</td>
                                                            <td class="blue-cell">{{ row.student.pen }}</td>

                                                            <td class="yellow-cell">{{ row.institution_name }}</td>
                                                            <td class="yellow-cell">{{ row.study_period_start_date }}</td>
                                                            <td class="yellow-cell">{{ row.credential }}</td>
                                                            <td class="yellow-cell">{{ row.program_length }} {{ row.program_length_type}}</td>
                                                            <td class="yellow-cell">{{ formatMoney(row.total_estimated_cost) }}</td>
                                                            <td class="yellow-cell">{{ row.student_status}}</td>
                                                            <td class="yellow-cell">{{ row.comments}}</td>

                                                            <td class="green-cell"></td>

                                                        </tr>
                                                    </template>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>

                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="green-cell">Total Payment Request</td>

                                                    <td class="green-cell">$</td>

                                                </tr>

                                                </tfoot>
                                            </table>

                                        </div>
                                    </div>



                                </div>
                                <h1 v-else class="lead">No results</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>

</template>
<script>
import BreezeAuthenticatedLayout from '../Layouts/Authenticated.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import BreezeInput from "@/Components/Input";
import BreezeSelect from "@/Components/Select";
import BreezeLabel from "@/Components/Label";
import BreezePagination from "@/Components/Pagination";
import BreezeButton from "@/Components/Button";

export default {
    name: 'Reports',
    components: {
        BreezeAuthenticatedLayout, Head, Link, BreezeInput, BreezeSelect, BreezeLabel, BreezePagination, BreezeButton
    },
    props: {
        results: Object,
        schools: Object,
    },
    data() {
        return {
            reportForm: useForm({
                institutionId: '',
            }),
            showSuccessMsg: false,
            reportDate: '',
        }
    },
    methods: {
        download: function ()
        {
            this.reportDate = prompt("Please enter report Date", "");
            let text;
            if (this.reportDate == null || this.reportDate == "") {
                text = "User cancelled the prompt.";
            } else {
                let vm = this;
                setTimeout(function (){
                    vm.downloadTableAsCsv();
                }, 1000);
            }


        },
        formatMoney: function (a){
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(a);
        },
        reportFormSubmit: function (){
            if(this.reportForm.institutionId === '') return false;
            this.reportDate = '';
            this.reportForm.post('/twp/reports', {
                onSuccess: (response) => {

                    //this.results = response.data.results;

                },
                preserveState: false
            });
        },
        // Quick and simple export target #table_id into a csv
        downloadTableAsCsv: function(separator = ',') {
            // Select rows from table_id
            let rows = document.querySelectorAll('table#report tr');
            // Construct csv
            let csv = [];
            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll('td, th');
                for (let j = 0; j < cols.length; j++) {
                    // Clean innertext to remove multiple spaces and jumpline (break csv)
                    let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
                    // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
                    data = data.replace(/"/g, '""');
                    // Push escaped string
                    row.push('"' + data + '"');
                }
                csv.push(row.join(separator));
            }
            let csv_string = csv.join('\n');
            // Download it
            let filename = 'report_' + new Date().toLocaleDateString() + '.csv';
            let link = document.createElement('a');
            link.style.display = 'none';
            link.setAttribute('target', '_blank');
            link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },

    },
    mounted() {

    }
}
</script>
