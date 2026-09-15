<template>
    <div class="bulk-floating-action-wrapper">
        <div class="actions">
            <!-- Assign candidates -->
            <div
                v-if="$have('PERMISSION_ASSIGN_JOB')"
                class="dropdown d-inline-block btn-dropdown keep-inside-clicks-open" :title="$t('assign_job')"
                data-toggle="tooltip">
                <button aria-expanded="false" aria-haspopup="true" class="btn btn-light dropdown-toggle border-right"
                    data-toggle="dropdown" type="button" id="assignGroup" @click="operationType = 'assign'">
                    <app-icon name="user-plus" />
                </button>
                <div class="dropdown-menu p-primary" style="width: 500px" aria-labelledby="assignGroup">
                    <form ref="form" :data-url="``" class="mb-0">
                        <h5 class="mb-3">{{ $t('assign_job') }}</h5>
                        <div class="form-group">
                            <div class="form-row">
                                <label class="mb-0 col-sm-3 d-flex align-items-center">
                                    {{ $t('jobs') }}
                                </label>
                                <div class="col-sm-9">
                                    <app-input type="select" list-value-field="name" v-model="jobPostId" :list="jobList" />
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" @click.prevent="operationJobs">
                                <span v-if="preloader" class="w-100">
                                    <app-pre-loader />
                                </span>
                                <template v-else>{{ $t('assign') }}</template>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!--Retract jons-->
            <div
                v-if="$have('PERMISSION_UN_ASSIGN_JOB')"
                class="dropdown d-inline-block btn-dropdown" :title="$t('retract_job')" data-toggle="tooltip">
                <button id="retractGroup" aria-expanded="false" aria-haspopup="true"
                    class="btn btn-light dropdown-toggle border-right" data-toggle="dropdown" type="button"
                    @click="operationType = 'retract'">
                    <app-icon name="user-minus" />
                </button>
                <!-- @click.prevent="retractCandidate" -->
                <div class="dropdown-menu p-primary" style="width: 500px" aria-labelledby="retractGroup">
                    <form ref="form" :data-url="``" class="mb-0">
                        <h5 class="mb-3">{{ $t('retract_job') }}</h5>
                        <div class="form-group">
                            <div class="form-row">
                                <label class="mb-0 col-sm-3 d-flex align-items-center">
                                    {{ $t('jobs') }}
                                </label>
                                <div class="col-sm-9">
                                    <app-input type="select" list-value-field="name" v-model="jobPostId" :list="jobList" />
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-danger" @click.prevent="operationJobs">
                                <span v-if="preloader" class="w-100">
                                    <app-pre-loader />
                                </span>
                                <template v-else>{{ $t('retract') }}</template>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!--Delete-->
            <!-- <div class="dropdown d-inline-block btn-dropdown" :title="$t('delete')" data-toggle="tooltip">
                <button class="btn btn-light dropdown-toggle border-right" type="button" @click.prevent="deleteJobs">
                    <app-icon name="trash-2"/>
                </button>
            </div> -->
        </div>
    </div>
</template>

<script>
import { FormMixin } from "../../../../../core/mixins/form/FormMixin";
import { ALL_JOBS, BULK_ASSIGN_JOBS, BULK_RETRACT_JOBS } from "../../../../Config/ApiUrl";

export default {
    name: "BulkAction",
    mixins: [FormMixin],
    props: {
        selected: {
            type: Array,
            required: true
        },
        list: {
            type: Array,
            required: true
        },
        isAllRowSelected: Boolean,

        tableId: {}
    },
    data() {
        return {
            BULK_ASSIGN_JOBS,
            jobPostId: null,
            jobList: [],
            preloader: false,
            operationType: '',
        }
    },
    methods: {
        // deleteJobs() {
        //     this.$emit('deleteSelected')
        // },
        operationJobs() {
            this.preloader = true;
            const attachable_ids = this.selected.map(item => item.id)
            let data = {
                attachable_ids: attachable_ids,
                job_post_id: this.jobPostId * 1,
                is_all_selected: this.isAllRowSelected
            }
            if (this.operationType === 'assign') {
                this.axiosPatch({ url: BULK_ASSIGN_JOBS, data }).then((res) => {
                    this.$toastr.s(res.data.message);
                }).catch(({ response }) => {
                    this.$toastr.e(response.data.message);
                }).finally(() => {
                    this.preloader = false;
                    this.$hub.$emit(`reload-${this.tableId}`);
                })
            } else if (this.operationType === 'retract') {
                this.axiosPatch({ url: BULK_RETRACT_JOBS, data }).then((res) => {
                    this.$toastr.s(res.data.message);
                }).catch(({ response }) => {
                    this.$toastr.e(response.data.message);
                }).finally(() => {
                    this.preloader = false;
                    this.$hub.$emit(`reload-${this.tableId}`);
                })
            }
        },
        // retractCandidate() {
        //     this.$emit('deleteSelected')
        // },
        getJobs() {
            this.dataLoaded = true;
            this.axiosGet(ALL_JOBS)
                .then((response) => {
                    this.jobList = response.data
                })
                .catch(({ error }) => {
                })
                .finally(() => {
                });
        },
    },
    mounted() {
        this.getJobs();
    },
}
</script>
