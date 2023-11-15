<template>
    <tr>
        <th scope="col">
            <a href="#" @click="switchSort('name')">
                <span>School Name</span>
                <em v-if="sortClmn === 'name' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col">
            <a href="#" @click="switchSort('city')">
                <span>City</span>
                <em v-if="sortClmn === 'city' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>


        <th scope="col"></th>
    </tr>
</template>
<script>

import {Inertia} from "@inertiajs/inertia";

export default {
    name: 'SchoolsHeader',
    components: {},
    props: {},
    data() {
        return {
            sortClmn: 'name',
            sortType: 'asc',
            url: '',
            path: '/neb/institutions',
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
    }
};
</script>
