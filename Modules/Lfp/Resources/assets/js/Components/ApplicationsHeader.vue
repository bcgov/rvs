<template>
    <tr>
        <th scope="col">Last Name</th>
        <th scope="col">First Name</th>
        <th scope="col" style="min-width: 130px;">Profession</th>
        <th scope="col" style="min-width: 130px;">Date of Birth</th>
        <th scope="col" style="min-width: 130px;">Receive Date</th>
        <th scope="col" style="min-width: 130px;">Status</th>
        <th scope="col" style="min-width: 130px;">Inactive</th>
    </tr>
</template>
<script>
import BreezeSelect from "@/Components/Select";

export default {
    name: 'ApplicationsHeader',
    components: {BreezeSelect},
    props: {},
    data() {
        return {
            url: '',
        }
    },
    mounted() {
        this.url = new URL(document.location);
    },
    methods: {

    }
};
</script>
