<template>
    <tr>
        <th scope="col">Last Name</th>
        <th scope="col">First Name</th>
        <th scope="col" style="min-width: 130px;">In Good Standing</th>
        <th scope="col" style="min-width: 130px;">
            <span class="float-start mt-1">Status: </span>
            <select class="float-end form-select form-select-sm w-50" @change="switchSort" v-model="filterStatus">
                <option value="all">All</option>
                <option value="Pending">Pending</option>
                <option value="Ready">Ready</option>
                <option value="Completed">Completed</option>
                <option value="Denied">Denied</option>
            </select>
        </th>
        <th scope="col" style="min-width: 130px;">Prop. Reg. Date</th>
        <th scope="col" style="min-width: 130px;">Receive Date</th>
    </tr>
</template>
<script>
import BreezeSelect from "@/Components/Select";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: 'IntakesHeader',
    components: {BreezeSelect},
    props: {},
    data() {
        return {
            url: '',
            path: 'intakes',
            filterStatus: 'all',
        }
    },
    mounted() {
        this.url = new URL(document.location);
        this.url.searchParams.forEach((value, key) => {
            if(key === 'filter_status') {
                this.filterStatus = value;
            }
        });
    },
    methods: {
        switchSort: function (e) {

            let data = {}
            if(e.target.value != 'all') {
                data = {
                    'filter_status': e.target.value
                };
            }

            //if the url has filter_x params then append them all
            this.url.searchParams.forEach((value, key) => {
                let filter = key.split('filter_');
                if(filter.length > 1 && key !== 'filter_status') {
                    data[key] = value;
                }
            });

            Inertia.get(/lfp/ + this.path, data, {
                preserveState: true
            });

        },
    }
};
</script>
