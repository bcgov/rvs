<template>
    <div class="card mb-3">
        <div class="card-header text-center">{{ title }}</div>
        <div class="card-body">
            <div v-if="results != null" class="table-responsive pb-3">
                <table :id="tableId" class="table table-striped">
                    <thead>
                    <tr class="d-none"><th colspan="14">{{tableHeader}}</th></tr>
                    <tr>
                        <th></th>
                        <template v-for="k in Object.keys(results['Age and Eligibility'])">
                            <th scope="col" v-if="k !== 'TOTAL'">{{ k }}</th>
                        </template>
                        <th>TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, i) in Object.entries(results)">
                        <td>{{row[0]}}</td>
                        <template v-for="k in Object.keys(results['Age and Eligibility'])">
                            <td v-if="k !== 'TOTAL'">{{ row[1][k] }}</td>
                        </template>
                        <td>{{ row[1]['TOTAL']}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <h1 v-else class="lead">No results</h1>
        </div>
    </div>
</template>
<script>


export default {
    name: 'ReportGrid',
    components: {
    },
    props: {
        results: Object,
        title: String,
        tableId: String,
        tableHeader: String,
    },
    data() {
        return {
        }
    },
    methods: {


    },
    watch: {
    },
    computed: {
    },
    mounted() {

    }
}
</script>
