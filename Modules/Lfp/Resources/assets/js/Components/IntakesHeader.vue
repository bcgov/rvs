<template>
    <tr>
        <th scope="col">Last Name</th>
        <th scope="col">First Name</th>
        <th scope="col" style="min-width: 130px;">In Good Standing</th>
        <th scope="col" style="min-width: 130px;">Status</th>
        <th scope="col" style="min-width: 130px;">Status</th>
    </tr>
</template>
<script>
import BreezeSelect from "@/Components/Select";

export default {
    name: 'IntakesHeader',
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
