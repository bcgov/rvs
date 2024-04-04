<template>
    <tr>
        <th scope="col">
            <a href="#" @click="switchSort('last_name')">
                <span>Last Name</span>
                <em v-if="sortClmn === 'last_name' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col">
            <a href="#" @click="switchSort('first_name')">
                <span>First Name</span>
                <em v-if="sortClmn === 'first_name' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col">
            <a href="#" @click="switchSort('in_good_standing')">
                <span>In Good Standing</span>
                <em v-if="sortClmn === 'in_good_standing' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col" style="min-width: 130px;">
            <span class="float-start mt-1 me-1">Status: </span>
            <select class="form-select form-select-sm w-50" @change="switchStatusSort" v-model="appStatusSort">
                <option value="all">All</option>
                <option value="Pending">Pending</option>
                <option value="Ready">Ready</option>
                <option value="Completed">Completed</option>
                <option value="Denied">Denied</option>
            </select>
        </th>
        <th scope="col">
            <a href="#" @click="switchSort('proposed_registration_date')">
                <span>Prop. Reg. Date</span>
                <em v-if="sortClmn === 'proposed_registration_date' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col">
            <a href="#" @click="switchSort('receive_date')">
                <span>Receive Date</span>
                <em v-if="sortClmn === 'receive_date' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>

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
            sortClmn: 'last_name',
            sortType: 'asc',
            url: '',
            path: 'intakes',
            appStatusSort: 'all',

        }
    },
    mounted() {
        this.url = new URL(document.location);
        this.sortClmn = this.url.searchParams.get("sort");
        this.sortType = this.url.searchParams.get("direction");
        this.url.searchParams.forEach((value, key) => {
            if(key === 'filter_status') {
                this.appStatusSort = value;
            }
        });
    },
    methods: {
        switchSort: function (clmn) {
            if (clmn === this.sortClmn) {
                if (this.sortType === 'asc') {
                    this.sortType = 'desc';
                } else {
                    this.sortType = 'asc';
                }
            } else {
                this.sortClmn = clmn;
                this.sortType = 'asc';
            }

            let data = {
                'sort': this.sortClmn,
                'direction': this.sortType
            };

            //if the url has filter_x params then append them all
            this.url.searchParams.forEach((value, key) => {
                let filter = key.split('filter_');
                if(filter.length > 1) {
                    data[key] = value;
                }
            });

            Inertia.get(this.path, data, {
                preserveState: true
            });

        },

        switchStatusSort: function (e) {

            let data = {
                'filter_status': this.appStatusSort
            };

            //if the url has filter_x params then append them all
            this.url.searchParams.forEach((value, key) => {
                let filter = key.split('filter_');
                if(filter.length > 1) {
                    data[key] = value;
                }
            });

            Inertia.get(this.path, data, {
                preserveState: true
            });

        },
    }
};
</script>
