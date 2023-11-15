<template>
    <div class="mt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            VSS Case Search
                        </div>
                        <div class="card-body">
                            <CaseSearchBox @search="emitSearch" />
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        HI
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { ref } from 'vue';
import CaseSearchBox from '../Components/CaseSearch.vue';

// const showingNavigationDropdown = ref(false);

export default {
    name: 'BodyContainer',
    components: {
        CaseSearchBox
    },
    props: [],
    data() {
        return {
            showingNavigationDropdown: ref(false),
            searchType: '',
            searchData: '',
            page: '',
        }
    },
    methods: {
        emitSearch: function (data){
            this.$emit('search', data);
        }
    }
}
</script>
