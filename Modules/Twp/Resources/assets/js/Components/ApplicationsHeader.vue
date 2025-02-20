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
            <a href="#" @click="switchSort('alias_name')">
                <span>Alias Name</span>
                <em v-if="sortClmn === 'alias_name' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col" style="min-width: 130px;">
            <a href="#" @click="switchSort('birth_date')">
                <span>Date of Birth</span>
                <em v-if="sortClmn === 'birth_date' && sortType === 'desc'" class="bi bi-sort-numeric-up"></em>
                <em v-else class="bi bi-sort-numeric-down"></em>
            </a>
        </th>

        <th scope="col" style="min-width: 130px;">
            <a href="#" @click="switchSort('pen')">
                <span>PEN #</span>
                <em v-if="sortClmn === 'pen' && sortType === 'desc'" class="bi bi-sort-numeric-up"></em>
                <em v-else class="bi bi-sort-numeric-down"></em>
            </a>
        </th>
        <th scope="col" style="min-width: 130px;">
            <a href="#" @click="switchSort('sin')">
                <span>SIN #</span>
                <em v-if="sortClmn === 'sin' && sortType === 'desc'" class="bi bi-sort-numeric-up"></em>
                <em v-else class="bi bi-sort-numeric-down"></em>
            </a>
        </th>
        <th scope="col" style="min-width: 100px; max-width: 250px;">
            <BreezeSelect @change="switchStatusSort($event)" class="form-select" v-model="appStatusSort">
                <option value="ALL">App Status - All</option>
                <option value="APPROVED ON APPEAL">App Status - Approved on Appeal (legacy)</option>
                <option v-for="status in $attrs.utils['Application Status']" :key="status.id" :value="status.field_name">
                    {{ toTitleCase(status.field_name) }}
                </option>
            </BreezeSelect>

        </th>
    </tr>
</template>
<script>

import {Inertia} from "@inertiajs/inertia";
import BreezeSelect from "@/Components/Select";

export default {
    name: 'ApplicationsHeader',
    components: {BreezeSelect},
    props: {
        toTitleCase: Function
    },
    data() {
        return {
            sortClmn: 'last_name',
            sortType: 'asc',
            url: '',
            path: '/twp/application-list',
            appStatusSort: 'ALL',
        }
    },
    mounted() {
        this.url = new URL(document.location);
        this.sortClmn = this.url.searchParams.get("sort");
        this.sortType = this.url.searchParams.get("direction");
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

            this.sortClmn = 'app_status';
            this.sortType = this.appStatusSort;


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
    }
};
</script>
