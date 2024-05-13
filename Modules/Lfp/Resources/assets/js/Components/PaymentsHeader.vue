<template>
    <tr>
        <th scope="col">
            <span>Last Name</span>
        </th>
        <th scope="col">
            <span>First Name</span>
        </th>
        <th scope="col" style="min-width: 130px;">
            <span>Profession</span>
        </th>

        <th scope="col" style="min-width: 130px;">
            <span>Anniv. Date</span>
        </th>

        <th scope="col" style="min-width: 130px;">
            <span>SFAS Status</span>
        </th>
        <th scope="col" style="min-width: 130px;">
            <span>Prop. Date</span>
        </th>
        <th scope="col" style="min-width: 130px;">
            <span>Prop. $$</span>
        </th>

    </tr>
</template>
<script>

import {Inertia} from "@inertiajs/inertia";
import BreezeSelect from "@/Components/Select";

export default {
    name: 'PaymentsHeader',
    components: {BreezeSelect},
    props: {},
    data() {
        return {
            sortClmn: 'receive_date',
            sortType: 'asc',
            url: '',
            path: '/lfp/payments',
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

            this.sortClmn = 'status';
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
