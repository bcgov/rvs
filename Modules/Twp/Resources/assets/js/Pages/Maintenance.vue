<style scoped>
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>
    <Head title="Maintenance" />

    <BreezeAuthenticatedLayout v-bind="$attrs">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                TWP - Tuition Waiver Program
            </h2>
        </template>

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
                        <MaintenanceStaff v-if="page === 'staff'" :results="results"></MaintenanceStaff>
                        <MaintenanceStaffEdit v-if="page === 'staff-edit'" :results="results"></MaintenanceStaffEdit>
                        <MaintenanceReasons v-if="page === 'reasons'" :results="results"></MaintenanceReasons>
                        <MaintenanceIndigeneityTypes v-if="page === 'indigeneity'" :results="results"></MaintenanceIndigeneityTypes>
                        <MaintenanceInstitutions v-if="page === 'institutions'" :results="results"></MaintenanceInstitutions>
                        <MaintenancePaymentTypes v-if="page === 'payments'" :results="results"></MaintenancePaymentTypes>
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
import MaintenanceStaff from "../Components/MaintenanceStaff";
import MaintenanceStaffEdit from "../Components/MaintenanceStaffEdit";
import MaintenanceReasons from "../Components/MaintenanceReasons";
import MaintenanceInstitutions from "../Components/MaintenanceInstitutions";
import MaintenanceIndigeneityTypes from "../Components/MaintenanceIndigeneityTypes.vue";
import MaintenancePaymentTypes from "../Components/MaintenancePaymentTypes";
import MaintenanceReports from "../Components/MaintenanceReports";

export default {
    name: 'Maintenance',
    components: {
        MaintenanceReports,
        MaintenancePaymentTypes,
        MaintenanceInstitutions,
        MaintenanceIndigeneityTypes,
        MaintenanceReasons,
        MaintenanceMenu,
        BreezeAuthenticatedLayout, Head, Link, MaintenanceStaff, MaintenanceStaffEdit
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
