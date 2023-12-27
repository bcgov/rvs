<template>

    <div class="list-group">
        <Link href="/lfp/maintenance/staff" class="list-group-item list-group-item-action" :class="page.split('staff').length > 1 ? 'active' : ''">Staff</Link>
        <Link href="/lfp/maintenance/utils" class="list-group-item list-group-item-action" :class="page.split('utils').length > 1 ? 'active' : ''">Utils</Link>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    page: String,
});

</script>
