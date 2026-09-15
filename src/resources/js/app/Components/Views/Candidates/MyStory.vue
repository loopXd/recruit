<template>
    <div class="content-wrapper">
        <div class="d-flex align-items-center justify-content-between">
            <app-breadcrumb :page-title="$t('my_story')"/>
        </div>

        <app-table
                :id="tableId"
                :options="options"
                @action="getTableAction"
        />

        <candidate-details-modal
                v-if="isCandidateDetailsModalActive"
                :is-candidate="isCandidate"
                :table-id="tableId"
                :job-applicant-id="jobApplicantId"
                :user="user"
                @closeModal="closeCandidateDetailsModal"
        />
    </div>
</template>

<script>
import {MY_STORY} from "../../../Config/ApiUrl";
import DeleteMixin from "../../../Mixins/DeleteMixin";
import {formatDateTime} from "../../../Helpers/DateTimeHelper";

export default {
    name: 'MyStory',
    mixins: [DeleteMixin],
    props: {
        isCandidate: {
            type: Boolean,
            required: true
        },
        user: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            isCandidateDetailsModalActive: false,
            selectedUrl: '',
            tableId: 'department',
            deleteModalId: 'department-delete-modal',
            rowData: {},
            jobApplicantId: '',
            options: {
                url: MY_STORY,
                name: 'my_story',
                datatableWrapper: false,
                columns: [
                    {
                        title: this.$t('applied_job'),
                        type: 'custom-html',
                        key: 'job_post',
                        modifier: (job_post) => {
                            return job_post.name
                        }
                    },
                    {
                        title: this.$t('applied_at'),
                        type: 'custom-html',
                        key: 'job_post',
                        modifier: (job_post) => {
                            return formatDateTime(job_post.created_at)
                        }
                    },
                    {
                        title: this.$t('salary'),
                        type: 'custom-html',
                        key: 'job_post',
                        modifier: (job_post) => {
                            return job_post.salary
                        }
                    },
                    {
                        title: this.$t('no_of_vacancy'),
                        type: 'custom-html',
                        key: 'job_post',
                        modifier: (job_post) => {
                            return job_post.vacancy_count
                        }
                    },
                    // {
                    //     title: this.$t('responsibilities'),
                    //     type: 'custom-html',
                    //     key: 'job_post',
                    //     modifier: (job_post) => {
                    //         return job_post.responsibilities
                    //     }
                    // },
                    {
                        title: this.$t('last_submission_date'),
                        type: 'custom-html',
                        key: 'job_post',
                        modifier: (job_post) => {
                            return job_post.last_submission_date
                        }
                    },
                ],
                actions: [
                    {
                        title: this.$t('view'),
                        icon: 'eye',
                        key: 'view',
                        modifier: () => true
                    },
                ],
                showAction: true,
                rowLimit: 10,
                orderBy: 'desc',
                tableShadow: false,
                actionType: 'default'
            },
        }
    },
    beforeMount() {
        this.options.columns.push({
            title: this.$t('actions'),
            type: 'action'
        })
    },

    methods: {
        closeCandidateDetailsModal() {
            this.jobApplicantId = '';
            this.isCandidateDetailsModalActive = false;
        },
        openCandidateDetailsModal() {
            this.isCandidateDetailsModalActive = true;
        },
        getTableAction(rowData, actionObj) {
            this.rowData = rowData;
            if (actionObj.key === 'view') {
                this.jobApplicantId = rowData.id
                this.openCandidateDetailsModal();
            }
        },
    }
}
</script>
