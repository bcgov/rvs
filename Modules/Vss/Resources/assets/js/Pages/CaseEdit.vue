<style scoped>
tr {
    padding-bottom: 7px;
    display: block;
}
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>
        <Head title="VSS - Case Funding Edit" />

        <BreezeAuthenticatedLayout>

            <div class="mt-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    VSS Case Search
                                </div>
                                <div class="card-body">
                                    <CaseSearchBox />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 mt-3 mb-5">
                            <div class="card">
                                <div v-if="editForm != null" class="card-header">
                                    VSS Case Edit
                                    <span class="btn btn-sm rounded-pill text-bg-primary ms-2 disabled">{{ editForm.incident_status }}</span>
                                    <a :href="'/reports/download/' + editForm.id" class="btn rounded-pill btn-outline-secondary shadow-none float-end" data-bs-toggle="tooltip" data-bs-title="Download Student Report">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-down" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z"/>
                                            <path fill-rule="evenodd" d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>
                                        </svg>
                                    </a>
                                </div>
                                <template v-if="editForm != null">
                                    <form @submit.prevent="updateCase">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <table>
                                                        <tr>
                                                            <th scope="row">SIN:</th>
                                                            <td class="ps-1">{{ editForm.sin }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th scope="row">Year of Audit:</th>
                                                            <td class="ps-1">
                                                                <BreezeInput class="form-control" type="text" placeholder="i.e 21/22" maxlength="5" v-model="editForm.year_of_audit" aria-required="true" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Date Opened:</th>
                                                            <td class="ps-1">
                                                                <BreezeInput class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.open_date" />
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-lg-4">

                                                    <table>
                                                        <tr>
                                                            <th scope="row">Last Name:</th>
                                                            <td class="ps-1">
                                                                <BreezeInput class="form-control" type="text" v-model="editForm.last_name" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Application:</th>
                                                            <td class="ps-1">
                                                                <BreezeInput class="form-control" type="number" v-model="editForm.application_number" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Reactivate Date:</th>
                                                            <td class="ps-1">
                                                                <BreezeInput class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.reactivate_date" />
                                                            </td>
                                                        </tr>

                                                    </table>

                                                </div>
                                                <div class="col-lg-4">

                                                    <table>
                                                        <tr>
                                                            <th scope="row">First Name:</th>
                                                            <td class="ps-1">
                                                                <BreezeInput class="form-control" type="text" v-model="editForm.first_name" />
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th scope="row">School:</th>
                                                            <td class="ps-1">
<!--                                                                <BreezeSelect class="form-select" v-model="editForm.institution_code">-->
<!--                                                                    <option v-for="(school,j) in schools" :value="school.institution_code">{{ school.institution_name }} | {{ school.institution_code }}</option>-->
<!--                                                                </BreezeSelect>-->
                                                                <BreezeInput @focusout="resetSchoolFilter" @keyup="filterActiveSchools($event)" type="text" class="form-control" id="inputInstitution" v-model="editForm.institution.institution_name" />
                                                                <input type="hidden" v-model="editForm.institution_code" />
                                                                <ul class="dropdown-menu" :class="editForm.schoolsListHidden === false ? 'show' : 'hidden'" data-popper-placement="top-start" style="
    position: absolute;
    right: 0;
    overflow-y: scroll;
    height: 400px;
">
                                                                    <template v-for="(school,j) in schoolsList">
                                                                        <li @click="assignSchool(school, j)" :value="school.institution_code" class="dropdown-item">{{ school.institution_code }} | {{ school.institution_name }}</li>
                                                                    </template>
                                                                </ul>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Status Code:</th>
                                                            <td class="ps-1">
                                                                <BreezeSelect class="form-select" v-model="editForm.incident_status">
                                                                    <option value="Active">Active</option>
                                                                    <option value="Re-activated">Re-activated</option>
                                                                    <option value="Inactive">Inactive</option>
                                                                </BreezeSelect>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                </div>
                                            </div>

                                            <!-- break -->
                                            <hr>
                                            <!-- break -->

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <table>
                                                        <tr>
                                                            <th scope="row">Referral:</th>
                                                            <td class="ps-1">
                                                                <BreezeSelect class="form-select" v-model="editForm.referral_source_id">
                                                                    <option v-for="(ref,j) in referrals" :value="ref.id">{{ ref.description }}</option>
                                                                </BreezeSelect>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Severity:</th>
                                                            <td class="ps-1">
                                                                <BreezeSelect class="form-select" v-model="editForm.severity">
                                                                    <option value="A">Audit</option>
                                                                    <option value="I">Investigation</option>
                                                                </BreezeSelect>
                                                            </td>
                                                        </tr>

                                                    </table>
                                                </div>
                                                <div class="col-lg-4">

                                                    <table>
                                                        <tr>
                                                            <th scope="row">Auditor:</th>
                                                            <td class="ps-1">
                                                                <BreezeSelect class="form-select" v-model="editForm.auditor_user_id">
                                                                    <option value=""></option>
                                                                    <option v-for="(u,j) in staff" :value="u.user_id">{{ u.first_name }} {{ u.last_name }} | {{ u.user_id }}</option>
                                                                </BreezeSelect>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Aud. Date:</th>
                                                            <td class="ps-1">
                                                                <BreezeInput class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.audit_date" />
                                                            </td>
                                                        </tr>

                                                    </table>

                                                </div>
                                                <div class="col-lg-4">

                                                    <table>
                                                        <tr>
                                                            <th scope="row">Investigator:</th>
                                                            <td class="ps-1">
                                                                <BreezeSelect class="form-select" v-model="editForm.investigator_user_id">
                                                                    <option value=""></option>
                                                                    <option v-for="(u,j) in staff" :value="u.user_id">{{ u.first_name }} {{ u.last_name }} | {{ u.user_id }}</option>
                                                                </BreezeSelect>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th scope="row">Inv. Date:</th>
                                                            <td class="ps-1">
                                                                <BreezeInput class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.investigation_date" />
                                                            </td>
                                                        </tr>
                                                    </table>

                                                </div>
                                            </div>


                                            <!-- break -->
                                            <hr>
                                            <!-- break -->


                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <table>
                                                        <tr>
                                                            <th scope="row">Primary Area of Audit:</th>
                                                            <td class="ps-1">
                                                                <BreezeSelect class="form-select" v-model="editForm.area_of_audit_code">
                                                                    <option v-for="area in areaOfAudits" :value="area.area_of_audit_code">{{ area.description }}</option>
                                                                </BreezeSelect>
                                                            </td>
                                                        </tr>

                                                    </table>
                                                </div>
                                                <div class="col-lg-4">

                                                    <table>
                                                        <tr>
                                                            <th scope="row">Audit Type:</th>
                                                            <td class="ps-1">
                                                                <BreezeSelect class="form-select" v-model="editForm.audit_type">
                                                                    <option value="A">Pre-Audit</option>
                                                                    <option value="P">Post-Audit</option>
                                                                </BreezeSelect>
                                                            </td>
                                                        </tr>

                                                    </table>

                                                </div>

                                            </div>

                                            <!-- break -->
                                            <div class="card mt-3">
                                                <div class="card-header text-muted">Additional Areas of Audit</div>
                                                <div class="card-body d-grid gap-2">

                                                    <div v-for="(aud, i) in editForm.audits" class="row">
                                                        <div class="col-lg-8">
                                                            <table>
                                                                <tr>
                                                                    <th scope="row">Area of Audit:</th>
                                                                    <td class="ps-1">
                                                                        <BreezeSelect class="form-select" v-model="aud.area_of_audit_code">
                                                                            <option v-for="area in areaOfAudits" :value="area.area_of_audit_code">{{ area.description }}</option>
                                                                        </BreezeSelect>
                                                                    </td>
                                                                </tr>

                                                            </table>
                                                        </div>
                                                        <div class="col-lg-4">

                                                            <table>
                                                                <tr>
                                                                    <th scope="row">Audit Type:</th>
                                                                    <td class="ps-1">
                                                                        <BreezeSelect class="form-select" v-model="aud.audit_type">
                                                                            <option value="A">Pre-Audit</option>
                                                                            <option value="P">Post-Audit</option>
                                                                        </BreezeSelect>
                                                                    </td>
                                                                    <td><button @click="removeAreaOfAuditRow(aud.area_of_audit_code, aud.audit_type)" type="button" class="btn-close m-auto" aria-label="Close"></button></td>

                                                                </tr>
                                                            </table>

                                                        </div>

                                                    </div><!-- end of row -->

                                                    <div v-for="(aud, i) in newAreaOfAuditRows" class="row">
                                                        <div class="col-lg-8">
                                                            <table>
                                                                <tr>
                                                                    <th scope="row">Area of Audit:</th>
                                                                    <td class="ps-1">
                                                                        <BreezeSelect class="form-select" v-model="aud.area_of_audit_code">
                                                                            <option v-for="area in areaOfAudits" :value="area.area_of_audit_code">{{ area.description }}</option>
                                                                        </BreezeSelect>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <table>
                                                                <tr>
                                                                    <th scope="row">Audit Type:</th>
                                                                    <td class="ps-1">
                                                                        <BreezeSelect class="form-select" v-model="aud.audit_type">
                                                                            <option value="A">Pre-Audit</option>
                                                                            <option value="P">Post-Audit</option>
                                                                        </BreezeSelect>
                                                                    </td>
                                                                    <td class="ps-1"><button @click="removeNewAreaOfAuditRow(i)" type="button" class="btn-close m-auto" aria-label="Close"></button></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div><!-- end of additional new row -->
                                                    <div><button @click="addNewAreaOfAuditRow" type="button" class="btn btn-link">+ Add additional area of audit</button></div>

                                                </div>
                                            </div>

                                            <!-- break -->
                                            <hr>
                                            <!-- break -->

                                            <div class="card mt-3">
                                                <div class="card-header text-muted">Nature of Offense(s)</div>
                                                <div class="card-body d-grid gap-2">

                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <table>
                                                                <tr v-for="(row, i) in editForm.offences">
                                                                    <th scope="row">Nature of Offense:</th>
                                                                    <td class="ps-1">
                                                                        <BreezeSelect class="form-select" v-model="row.nature_code">
                                                                            <option v-for="offence in natureOffences" :value="offence.nature_code">{{ offence.description }}</option>
                                                                        </BreezeSelect>
                                                                    </td>
                                                                    <td><button @click="removeOffenceRow(row.nature_code)" type="button" class="btn-close m-auto" aria-label="Close"></button></td>

                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div v-for="(row, i) in newOffenceRows" class="row">
                                                        <div class="col-auto">
                                                            <table>
                                                                <tr>
                                                                    <th scope="row">Nature of Offense:</th>
                                                                    <td class="ps-1">
                                                                        <BreezeSelect class="form-select" v-model="row.nature_code">
                                                                            <option v-for="offence in natureOffences" :value="offence.nature_code">{{ offence.description }}</option>
                                                                        </BreezeSelect>
                                                                    </td>
                                                                    <td class="ps-1"><button @click="removeNewOffenceRow(i)" type="button" class="btn-close m-auto" aria-label="Close"></button></td>

                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div><!-- end of additional new row -->
                                                    <div><button @click="addNewOffenceRow" type="button" class="btn btn-link">+ Add additional nature of offence</button></div>
                                                </div>
                                            </div>



                                            <!-- break -->
                                            <hr>
                                            <!-- break -->


                                            <div class="card mt-3">
                                                <div class="card-header text-muted">Sanction(s)</div>
                                                <div class="card-body d-grid gap-2">

                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <table>
                                                                <tr v-for="(row, i) in editForm.sanctions">
                                                                    <th scope="row">Sanction Type:</th>
                                                                    <td class="ps-1">
                                                                        <BreezeSelect class="form-select" v-model="row.sanction_code">
                                                                            <option v-for="sanction in sanctions" :value="sanction.sanction_code">{{ sanction.short_description }} | {{ sanction.description }}</option>
                                                                        </BreezeSelect>
                                                                    </td>
                                                                    <td><button @click="removeOldSanctionRow(row.sanction_code)" type="button" class="btn-close m-auto" aria-label="Close"></button></td>

                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div v-for="(row, i) in newSanctionRows" class="row">
                                                        <div class="col-auto">
                                                            <table>
                                                                <tr>
                                                                    <th scope="row">Sanction Type:</th>
                                                                    <td class="ps-1">
                                                                        <BreezeSelect class="form-select" v-model="row.sanction_code">
                                                                            <option v-for="sanction in sanctions" :value="sanction.sanction_code">{{ sanction.short_description }} | {{ sanction.description }}</option>
                                                                        </BreezeSelect>
                                                                    </td>
                                                                    <td class="ps-1"><button @click="removeNewSanctionRow(i)" type="button" class="btn-close m-auto" aria-label="Close"></button></td>

                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div><!-- end of additional new row -->
                                                    <div><button @click="addNewSanctionRow" type="button" class="btn btn-link">+ Add additional sanction type</button></div>
                                                </div>
                                            </div>



                                            <!-- break -->
                                            <hr>
                                            <!-- break -->


                                            <div class="row">

                                                <div class="col-lg-3">
                                                    <table>
                                                        <tr>
                                                            <th scope="row">Bring Forward</th>
                                                            <td class="ps-1">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" role="switch" id="bringForwardSwitch" v-model="editForm.bring_forward">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-1">
                                                                <BreezeLabel for="bring_forward_date">Date</BreezeLabel>
                                                                <BreezeInput id="bring_forward_date" class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.bring_forward_date" :disabled="!editForm.bring_forward" />
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div><!-- end of col-lg-3 -->
                                                <div class="col-lg-3">
                                                    <table>
                                                        <tr>
                                                            <th scope="row">Appealed</th>
                                                            <td class="ps-1">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" role="switch" id="appealedSwitch" v-model="editForm.appeal_flag">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-1">
                                                                <BreezeLabel for="appeal_outcome">Appeal Outcome</BreezeLabel>
                                                                <BreezeSelect id="appeal_outcome" class="form-select" v-model="editForm.appeal_outcome" :disabled="!editForm.appeal_flag">
                                                                    <option value=""></option>
                                                                    <option value="A">Approved</option>
                                                                    <option value="D">Denied</option>
                                                                    <option value="P">Approved in Part</option>
                                                                </BreezeSelect>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div><!-- end of col-lg-3 -->
                                                <div class="col-lg-3">
                                                    <table>
                                                        <tr>
                                                            <th scope="row">Case Closed</th>
                                                            <td class="ps-1">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" role="switch" id="caseClosedSwitch" v-model="editForm.case_close">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-1">
                                                                <BreezeLabel for="case_date">Date</BreezeLabel>
                                                                <BreezeInput id="case_date" class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.close_date" :disabled="!editForm.case_close" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-1">
                                                                <BreezeLabel for="case_reason">Reason</BreezeLabel>
                                                                <BreezeSelect id="case_reason" class="form-select" v-model="editForm.reason_for_closing" :disabled="!editForm.case_close">
                                                                    <option value=""></option>
                                                                    <option value="C">Complete</option>
                                                                    <option value="D">Deceased</option>
                                                                    <option value="N">No Response</option>
                                                                </BreezeSelect>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-1">
                                                                <BreezeLabel for="case_outcome">Case Outcome</BreezeLabel>
                                                                <BreezeSelect id="case_outcome" class="form-select" v-model="editForm.case_outcome" :disabled="!editForm.case_close">
                                                                    <option value=""></option>
                                                                    <option value="S">Substantiated</option>
                                                                    <option value="U">Unsubstantiated</option>
                                                                </BreezeSelect>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div><!-- end of col-lg-3 -->


                                                <div class="col-lg-3">
                                                    <table>
                                                        <tr>
                                                            <th scope="row">Referred to RCMP</th>
                                                            <td class="ps-1">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" role="switch" id="referredToRcmpSwitch" v-model="editForm.rcmp_referral_flag">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-1">
                                                                <BreezeLabel for="date_referred_to_rcmp">Date referred to RCMP</BreezeLabel>
                                                                <BreezeInput id="date_referred_to_rcmp" class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.rcmp_referral_date" :disabled="!editForm.rcmp_referral_flag" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-1">
                                                                <BreezeLabel for="date_closed_by_rcmp">Date closed by RCMP</BreezeLabel>
                                                                <BreezeInput id="date_closed_by_rcmp" class="form-control" type="date" placeholder="YYYY-MM-DD" v-model="editForm.rcmp_closure_date" :disabled="!editForm.rcmp_referral_flag" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Charges laid</th>
                                                            <td class="ps-1">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" role="switch" id="chargesLaidSwitch" v-model="editForm.charges_laid_flag">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Convicted</th>
                                                            <td class="ps-1">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" role="switch" id="convictedSwitch" v-model="editForm.conviction_flag">
                                                                </div>
                                                            </td>
                                                        </tr>

                                                    </table>
                                                </div><!-- end of col-lg-3 -->

                                            </div>



                                            <!-- break -->
                                            <hr>
                                            <!-- break -->




                                            <div class="row">
                                                <div class="col-lg-3 text-lg-end text-start"><strong>Sentence Comments:</strong></div>
                                                <div class="col-lg-9">
                                                    <textarea class="form-control" v-model="editForm.sentence_comment" rows="3"></textarea>
                                                </div>
                                            </div>

                                            <div v-if="editForm.errors != undefined" class="row">
                                                <div class="col-12">
                                                    <div v-if="editForm.hasErrors == true" class="alert alert-danger mt-3">
                                                        <ul>
                                                            <li v-for="err in editForm.errors">{{ err }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn me-2 btn-outline-success" :disabled="editForm.processing">Update Case</button>
                                            <Link @click="back" class="btn btn-outline-primary float-end" href="#">Back</Link>
                                            <Link :href="'/vss/case-funding/' + result.id" class="btn btn-outline-warning float-end me-2">Funds</Link>
                                            <Link :href="'/vss/case-comment/' + result.id" class="btn btn-outline-dark float-end me-2">Comments</Link>
                                        </div>
                                    </form>
                                </template>
                                <h1 v-else class="lead">No results</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="showSuccessMsg" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="updateSuccessAlert" class="alert alert-success alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="100">
                    <div class="">
                        <div class="toast-body">
                            Case record was updated successfully.
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>


        </BreezeAuthenticatedLayout>

</template>
<script>

import BreezeAuthenticatedLayout from '../Layouts/Authenticated.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import CaseSearchBox from '../Components/CaseSearch.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeSelect from '@/Components/Select.vue';
import NavLink from "@/Components/NavLink";

export default {
    name: 'CaseEdit',
    components: {
        NavLink,
        BreezeAuthenticatedLayout, CaseSearchBox, Head, Link, BreezeInput, BreezeSelect, BreezeLabel
    },
    props: {
        result: Object,
        funds: Object,
        now: String,
        schools: Object,
        areaOfAudits: Object,
        natureOffences: Object,
        referrals: Object,
        sanctions: Object,
        staff: Object
    },
    data() {
        return {
            noChanges: true,
            newRows: [],
            showSuccessMsg: false,

            newAreaOfAuditRows: [],
            newOffenceRows: [],
            newSanctionRows: [],

            editForm: null,
            schoolsList: [],


        }
    },
    methods: {
        resetSchoolFilter: function (){
            //if schoolsListHidden is true then the schools list is still shown
            //and the input field lost focus because the user clicked something
            //other than the list ot assignSchool
            let vm = this;
            setTimeout(function (){
                if(vm.editForm.schoolsListHidden === false){
                    vm.editForm.institution = JSON.parse(JSON.stringify(vm.result.institution));
                    vm.editForm.schoolsListHidden = true;
                    vm.schoolsList = vm.schools;
                }
            }, 300);
        },
        assignSchool: function (school, j) {
            this.editForm.institution_code = school.institution_code;
            this.editForm.institution = school;
            this.editForm.schoolsListHidden = true;
            this.schoolsList = this.schools;
        },
        filterActiveSchools: function (e) {
            this.editForm.schoolsListHidden = false;
            let search = e.target.value.toLowerCase();
            if(search.length > 2){
                this.schoolsList = this.schools.filter(obj => {
                    if(obj.institution_name == null)
                        return false;
                    return obj.institution_name.toLowerCase().indexOf(search) >= 0;
                } );
            }
        },
        removeAreaOfAuditRow: function (code, type)
        {
            if(confirm('Are you sure you want to remove this Area of Audit?')){
                let form = useForm({
                    area_of_audit_code: code,
                    audit_type: type
                });

                form.post('/vss/case-funding-delete-audit-type/' + this.result.id, {
                    onSuccess: () => {
                        this.showSuccessAlert();
                        this.addDefaultLists();
                    }
                });
            }
        },
        removeOffenceRow: function (code)
        {
            if(confirm('Are you sure you want to remove this Nature of Offence?')){
                let form = useForm({
                    nature_code: code
                });

                form.post('/vss/case-funding-delete-offence/' + this.result.id, {
                    onSuccess: () => {
                        this.showSuccessAlert();
                        this.addDefaultLists();
                    }
                });
            }
        },
        removeOldSanctionRow: function (code)
        {
            if(confirm('Are you sure you want to remove this Sanction Type?')){
                let form = useForm({
                    sanction_code: code
                });

                form.post('/vss/case-funding-delete-sanction/' + this.result.id, {
                    onSuccess: () => {
                        this.showSuccessAlert();
                        this.addDefaultLists();
                    }
                });
            }
        },
        updateCase: function ()
        {
            this.editForm.processing = true;

            let oldAuditCodes = [];
            for(let i=0; i<this.editForm.audits.length; i++)
            {
                oldAuditCodes.push({
                    'area_of_audit_code': this.editForm.audits[i].area_of_audit_code,
                    'audit_type': this.editForm.audits[i].audit_type
                });
            }

            let oldNatureCodes = [];
            for(let i=0; i<this.editForm.offences.length; i++)
            {
                oldNatureCodes.push({
                    'nature_code': this.editForm.offences[i].nature_code
                });
            }

            let oldSanctionCodes = [];
            for(let i=0; i<this.editForm.sanctions.length; i++)
            {
                oldSanctionCodes.push({
                    'sanction_code': this.editForm.sanctions[i].sanction_code
                });
            }

            this.editForm = useForm({

                incident_id: this.editForm.incident_id,
                sin: this.editForm.sin,
                institution_code: this.editForm.institution_code,
                last_name: this.editForm.last_name,
                first_name: this.editForm.first_name,
                year_of_audit: this.editForm.year_of_audit,
                open_date: this.editForm.open_date,
                application_number: this.editForm.application_number,
                reactivate_date: this.editForm.reactivate_date,
                incident_status: this.editForm.incident_status,
                referral_source_id: this.editForm.referral_source_id,
                severity: this.editForm.severity,
                auditor_user_id: this.editForm.auditor_user_id,
                audit_date: this.editForm.audit_date,
                investigator_user_id: this.editForm.investigator_user_id,
                investigation_date: this.editForm.investigation_date,
                area_of_audit_code: this.editForm.area_of_audit_code,
                audit_type: this.editForm.audit_type,
                bring_forward: this.editForm.bring_forward,
                bring_forward_date: this.editForm.bring_forward_date,
                appeal_flag: this.editForm.appeal_flag,
                appeal_outcome: this.editForm.appeal_outcome,
                case_close: this.editForm.case_close,
                close_date: this.editForm.close_date,
                reason_for_closing: this.editForm.reason_for_closing,
                case_outcome: this.editForm.case_outcome,
                rcmp_referral_flag: this.editForm.rcmp_referral_flag,
                rcmp_referral_date: this.editForm.rcmp_referral_date,
                rcmp_closure_date: this.editForm.rcmp_closure_date,
                charges_laid_flag: this.editForm.charges_laid_flag,
                conviction_flag: this.editForm.conviction_flag,
                sentence_comment: this.editForm.sentence_comment,


                old_audit_codes: oldAuditCodes,
                new_audit_codes: this.newAreaOfAuditRows,

                old_offence_codes: oldNatureCodes,
                new_offence_codes: this.newOffenceRows,

                old_sanction_codes: oldSanctionCodes,
                new_sanction_codes: this.newSanctionRows,

            });

            this.addDefaultLists();

            this.editForm.put('/vss/cases/' + this.result.id, {
                onSuccess: () => {
                    this.showSuccessAlert();
                    this.addDefaultLists();
                },
                onFailure: () => {
                    this.addDefaultLists();
                },
                onError: () => {
                    this.addDefaultLists();
                }
            });
            // form.wasSuccessful();
        },
        showSuccessAlert: function ()
        {
            this.showSuccessMsg = true;
            let vm = this;
            setTimeout(function (){
                vm.showSuccessMsg = false;
                vm.noChanges = true;
            }, 5000);
        },

        addNewAreaOfAuditRow: function(){
            this.newAreaOfAuditRows.push({
                'area_of_audit_code': 1,
                'audit_type': 'A'
            });
            this.noChanges = false;
        },
        removeNewAreaOfAuditRow: function(index){
            let newArray = [];
            for(let i=0; i<this.newAreaOfAuditRows.length; i++){
                if(i !== index){
                    newArray.push(this.newAreaOfAuditRows[i]);
                }
            }
            this.newAreaOfAuditRows.length = 0;
            this.newAreaOfAuditRows = [];
            this.newAreaOfAuditRows = newArray;
        },

        addNewOffenceRow: function(){
            this.newOffenceRows.push({
                'nature_code': 'Failure to Report'
            });
            this.noChanges = false;
        },
        removeNewOffenceRow: function(index){
            let newArray = [];
            for(let i=0; i<this.newOffenceRows.length; i++){
                if(i !== index){
                    newArray.push(this.newOffenceRows[i]);
                }
            }
            this.newOffenceRows.length = 0;
            this.newOffenceRows = [];
            this.newOffenceRows = newArray;
        },

        addNewSanctionRow: function(){
            this.newSanctionRows.push({
                'sanction_code': 1
            });
            this.noChanges = false;
        },
        removeNewSanctionRow: function(index){
            let newArray = [];
            for(let i=0; i<this.newSanctionRows.length; i++){
                if(i !== index){
                    newArray.push(this.newSanctionRows[i]);
                }
            }
            this.newSanctionRows.length = 0;
            this.newSanctionRows = [];
            this.newSanctionRows = newArray;
        },

        back: function()
        {
            window.history.back();
        },

        addDefaultLists: function ()
        {

            if(this.editForm != null){

            }else{
                this.editForm = this.result;
                this.editForm.processing = false;
            }

            this.newAreaOfAuditRows = [];
            this.newOffenceRows = [];
            this.newSanctionRows = [];

            this.editForm.audits = this.result.audits;
            this.editForm.offences = this.result.offences;
            this.editForm.sanctions = this.result.sanctions;
            this.editForm.schools = this.result.schools;


        }

    },
    watch: {
    },
    computed: {
    },
    mounted() {
        this.addDefaultLists();

        //enable tooltips
        setTimeout(function (){
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        }, 3000);
    }
}
</script>
