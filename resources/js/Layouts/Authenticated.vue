<template>
    <div>
        <div v-if="$attrs.auth.disabled === true" class="card text-center">
            <h2 class="card-body">You are not authorized.</h2>
        </div>
        <div v-else class="min-h-screen bg-gray-100">

            <main>
                <div class="mt-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 mt-3 ">

                                <slot />

                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
</template>
<script setup>

defineProps({
    loginAttempt: Boolean,
    hasAccess: Boolean,
    status: String,
});
// defineComponent( {
//     Head
// });
</script>
