<style scoped>
#countdown {
    font-size: 5em;
    text-align: center;
    animation: bounce 0.5s ease-in-out infinite alternate;
}

@keyframes bounce {
    from {
        transform: translateY(-10px);
    }
    to {
        transform: translateY(0);
    }
}

</style>
<template>
    <div class="card mb-4">
        <div class="card-header">Ministry Reports</div>
        <div class="card-body">

            <button type="button" class="btn btn-primary w-100 mb-3" @click="runReports">Enable Reports</button><br/>
            <div id="countdown" style="display: none;">60</div>

        </div>


    </div>

</template>
<script>
import {Link} from '@inertiajs/vue3';
import axios from "axios";

export default {
    name: 'MaintenanceReports',
    components: {
        Link
    },
    props: {
    },
    data() {
        return {
            seconds: 60,
        }
    },
    methods: {
        runReports: function ()
        {
            let vm = this;
            axios({
                url: '/yeaf/maintenance/reports/switch',
                method: 'post',
                headers: {'Accept': 'application/json'}
            })
                .then(function (response) {
                    let countdown = document.getElementById('countdown');
                    countdown.textContent = vm.seconds;
                    countdown.style.display = 'block';

                    const intervalId = setInterval(() => {

                        countdown.textContent = --vm.seconds;
                        if (vm.seconds === 0) {
                            clearInterval(intervalId);
                            countdown.style.display = 'none';
                            vm.seconds = 60;
                        }
                    }, 1000);


                })
                .catch(function (error) {
                });
        },
    }
}

</script>
