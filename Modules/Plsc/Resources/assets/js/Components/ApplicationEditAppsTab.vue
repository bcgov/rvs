<template>
    <div class="card">
        <div class="card-body">
            <p>Select SABC apps associated with this LFP:</p>
            <div class="row">
                <div class="col-md-2" v-for="(app, i) in apps">
                    <div class="form-check">
                        <label :for="'sabcApp'+i" class="form-check-label">{{ app.application_number }}</label>
                        <input type="checkbox" class="form-check-input" :id="'sabcApp'+i" @change="updateApps($event, i)" />
                    </div>
                </div>


            </div>
            <hr/>
            <div class="accordion" id="accordionSabcApps">
                <div v-for="(app, i) in apps" class="accordion-item">
                    <h2 class="accordion-header" :id="'heading'+i">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse'+i" aria-expanded="false" :aria-controls="'collapse'+i">
                            {{ app.application_number }}
                        </button>
                    </h2>
                    <div :id="'collapse'+i" class="accordion-collapse collapse" :aria-labelledby="'heading'+i" data-bs-parent="#accordionSabcApps">
                        <div class="accordion-body">
                            <div class="row g-3">
                                <div v-for="(c,j,k) in app" class="col-md-3">
                                    <BreezeLabel :for="i+'inputSabcApp'+k" class="form-label" :value="j" />
                                    <BreezeInput type="text" class="form-control" :id="i+'inputSabcApp'+k" :value="c" readonly disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';

export default {
    name: 'ApplicationEditAppsTab',
    components: {
        BreezeInput, BreezeLabel,
    },
    props: {
        result: Object,
        apps: Object
    },
    data() {
        return {
            noChanges: true,
        }
    },
    methods: {
        updateApps: function(e, index){
            let url = '/lfp/applications/remove-app';
            let data = {
                lfp_id: this.result.id,
                application_number: this.apps[index].application_number
            }

            //add app
            if(e.target.checked){
                url = '/lfp/applications/connect-app';
            }

            axios.post(url, data)
                .then(function (response) {
                    // handle success
                    // console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });

        }
    }
}

</script>
