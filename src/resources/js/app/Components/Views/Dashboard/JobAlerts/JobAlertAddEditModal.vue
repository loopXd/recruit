<template>
    <modal :modal-id="modalId"
           :title="$t('set_job_alert')"
           :preloader="preloader"
           @submit="submit"
           @closeModal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form class="mb-0"
                  :class="{'loading-opacity': preloader}"
                  ref="form"
                 >

                <div class="form-group  d-flex justify-content-between mb-3">
                    <label for="is_active">
                        {{ $t('get_job_alert') }}
                    </label>
                    <app-input
                            id="is_active"
                            type="switch"
                            v-model="formData.is_active"
                    />
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <app-note
                                :show-title="false"
                                content-type="html"
                                :notes="getJobAlertEmail"
                        />
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="jobType">
                        {{ $t('job_type') }}
                    </label>
                    <app-input
                            id="jobType"
                            type="multi-select"
                            :placeholder="$chooseLabel('job_type')"
                            :list="jobTypeList"
                            list-value-field="name"
                            v-model="formData.job_type"
                    />
                </div>
                <div class="form-group mb-3">
                    <label for="work_arrangement">
                        {{ $t('work_arrangement') }}
                    </label>
                    <app-input
                            id="work_arrangement"
                            type="multi-select"
                            :placeholder="$chooseLabel('work_arrangement')"
                            :list="workArrangements"
                            list-value-field="value"
                            v-model="formData.working_arrangement"
                    />
                </div>
                <div class="form-group mb-3">
                    <label for="department">
                        {{ $t('department') }}
                    </label>
                    <app-input
                            id="department"
                            type="multi-select"
                            :placeholder="$chooseLabel('department')"
                            :list="departmentList"
                            list-value-field="name"
                            v-model="formData.department"
                    />
                </div>
                <div class="form-group mb-3">
                    <label for="experience_level">
                        {{ $t('experience_level') }}
                    </label>
                    <app-input
                            id="experience_level"
                            type="multi-select"
                            :placeholder="$chooseLabel('experience_level')"
                            :list="jobExperienceList"
                            list-value-field="name"
                            v-model="formData.experience_level"
                    />
                </div>
            </form>
        </template>
    </modal>
</template>
<script>


import {ModalMixin} from "../../../../Mixins/ModalMixin";
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";
import {GET_JOB_ALERT, SET_JOB_ALERT} from "../../../../Config/ApiUrl";
import {mapGetters} from "vuex";
import {axiosGet} from "../../../../Helpers/AxiosHelper";

export default {
    name: 'JobAlertAddEditModal',
    mixins: [ModalMixin, FormMixin],
    props: {
        tableId: {type: String},
        workArrangements: {
            type: Array,
            required: true
        }
    },
    computed: {
        ...mapGetters([
            'jobTypeList',
            'departmentList',
            'jobExperienceList'
        ])
    },
    data() {
        return {
            SET_JOB_ALERT,
            modalId: 'experience-level-add-edit-modal',
            getJobAlertEmail: `<p>${this.$t('job_alert_note')}</p>`,
            formData: {
                job_type: [],
                working_arrangement: [],
                department: [],
                experience_level: []
            }
        }
    },
    beforeMount(){
        axiosGet(GET_JOB_ALERT).then((res) => {
            if(!!res.data) {
                this.formData.is_active = res.data.is_active
                this.formData.department = res.data.department
                this.formData.experience_level = res.data.experience_level
                this.formData.job_type = res.data.job_type
                this.formData.working_arrangement = res.data.working_arrangement
            }

        });
    },
    methods: {
        submit() {
            let reqType = "patch";
            this.submitFromFixin(reqType, SET_JOB_ALERT, this.formData);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit(`reload-${this.tableId}`)
        },
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
            this.preloader = false;
        }
    }
}
</script>
