<script>
import GuestLayout from '@/Layouts/Guest.vue';
import {Head} from '@inertiajs/vue3';

export default {
    name: 'Login',
    components: {
        GuestLayout, Head
    },
    props: {
        loginAttempt: Boolean,
        hasAccess: Boolean,
        status: String,
    }
}
</script>

<style scoped>
.btn-bc-gov{
    background-image: none;
    background-color: #003366;
    border: none;
    border-radius: 4px;
    color: white;
    text-align: center;
    text-decoration: none;
    display: block;
    font-weight: 700;
    letter-spacing: 1px;
    cursor: pointer;
    max-width: 150px;
    margin-left: auto;
    margin-right: auto;
}
.btn-bc-gov:hover{
    background: #ededed !important;
    background-image: none !important;
    text-decoration: underline !important;
    opacity: 0.80 !important;
    color: #036 !important;
}
</style>
<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>


        <div class="">
            <p>Authenticate with:</p>
            <hr/>
            <a href="/applogin" class="btn btn-bc-gov">IDIR</a>
        </div>
    </GuestLayout>
</template>
