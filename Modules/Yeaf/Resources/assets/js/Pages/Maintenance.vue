<style scoped>
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>
    <Head title="Maintenance" />

    <BreezeAuthenticatedLayout v-bind="$attrs">

        <div class="mt-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 mt-3">
                        <div class="card">
                            <div class="card-header">
                                Maintenance Menu
                            </div>
                            <div class="card-body">
                                <MaintenanceMenu :page="page" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 mt-3">
                        <MaintenanceMinistry v-if="page === 'ministry'" :results="results" :provinces="provinces"></MaintenanceMinistry>
                        <MaintenanceStaff v-if="page === 'staff'" :results="results"></MaintenanceStaff>
                        <MaintenanceStaffEdit v-if="page === 'staff-edit'" :results="results"></MaintenanceStaffEdit>
                        <MaintenanceIneligibles v-if="page === 'ineligibles'" :results="results"></MaintenanceIneligibles>
                        <MaintenanceProgramYears v-if="page === 'program-years'" :results="results"></MaintenanceProgramYears>
                        <MaintenanceBatches v-if="page === 'batches'" :results="results"></MaintenanceBatches>
                        <MaintenanceLetters v-if="page === 'letters'" :program_years="program_years" :batches="batches"></MaintenanceLetters>
                        <MaintenanceReports v-if="page === 'reports'" :results="results"></MaintenanceReports>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>

</template>
<script>
import BreezeAuthenticatedLayout from '../Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/vue3';
import MaintenanceMenu from "../Components/MaintenanceMenu";
import MaintenanceMinistry from "../Components/MaintenanceMinistry";
import MaintenanceStaff from "../Components/MaintenanceStaff";
import MaintenanceStaffEdit from "../Components/MaintenanceStaffEdit";
import MaintenanceReports from "../Components/MaintenanceReports";
import MaintenanceLetters from "../Components/MaintenanceLetters";
import MaintenanceIneligibles from "../Components/MaintenanceIneligibles";
import MaintenanceProgramYears from "../Components/MaintenanceProgramYears";
import MaintenanceBatches from "../Components/MaintenanceBatches";

export default {
    name: 'Maintenance',
    components: {
        MaintenanceProgramYears,
        MaintenanceBatches,
        MaintenanceIneligibles,
        MaintenanceReports,
        MaintenanceLetters,
        MaintenanceMenu,
        BreezeAuthenticatedLayout, Head, Link, MaintenanceMinistry, MaintenanceStaff, MaintenanceStaffEdit
    },
    props: {
        results: Object,
        page: String,
        provinces: Object,
        program_years: Object,
        batches: Object,
    },
    data() {
        return {
        }
    },
}
</script>
