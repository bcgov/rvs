<style scoped>
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>
    <Head title="Reports" />

    <BreezeAuthenticatedLayout>

        <div class="mt-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <div class="card">
                            <div class="card-header">
                                VSS Area and Program Report
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="reportFormSubmit" class="m-3">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <BreezeLabel class="form-label" for="inputStartDate" value="Start Date" />
                                            <BreezeInput type="date" id="inputStartDate" class="form-control" v-model="reportForm.inputStartDate" />
                                        </div>
                                        <div class="col-md-6">
                                            <BreezeLabel class="form-label" for="inputEndDate" value="End Date" />
                                            <BreezeInput type="date" id="inputEndDate" class="form-control" v-model="reportForm.inputEndDate" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <BreezeInput @click="changeType('overaward')" type="radio" name="radioReport" id="radioReport1" class="form-check-input" value="overaward" />
                                                <BreezeLabel class="col-auto form-check-label" for="radioReport1" value="Over Award Report" />
                                            </div>
                                            <div class="form-check">
                                                <BreezeInput @click="changeType('prevented')" type="radio" name="radioReport" id="radioReport2" class="form-check-input" value="prevented" />
                                                <BreezeLabel class="col-auto form-check-label" for="radioReport2" value="Prevented Report" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-auto">
                                            <BreezeButton type="submit" class="btn btn-primary" :class="{ 'opacity-25': reportForm.processing }" :disabled="reportForm.processing">
                                                Search
                                            </BreezeButton>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 mt-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                <span v-if="results === null">VSS Report</span>
                                <template v-else>
                                    <span v-if="reportForm.type === 'prevented'">VSS Prevented Report</span>
                                    <span v-else>VSS Over Award Report</span>
                                    <small class="text-muted"> from {{start}} to {{end}}</small>
                                </template>

                                <div v-if="results != null" class="btn-group float-end" role="group" aria-label="Reports view">
                                    <button @click="switchView('chart')" type="button" class="btn btn-sm btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-line" viewBox="0 0 16 16">
                                            <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2zm1 12h2V2h-2v12zm-3 0V7H7v7h2zm-5 0v-3H2v3h2z"/>
                                        </svg>
                                    </button>
                                    <button @click="switchView('grid')" type="button" class="btn btn-sm btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grid-3x3" viewBox="0 0 16 16">
                                            <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h13A1.5 1.5 0 0 1 16 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13zM1.5 1a.5.5 0 0 0-.5.5V5h4V1H1.5zM5 6H1v4h4V6zm1 4h4V6H6v4zm-1 1H1v3.5a.5.5 0 0 0 .5.5H5v-4zm1 0v4h4v-4H6zm5 0v4h3.5a.5.5 0 0 0 .5-.5V11h-4zm0-1h4V6h-4v4zm0-5h4V1.5a.5.5 0 0 0-.5-.5H11v4zm-1 0V1H6v4h4z"/>
                                        </svg>
                                    </button>
                                    <button @click="switchView('download')" type="button" class="btn btn-sm btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-down" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z"/>
                                            <path fill-rule="evenodd" d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div v-if="results != null" class="table-responsive pb-3">
                                    <template v-if="currentView === 'chart'">
                                        <ReportChart :results="results.pre" title="Pre Audit Report" bg="#0d6efd" />
                                        <hr/>
                                        <ReportChart :results="results.post" title="Post Audit Report" bg="#198754" />
                                        <hr/>
                                        <ReportChart :results="results.total" title="Totals Report" bg="#0dcaf0" />
                                    </template>
                                    <template v-else>
                                        <ReportGrid :tableHeader="gridReportTitle('Pre')" :results="results.pre" title="Pre Report" bg="#0dcaf0" tableId="reports_pre" />
                                        <hr/>
                                        <ReportGrid :tableHeader="gridReportTitle('Post')" :results="results.post" title="Post Report" bg="#0dcaf0" tableId="reports_post" />
                                        <hr/>
                                        <ReportGrid :tableHeader="gridReportTitle('Totals')" :results="results.total" title="Totals Report" bg="#0dcaf0" tableId="reports_total" />
                                        <hr/>
                                    </template>
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
import ReportChart from "../Components/ReportChart";
import ReportGrid from "../Components/ReportGrid";

export default {
    name: 'Reports',
    components: {
        BreezeAuthenticatedLayout, Head, Link, BreezeInput, BreezeSelect, BreezeLabel, BreezePagination, BreezeButton, ReportChart, ReportGrid
    },
    props: {
        results: Object,
        start: String,
        end: String
    },
    data() {
        return {
            reportForm: {
                inputEndDate: '2022-08-04',
                inputStartDate: '1990-08-04',
                type: 'prevented',
            },
            currentView: 'chart',
            showSuccessMsg: false,
        }
    },
    methods: {
        switchView: function (viewType)
        {
            this.currentView = viewType;
            if(viewType === 'download'){
                this.currentView = 'grid';
                let vm = this;
                setTimeout(function (){
                    vm.downloadTableAsCsv('reports_pre');
                    vm.downloadTableAsCsv('reports_post');
                    vm.downloadTableAsCsv('reports_total');
                }, 1000);
            }
        },
        changeType: function (type){
            this.reportForm.type = type;
        },
        reportFormSubmit: function (){
            if(this.reportForm.inputEndDate === '' || this.reportForm.inputStartDate === '') return false;

            let activeUsersForm = useForm(this.reportForm);
            activeUsersForm.post('/vss/reports', {
                onSuccess: (response) => {

                    //this.results = response.data.results;

                },
                preserveState: false
            });
        },
        // Quick and simple export target #table_id into a csv
        downloadTableAsCsv: function(table_id, separator = ',') {
            // Select rows from table_id
            let rows = document.querySelectorAll('table#' + table_id + ' tr');
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
            let filename = 'export_' + table_id + '_' + new Date().toLocaleDateString() + '.csv';
            let link = document.createElement('a');
            link.style.display = 'none';
            link.setAttribute('target', '_blank');
            link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },
        gridReportTitle: function (type){
            if (this.reportForm.type === 'prevented'){
                return 'VSS ' + type + ' Report for Prevented Awards from ' + this.start + ' to ' + this.end;
            }
            return 'VSS ' + type + ' Report for Over Award from ' + this.start + ' to ' + this.end;
        }

    },
    mounted() {
        if(this.start !== undefined){
            this.reportForm.inputStartDate = this.start;
        }
        if(this.end !== undefined){
            this.reportForm.inputEndDate = this.end;
        }
    }
}
</script>
