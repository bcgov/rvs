<script setup>

import {Head} from '@inertiajs/vue3';
import {defineComponent} from "vue";
defineComponent( {
    Head
});
defineProps({
    loginAttempt: Boolean,
    hasAccess: Boolean,
    status: String,
});
</script>
<style scoped>
.bg-bc-gov{
    background: none;
    background-color: #036;
    font-family: 'BCSans', 'Noto Sans', Verdana, Arial, sans-serif;
    color: #036;
}
.box-disabled{
    text-decoration: none;
    background-color: #e0e0e0;
    opacity: 0.7;
    color: #888;
    cursor: not-allowed;
}
</style>
<template>
        <Head title="Select Application" />

    <div class="min-h-screen text-center flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-bc-gov">

        <h2 class="text-center text-white">Welcome <span v-if="$attrs['auth']['user']">{{ $attrs['auth']['user']['first_name'] }}</span></h2>
        <p class="text-center text-white">Please select an application to log into</p>

        <div v-if="status" class="alert mb-4 font-medium text-sm alert-danger">
            {{ status }}
        </div>

        <div class="row col-12 m-3 justify-content-center">
            <div class="col-md-3">
                <a href="/yeaf/students" class="card p-5 text-dark" style="text-decoration:none;">
                    <h1 class="display-3 font-sans font-light">YEAF</h1>
                    <span>Youth Educational Assistance Fund</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="/twp/students" class="card p-5 text-dark" style="text-decoration:none;">
                    <h1 class="display-3 font-sans font-light">TWP</h1>
                    <span>Tuition Waiver Program</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="/vss/dashboard" class="card p-5 text-dark" style="text-decoration:none;">
                    <h1 class="display-3 font-sans font-light">VSS</h1>
                    <span>Verification Statistics System</span>
                </a>
            </div>
        </div>
        <div class="row col-12 m-3 justify-content-center">

            <div class="col-md-3">
                <a href="/neb/dashboard" class="card p-5" style="text-decoration:none;">
                    <h1 class="display-3 font-sans font-light">NEB</h1>
                    <span>Nurses Education Bursary</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="/lfp/dashboard" class="card p-5" style="text-decoration:none;">
                    <h1 class="display-3 font-sans font-light">BCLFP</h1>
                    <span>Loan Forgiveness Program</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="#" class="card p-5 box-disabled" style="text-decoration:none;">
                    <h1 class="display-3 font-sans font-light">PLSCPS</h1>
                    <span>Pacific Leaders Scholarship for Children of Public Servants</span>
                </a>
            </div>
        </div>
    </div>
</template>
