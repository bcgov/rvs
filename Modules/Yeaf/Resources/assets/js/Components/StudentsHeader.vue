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
                <span>First Name</span>
                <em v-if="sortClmn === 'first_name' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col">
            <a href="#" @click="switchSort('last_name')">
                <span>Last Name</span>
                <em v-if="sortClmn === 'last_name' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col" style="min-width: 120px;">
            <a href="#" @click="switchSort('life')">
                <span>Lifetime Award</span>
                <em v-if="sortClmn === 'life' && sortType === 'desc'" class="bi bi-sort-numeric-up"></em>
                <em v-else class="bi bi-sort-numeric-down"></em>
            </a>
        </th>

        <th scope="col" style="min-width: 130px;">Current Overaward</th>
        <th scope="col" style="min-width: 130px;">
            <a href="#" @click="switchSort('investigate')">
                <span>Under Investigation</span>
                <em v-if="sortClmn === 'investigate' && sortType === 'desc'" class="bi bi-sort-numeric-up"></em>
                <em v-else class="bi bi-sort-numeric-down"></em>
            </a>
        </th>

        <th scope="col"></th>
    </tr>
</template>
<script>

import {Inertia} from "@inertiajs/inertia";

export default {
    name: 'StudentsHeader',
    components: {},
    props: {},
    data() {
        return {
            sortClmn: 'sin',
            sortType: 'asc',
            url: '',
            path: 'students',
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

            Inertia.get(/yeaf/ + this.path, data, {
                preserveState: true
            });

        },
    }
};
</script>
