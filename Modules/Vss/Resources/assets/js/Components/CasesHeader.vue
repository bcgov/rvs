<template>
    <tr>
        <th scope="col">
            <a href="#" @click="switchSort('sin')">
                <span>SIN</span>
                <em v-if="sortClmn === 'sin' && sortType === 'desc'" class="bi bi-sort-numeric-up"></em>
                <em v-else class="bi bi-sort-numeric-down"></em>
            </a>
        </th>
        <th scope="col">
            <a href="#" @click="switchSort('first_name')">
                <span>Name</span>
                <em v-if="sortClmn === 'first_name' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col">
            <a href="#" @click="switchSort('institution_code')">
                <span>Inst. Code</span>
                <em v-if="sortClmn === 'institution_code' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col" style="min-width: 120px;">
            <a href="#" @click="switchSort('open_date')">
                <span>Date Open</span>
                <em v-if="sortClmn === 'open_date' && sortType === 'desc'" class="bi bi-sort-numeric-up"></em>
                <em v-else class="bi bi-sort-numeric-down"></em>
            </a>
        </th>
        <th scope="col" style="min-width: 130px;">
            <a href="#" @click="switchSort('reactivate_date')">
                <span>Date Reactiv.</span>
                <em v-if="sortClmn === 'reactivate_date' && sortType === 'desc'" class="bi bi-sort-numeric-up"></em>
                <em v-else class="bi bi-sort-numeric-down"></em>
            </a>
        </th>
        <th scope="col" style="min-width: 100px;">
            <a href="#" @click="switchSort('incident_status')">
                <span>Status</span>
                <em v-if="sortClmn === 'incident_status' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col" style="min-width: 100px;">
            <a href="#" @click="switchSort('auditor_user_id')">
                <span>Auditor</span>
                <em v-if="sortClmn === 'auditor_user_id' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col"></th>
    </tr>
</template>
<script>

import {Inertia} from "@inertiajs/inertia";

export default {
    name: 'CasesHeader',
    components: {},
    props: {},
    data() {
        return {
            sortClmn: 'open_date',
            sortType: 'asc',
            url: '',
            path: 'cases.index',
        }
    },
    mounted() {
        this.url = new URL(document.location);
        this.sortClmn = this.url.searchParams.get("sort");
        this.sortType = this.url.searchParams.get("direction");

        if (this.url.pathname === '/dashboard') {
            this.path = 'dashboard';
        }

        let search = this.url.pathname.split('case-search/');
        if (search.length > 1) {
            this.path = search[1];
        }
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

            Inertia.get('/vss/' + this.path, data, {
                preserveState: true
            });

        },
    }
};
</script>
