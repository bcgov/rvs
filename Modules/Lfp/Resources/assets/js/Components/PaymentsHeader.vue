<template>
    <tr>
        <th scope="col">
            <span>Last Name</span>
        </th>
        <th scope="col">
            <span>First Name</span>
        </th>
        <th scope="col" style="min-width: 130px;">
            <a href="#" @click="switchSort('profession')">
                <span>Profession</span>
                <em v-if="sortClmn === 'profession' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>

        <th scope="col" style="min-width: 130px;">
            <a href="#" @click="switchSort('anniversary_date')">
                <span>Anniv. Date</span>
                <em v-if="sortClmn === 'anniversary_date' && sortType === 'desc'" class="bi bi-sort-numeric-up"></em>
                <em v-else class="bi bi-sort-numeric-down"></em>
            </a>
        </th>

        <th scope="col" style="min-width: 130px;">
            <a href="#" @click="switchSort('sfas_pay_status')">
                <span>SFAS Status</span>
                <em v-if="sortClmn === 'sfas_pay_status' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col" style="min-width: 135px;">
            <a href="#" @click="switchSort('oc_pay_status')">
                <span>OShift Status</span>
                <em v-if="sortClmn === 'oc_pay_status' && sortType === 'desc'" class="bi bi-sort-alpha-up"></em>
                <em v-else class="bi bi-sort-alpha-down"></em>
            </a>
        </th>
        <th scope="col" style="min-width: 130px;">
            <a href="#" @click="switchSort('proposed_pay_date')">
                <span>Prop. Date</span>
                <em v-if="sortClmn === 'proposed_pay_date' && sortType === 'desc'" class="bi bi-sort-numeric-up"></em>
                <em v-else class="bi bi-sort-numeric-down"></em>
            </a>
        </th>
        <th scope="col" style="min-width: 130px;">
            <a href="#" @click="switchSort('proposed_pay_amount')">
                <span>Prop. $$</span>
                <em v-if="sortClmn === 'proposed_pay_amount' && sortType === 'desc'" class="bi bi-sort-numeric-up"></em>
                <em v-else class="bi bi-sort-numeric-down"></em>
            </a>
        </th>
        <th scope="col" style="min-width: 130px;">
            <span>Remove Flag</span>
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
            sortClmn: 'anniversary_date',
            sortType: 'desc',
            url: '',
            path: '/lfp/payments'
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
                'direction': this.sortType,
                'sort': this.sortClmn
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
