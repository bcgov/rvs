<template>

    <div class="list-group">
        <Link href="/plsc/maintenance/staff" class="list-group-item list-group-item-action" :class="page.split('staff').length > 1 ? 'active' : ''">Staff</Link>
        <Link href="/plsc/maintenance/institutions" class="list-group-item list-group-item-action" :class="page.split('institutions').length > 1 ? 'active' : ''">Institutions</Link>
        <Link href="/plsc/maintenance/utils" class="list-group-item list-group-item-action" :class="page.split('utils').length > 1 ? 'active' : ''">Utils</Link>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    page: String,
});

</script>
