<template>
    <div class="card">
        <div class="card-header">
            <div>Cover Letters Maintenance</div>
        </div>

        <div class="card-body mb-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Download Year End Report Letter</div>
                        <div class="card-body row">
                            <div class="col-md-8">
                                <BreezeSelect class="form-select" id="printYearEnd" v-model="selectedPy">
                                    <option value="">Select a Program Year</option>
                                    <option v-for="py in program_years" :value="py.id">{{ py.year_start }}/{{ py.year_end }}</option>
                                </BreezeSelect>
                            </div>
                            <div class="col-md-4"><button @click="downloadYearEnd" type="button" class="btn btn-success w-100">Submit</button> </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Download Award Recommendations Letter</div>
                        <div class="card-body row">
                            <div class="col-md-8">
                                <BreezeSelect class="form-select" id="printBatch" v-model="selectedBatch">
                                    <option value="">Select a Batch</option>
                                    <option v-for="batch in batches" :value="batch.id">{{ batch.batch_human_date }}</option>
                                </BreezeSelect>
                            </div>
                            <div class="col-md-4"><button @click="downloadBatch" type="button" class="btn btn-success w-100">Submit</button> </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

</template>
<script>
import { Link } from '@inertiajs/vue3';
import BreezeSelect from '@/Components/Select.vue';

export default {
    name: 'MaintenanceLetters',
    components: {
        Link, BreezeSelect
    },
    props: {
        batches: Object,
        program_years: Object,
    },
    data() {
        return {
            selectedPy: '',
            selectedBatch: '',
        }
    },
    methods: {
        downloadYearEnd: function (){
            if(this.selectedPy === ''){
                return false;
            }

            window.open('/yeaf/maintenance/download-letters/py/' + this.selectedPy,'_blank');
        },
        downloadBatch: function (){
            if(this.selectedBatch === ''){
                return false;
            }

            window.open('/yeaf/maintenance/download-letters/batch/' + this.selectedBatch,'_blank');
        },
    },
}

</script>
