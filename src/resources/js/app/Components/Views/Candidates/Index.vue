<template>
    <div class="content-wrapper">
        <div class="d-flex align-items-center justify-content-between">
            <app-breadcrumb :page-title="$t('candidates')"/>

            <div class="d-flex d-inline">
                <app-default-button
                        v-if="$have('PERMISSION_CREATE_CANDIDATE')"
                        btn-class="btn btn-info btn-with-shadow mb-4 mr-2"
                        :title="$t('import')"
                        @click="operationCandidateImport"
                />
                <app-default-button
                        v-if="$have('PERMISSION_EXPORT_CANDIDATE')"
                        btn-class="btn btn-primary btn-with-shadow mb-4 mr-2"
                        :title="$t('export_all')"
                        @click="viewConfirmationModal(true)"
                />
                <app-default-button
                        v-if="$have('PERMISSION_EXPORT_CANDIDATE')"
                        btn-class="btn btn-secondary btn-with-shadow mb-4 mr-2"
                        :title="$t('export')"
                        @click="viewConfirmationModal()"
                />
                <button
                        v-if="$have('PERMISSION_CREATE_CANDIDATE')"
                        type="button"
                        class="btn btn-success btn-with-shadow mb-4"
                        @click="isEmailVerificationModalActive = true">
                    <app-icon name="plus" class="size-20 mr-2"/>
                    {{ $addLabel('candidate') }}
                </button>
            </div>

        </div>

        <app-table
                v-if="$have('PERMISSION_VIEW_CANDIDATE')"
                :id="tableId"
                :options="options"
                @getRows="afterBulkSelect"
                @action="getTableAction"
        />

        <bulk-action
                v-if="isBulkActionActive && ($have('PERMISSION_ASSIGN_JOB') || $have('PERMISSION_UN_ASSIGN_JOB'))"
                :table-id="tableId"
                :list="[]"
                :selected="selectedRows"
                :is-all-row-selected="isAllRowSelected"
        />
        <!-- @retractSelected="deleteBulkCandidates" -->
        <!-- @deleteSelected="deleteBulkCandidates" -->

        <app-confirmation-modal
                v-if="bulkDeleteModal"
                modal-id="bulk-delete-modal"
                @cancelled="bulkDeleteCancel"
                @confirmed="bulkDeleteConfirmed"
        />

        <!-- Candidate Details Modal -->
        <candidate-details-modal
                v-if="isCandidateDetailsModalActive"
                :table-id="tableId"
                :job-applicant-id="jobApplicantId"
                :user="user"
                @openCandidateDisqualifyModal="openCandidateDisqualifyModal"
                @openCandidateMailingModal="openCandidateMailingModal"
                @openEventAddEditModal="openEventAddEditModal"
                @viewEvent="viewEventDetails"
                @closeModal="closeCandidateDetailsModal"
        />

        <!-- Email Verification Modal -->
        <email-verification-modal
                v-if="isEmailVerificationModalActive"
                @verifiedData="afterVerifiedEmail"
                @closeModal="closeEmailVerificationModal"
        />

        <!-- Candidate Add/Edit Modal -->
        <candidate-add-edit-modal
                v-if="isCandidateAddEditModalActive"
                :table-id="tableId"
                :selected-url="selectedUrl"
                :verify-email="verifyEmail"
                @closeModal="closeCandidateAddEditModal"
        />

        <!-- Candidate Assign Job Modal -->
        <candidate-assign-job-modal
                v-if="isCandidateAssignJobModalActive"
                :table-id="tableId"
                :candidate="rowData"
                @closeModal="closeCandidateAssignJobModal"
        />


        <!-- Candidate Disqualify Modal -->
        <candidate-disqualify-modal
                v-if="isCandidateDisqualifyModalActive"
                :table-id="tableId"
                :job-applicant-id="jobApplicantId"
                :selected-candidate="selectedCandidate"
                @closeModal="closeCandidateDisqualifyModal"
        />

        <!-- Candidate Mailing Modal -->
        <candidate-mailing-modal
                v-if="isCandidateMailingModalActive"
                :selected-candidate="selectedCandidate"
                :job-applicant-id="jobApplicantId"
                @closeModal="closeCandidateMailingModal"
        />

        <!-- Candidate Event Modal -->
        <candidate-event-modal
                v-if="isCandidateEventAddEditModalActive"
                :selected-job-post="selectedJobPost"
                :selected-applicant="selectedApplicant"
                :job-applicant-id="jobApplicantId"
                :selected-url="eventSelectedUrl"
                @closeModal="closeEventAddEditModal"
        />

        <!-- Candidate Delete Modal -->
        <app-delete-modal
                v-if="isCandidateDeleteModalActive"
                :modal-id="deleteModalId"
                :message="$t('delete_candidate_notice')"
                :preloader="deleteLoader"
                @confirmed="confirmDelete"
                @cancelled="cancel"
        />

        <!-- Unassigned Job Modal -->
        <app-delete-modal
                v-if="isUnassignedModalActive"
                :modal-id="deleteModalId"
                :message="$t('job_unassigned_notice')"
                :preloader="deleteLoader"
                @confirmed="confirmUnassigned"
                @cancelled="cancel"
        />


        <!--event view modal-->
        <event-view-modal
                v-if="eventViewModalActive"
                :modal-id="eventViewModalId"
                :event="selectedEvent"
                @closeModal="closeEventViewModal"
        />

        <app-confirmation-modal
                v-if="exportConfirmationModal"
                :title="modalSubtitle"
                :message="' '"
                modal-id="app-confirmation-modal"
                modal-class="primary"
                icon="download"
                :first-button-name="$t('export')"
                :second-button-name="$t('cancel')"
                @confirmed="exportFilteredAttendance()"
                @cancelled="exportConfirmationModal = false"
                :self-close="false"
        />
    </div>
</template>

<script>
import CandidatesTableMixin from './Mixins/CandidatesTableMixin';
import BulkAction from './BulkAction/BulkAction.vue'

export default {
    name: 'Candidate',
    components: { BulkAction },
    props: {
        user:{},
        isCandidate: {
            type: Boolean,
            required: true
        }
    },
    mixins: [CandidatesTableMixin],
    data(){
        return {
                isBulkActionActive: false,
                tableId: 'candidates-table',
                bulkContext: '',
                selectedRows: [],
                isAllRowSelected: false,
                bulkDeleteModal: false
        }
    },
    methods: {
        afterBulkSelect(selectedRows, isAllSelect) {
                this.selectedRows = selectedRows
                this.isAllRowSelected = isAllSelect
                this.isBulkActionActive = !!selectedRows.length || isAllSelect
        },
        // deleteBulkCandidates() {
        //         this.bulkDeleteModal = true
        // },
        bulkDeleteConfirmed() {
                this.bulkDeleteModal = false
        },
        bulkDeleteCancel() {
                this.bulkDeleteModal = false
        }
    }

}
</script>
