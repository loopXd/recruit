<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-7 col-xl-8 mb-4 mb-lg-0">
                <div  class="d-flex align-items-center justify-content-between">
                    <app-breadcrumb :page-title="$t('dashboard')"/>
                    <button
                        v-if="$have('PERMISSION_CREATE_JOB_POST')"
                        type="button"
                        class="btn btn-success btn-with-shadow mb-4"
                        data-toggle="modal"
                        @click="openJobAddEditModal">
                        <app-icon name="plus" class="size-20 mr-2"/>
                        {{ $createLabel('new_job') }}
                    </button>
                </div>

                <app-table
                    v-if="$have('PERMISSION_VIEW_JOB_POST')"
                    :id="tableId"
                    :options="options"
                    :table-view="false"
                    :card-view="true"
                    @action="getDashboardAction"
                />

                <job-add-edit-modal
                    v-if="isJobAddEditModalActive"
                    :work-arrangements="workArrangements"
                    :table-id="tableId"
                    :selected-url="selectedUrl"
                    @closeModal="closeJobAddEditModal"
                />

                <app-delete-modal
                    v-if="deleteConfirmationModalActive"
                    :preloader="deleteLoader"
                    :modal-id="deleteModalId"
                    @confirmed="confirmed"
                    @cancelled="cancel"
                />

                <!--Job Shareable Link Modal -->
                <shareable-link-modal
                    v-if="shareableLinkModalActive"
                    :job-post-id="rowData.id"
                    @closeModal="closeShareableLinkModal"
                />
            </div>
            <div class="col-lg-5 col-xl-4">
                <div v-if="isCandidate" class="mb-primary">
                    <job-alerts :work-arrangements="workArrangements" :is-candidate="isCandidate"/>
                </div>
                <div class="mb-primary" v-if="isCandidate && showLastAppliedJobs">
                    <recent-jobs :last-applied-job="lastAppliedJob"/>
                </div>
                <div v-if="$have('PERMISSION_VIEW_EVENT')" class="mb-primary">
                    <events/>
                </div>
                <div>
                    <to-do/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import JobTableMixin from './Mixins/JobTableMixin';
import {GET_LAST_APPLIED_JOBS} from "../../../Config/ApiUrl";

export default {
    name: 'DashboardJobList',

    mixins: [JobTableMixin],
    props: {
        isCandidate: {
            type: Boolean,
            required: true
        },
        workArrangements: {
            type: Array,
            required: true
        }
    },
}
</script>
