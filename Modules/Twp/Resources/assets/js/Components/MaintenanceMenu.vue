<template>

    <div class="list-group">
        <Link href="/twp/maintenance/staff" class="list-group-item list-group-item-action" :class="page.split('staff').length > 1 ? 'active' : ''">Staff</Link>
        <Link href="/twp/maintenance/institutions" class="list-group-item list-group-item-action" :class="page.split('institutions').length > 1 ? 'active' : ''">Institutions</Link>
        <Link href="/twp/maintenance/reasons" class="list-group-item list-group-item-action" :class="page.split('reasons').length > 1 ? 'active' : ''">Denial Reasons</Link>
        <Link href="/twp/maintenance/indigeneity" class="list-group-item list-group-item-action" :class="page.split('indigeneity').length > 1 ? 'active' : ''">Indigeneity Types</Link>
        <Link href="/twp/maintenance/payments" class="list-group-item list-group-item-action" :class="page.split('payments').length > 1 ? 'active' : ''">Payment Types</Link>
        <Link href="/twp/maintenance/utils" class="list-group-item list-group-item-action" :class="page.split('utils').length > 1 ? 'active' : ''">Utils</Link>
        <Link href="/twp/maintenance/reports" class="list-group-item list-group-item-action" :class="page.split('reports').length > 1 ? 'active' : ''">Reports</Link>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    page: String,
});

</script>
