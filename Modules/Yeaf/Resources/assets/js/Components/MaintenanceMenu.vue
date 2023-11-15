<template>

    <div class="list-group">
        <Link :href="'/yeaf/maintenance/ministry'" class="list-group-item list-group-item-action" :class="page === 'ministry' ? 'active' : ''">Ministry</Link>
        <Link :href="'/yeaf/maintenance/staff'" class="list-group-item list-group-item-action" :class="page.split('staff').length > 1 ? 'active' : ''">Staff</Link>
        <Link :href="'/yeaf/maintenance/ineligibles'" class="list-group-item list-group-item-action" :class="page.split('ineligibles').length > 1 ? 'active' : ''">Ineligible Reasons</Link>
        <Link :href="'/yeaf/maintenance/program'" class="list-group-item list-group-item-action" :class="page.split('program-years').length > 1 ? 'active' : ''">Program Years</Link>
        <Link :href="'/yeaf/maintenance/batches'" class="list-group-item list-group-item-action" :class="page.split('batches').length > 1 ? 'active' : ''">Batches</Link>
        <Link :href="'/yeaf/maintenance/reports'" class="list-group-item list-group-item-action" :class="page === 'reports' ? 'active' : ''">Reports</Link>
        <Link :href="'/yeaf/maintenance/letters'" class="list-group-item list-group-item-action" :class="page === 'letters' ? 'active' : ''">Cover Letters</Link>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    page: String,
});

</script>
