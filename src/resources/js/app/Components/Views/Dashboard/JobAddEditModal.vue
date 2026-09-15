<template>
    <modal :modal-id="modalId" :title="selectedUrl ? $editLabel('job') : $createLabel('new_job')" :preloader="preloader"
        :modal-scroll="false" modal-size="large" @submit="submit" @closeModal="closeModal">

        <template slot="body">
            <app-overlay-loader v-if="preloader" />
            <form v-else class="mb-0" :class="{ 'loading-opacity': preloader }" ref="form"
                :data-url="selectedUrl ? selectedUrl : JOB">
                <div class="form-group row align-items-center">
                    <label for="jobTitle" class="col-sm-3 mb-0">
                        {{ $t('job_title') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="jobTitle" type="text" v-model="formData.name"
                            :placeholder="$placeholder('title')" :required="true" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="jobType" class="col-sm-3 mb-sm-0">
                        {{ $t('job_type') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="jobType" type="search-select" :placeholder="$chooseLabel('job_type')"
                            :list="jobTypeList" list-value-field="name" :required="true"
                            v-model="formData.job_type_id" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="work_arrangement" class="col-sm-3 mb-sm-0">
                        {{ $t('work_arrangement') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="work_arrangement" type="search-select"
                            :placeholder="$chooseLabel('work_arrangement')" :list="workArrangements"
                            list-value-field="value" :required="true" v-model="formData.working_arrangement" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="department" class="col-sm-3 mb-sm-0">
                        {{ $t('department') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="department" type="search-and-select" :options="departmentOption"
                            :placeholder="$chooseLabel('department')" :required="true"
                            v-model="formData.department_id" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="experience_level" class="col-sm-3 mb-sm-0">
                        {{ $t('experience_level') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="experience_level" type="search-select"
                            :placeholder="$chooseLabel('experience_level')" :list="jobExperienceList"
                            list-value-field="name" :required="true" v-model="formData.experience_level_id" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="locations" class="col-sm-3 mb-sm-0">
                        {{ $t('location') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="locations" type="search-and-select" :options="locationOption"
                            :placeholder="$chooseLabel('location')" :required="true"
                            v-model="formData.company_location_id" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-3 mb-0">
                        {{ $t('description') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="description" type="textarea" v-model="formData.description"
                            :placeholder="$placeholder('description')" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="no_of_vacancy" class="col-sm-3 mb-0">
                        {{ $t('no_of_vacancy') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="no_of_vacancy" type="text" v-model="formData.vacancy_count"
                            :placeholder="$placeholder('no_of_vacancy')" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="salary" class="col-sm-3 mb-0">
                        {{ $t('salary') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="salary" type="text" v-model="formData.salary" v-money="money"
                            :placeholder="$placeholder('salary')" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <div class="col-sm-9 offset-sm-3">
                        <app-input type="checkbox" :list="[{ id: 1, value: 'Mostrar ao candidato' }]"
                            v-model="salaryIsViewable" list-value-field="value" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="sub-date" class="col-sm-3 mb-0">
                        {{ $t('last_submission') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="sub-date" type="date" :required="true" v-model="formData.last_submission_date"
                            :placeholder="$t('last_submission_date')" />
                    </div>
                </div>
            </form>
        </template>
    </modal>
</template>

<script>
import { FormMixin } from "../../../../core/mixins/form/FormMixin";
import { ModalMixin } from "../../../Mixins/ModalMixin";
import EditorDemoMixin from "../../Helpers/Editor/EditorDemoMixin";
import { mapGetters } from "vuex";
import { JOB, SELECTABLE_DEPARTMENTS_BY_SEARCH, SELECTABLE_COMPANY_LOCATIONS_BY_SEARCH } from "../../../Config/ApiUrl";
import DateFunction from "../../../../core/helpers/date/DateFunction";
import { VMoney } from 'v-money';
import moment from "moment";
import { format } from "fecha";


export default {
    name: 'JobAddEditModal',
    mixins: [FormMixin, ModalMixin, EditorDemoMixin],
    props: {
        tableId: {},
        workArrangements: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            modalId: 'job-add-edit-Modal',
            JOB,
            salaryIsViewable: [1], // this is for the checkboxes modelValue
            formData: {
                name: '',
                working_arrangement: '',
                job_type_id: '',
                experience_level_id: '',
                department_id: '',
                company_location_id: '',
                vacancy_count: '',
                salary: '',
                is_viewable: null, // this is for the api payload based on salaryIsViewable
                description: '',
                last_submission_date: new Date(),
            },
            departmentOption: {
                url: '/' + SELECTABLE_DEPARTMENTS_BY_SEARCH,
                modifire: (v) => {
                    return { id: v.id, value: v.name }
                },
                loader: 'app-pre-loader'
            },
            locationOption: {
                url: '/' + SELECTABLE_COMPANY_LOCATIONS_BY_SEARCH,
                modifire: (v) => {
                    return { id: v.id, value: v.address }
                },
                loader: 'app-pre-loader'
            },
            money: {
                decimal: ',',
                thousands: '.',
                prefix: '',
                suffix: '',
                precision: 2,
                masked: false
            }
        }
    },
    directives: { money: VMoney },
    computed: {
        ...mapGetters([
            'jobTypeList',
            'jobExperienceList',
            'departmentList',
            'stageList',
            'companyLocationList',
            'applicationForm'
        ])
    },
    methods: {
        generateSettingData() {
            this.content.title = this.formData.name;
            this.content.subtitle = this.formData.description ? this.formData.description : `${this.formData.name} - Description Here`;
            this.content.details = `${this.jobTypeList.find(item => item.id === this.formData.job_type_id)?.name} - ${this.companyLocationList.find(item => item.id === this.formData.company_location_id)?.address}`;
            let data = {};
            data.content = this.content;
            data.pageStyle = this.pageStyle;
            data.pageBlocks = this.pageBlocks
            return data;
        },
        generateStages() {
            let stages = this.stageList.filter(item => !(item.name.toLowerCase() === 'new' || item.name.toLowerCase() === 'hired' || item.name.toLowerCase() === 'disqualified')),
                stageName = stages.map(item => item.name.toLowerCase());
            return ['new', ...stageName, 'hired', 'disqualified'].toString();
        },
        submit() {
            if (!this.selectedUrl) {
                this.formData.apply_form_settings = this.applicationForm;
                this.formData.stages = this.generateStages();
                this.formData.job_post_settings = this.generateSettingData();
            } else {
                this.formData.job_post_settings.content.title = this.formData.name;
                this.formData.job_post_settings.content.subtitle = this.formData.description ? this.formData.description : `${this.formData.name} - Description Here`;
                this.formData.job_post_settings.content.details = `${this.jobTypeList.find(item => item.id === this.formData.job_type_id)?.name} - ${this.companyLocationList.find(item => item.id === this.formData.company_location_id)?.address}`;
            }
            if (this.formData.last_submission_date) {
                this.formData.last_submission_date = DateFunction.getDateFormatForBackend(this.formData.last_submission_date);
            }

            this.formData.is_viewable = !this.salaryIsViewable[0] ? 0 : 1;

            this.save(this.formData);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit(`reload-${this.tableId}`)
        },
        afterSuccessFromGetEditData({ data }) {
            const lastSubmissionDate = new Date(data.last_submission_date);
            lastSubmissionDate.setDate(lastSubmissionDate.getDate() + 1);

            this.preloader = true
            this.formData.name = data.name;
            this.formData.working_arrangement = data.working_arrangement;
            this.formData.job_type_id = data.job_type_id;
            this.formData.experience_level_id = data.experience_level_id;
            this.formData.department_id = data.department_id;
            this.formData.company_location_id = data.company_location_id;
            this.formData.vacancy_count = data.vacancy_count;
            this.formData.salary = data.salary;
            this.formData.description = data.description;
            this.formData.last_submission_date = moment(lastSubmissionDate).add(1, 'days').format('YYYY-MM-DD');
            this.salaryIsViewable = Boolean(+data.is_viewable) ? [1] : [];
            this.preloader = false;
            this.formData.job_post_settings = this.selectedUrl && typeof data.job_post_settings === "string"
                ? JSON.parse(data.job_post_settings)
                : data.job_post_settings;
            setTimeout(() => {
                this.preloader = false
            })
        }
    }
}
</script>