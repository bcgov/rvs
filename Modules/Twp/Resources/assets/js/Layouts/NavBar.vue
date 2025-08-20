<style scoped>
nav.navbar{
    background-color: #003366;
    border: none;
    border-bottom: 2px solid #fcba19;
    z-index: 99;
}
</style>
<template>
    <nav class="navbar navbar-expand-lg navbar-dark shadow">
        <div class="container-fluid">
            <Link class="navbar-brand" href="/twp/students">
                <BreezeApplicationLogo width="126" height="34" class="d-inline-block align-text-top me-3" />
                <span class="d-none d-lg-inline">TWP - Tuition Waiver Program</span>
            </Link>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav flex-row flex-wrap ms-md-auto" style="--bs-scroll-height: 100px;">
                    <li class="nav-item">
                        <BreezeNavLink class="nav-link" href="/twp/students" :active="page.url.indexOf('students') > -1">
                            Students
                        </BreezeNavLink>
                    </li>
                    <li class="nav-item">
                        <BreezeNavLink class="nav-link" href="/twp/application-list" :active="page.url.indexOf('application-list') > -1">
                            Applications
                        </BreezeNavLink>
                    </li>
                    <li v-if="isAdmin" class="nav-item">
                        <BreezeNavLink class="nav-link" href="/twp/maintenance/staff" :active="page.url.indexOf('maintenance') > -1">
                            Maintenance
                        </BreezeNavLink>
                    </li>
                    <li class="nav-item dropdown">
                        <BreezeNavLink class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ page.props.auth.user.user_id }}
                        </BreezeNavLink>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarScrollingDropdown">
                            <li class="dropdown-item px-4">
                                <div class="font-medium text-sm text-gray-500">{{ page.props.auth.user.email }}</div>
                            </li>
                            <li><hr class="dropdown-divider"></li>

                            <li class="dropdown-item mt-3 space-y-1">
                                <div class="d-grid gap-2">
                                <BreezeResponsiveNavLink class="text-left" href="/logout" method="post" as="button">
                                    Log Out
                                </BreezeResponsiveNavLink>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
import {computed, ref} from 'vue';
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';
import BreezeNavLink from '@/Components/NavLink.vue';
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

export default {
    name: 'NavBar',
    components: {
        BreezeApplicationLogo, BreezeResponsiveNavLink, BreezeNavLink, Link
    },
    props: [],
    data() {
        return {
            showingNavigationDropdown: ref(false),
            searchType: '',
            searchData: '',
            isAdmin: ref(false),
        }
    },
    methods: {
    },
    setup() {
        const page = usePage();
        const utils = computed(() => page.props.utils || {});

        return {
            page,
            utils
        };
    },
    mounted() {
        const roles = this.page?.props?.auth?.roles || [];
        this.isAdmin = roles.some(role => role?.name?.includes('Admin'));
    },
}
</script>
