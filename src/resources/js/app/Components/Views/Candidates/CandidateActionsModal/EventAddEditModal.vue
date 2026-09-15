<template>
    <modal :modal-id="modalId" :title="selectedUrl ? $editLabel('event') : $createLabel('event')"
        :preloader="eventLoader" :modal-scroll="false" modal-size="large" @submit="submit" @closeModal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="eventLoader" />
            <form class="mb-0" :class="{ 'loading-opacity': eventLoader }" ref="form"
                :data-url="selectedUrl ? selectedUrl : EVENT">

                <div class="form-group row align-items-center">
                    <label for="eventType" class="col-sm-3 mb-0">
                        {{ $t('event_type') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="eventType" type="search-select" v-model="formData.event_type_id"
                            :placeholder="$chooseLabel('event_type')" :required="true" :list="eventTypeList"
                            list-value-field="name" />
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label class="col-sm-3 mb-0">
                        {{ $t('event_schedule') }}
                    </label>
                    <div class="col-sm-9">
                        <div class="d-flex align-items-center justify-content-between">
                            <app-input type="date" date-mode="dateTime" :placeholder="$t('start_date')" :required="true"
                                v-model="formData.start_at" @input="setEndDateAsStartDate" />
                            -
                            <app-input type="date" date-mode="dateTime" :min-date="formData.start_at"
                                :placeholder="$t('end_date')" :required="true" v-model="formData.end_at" />
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="location" class="col-sm-3 mb-0">
                        {{ $t('location') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="location" type="text" :placeholder="$placeholder('location')" :required="true"
                            v-model="formData.location" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 mb-0">
                        {{ $t('candidate') }}
                    </label>
                    <div class="col-sm-9">
                        <p class="primary-text-color">{{ selectedApplicant.full_name }}</p>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="attendees" class="col-sm-3 mb-0">
                        {{ $t('attendees') }}
                    </label>
                    <div class="col-sm-9">
                        <app-note class="mb-4" title="info" :show-title="false"
                            :notes="$t('attendees_are_the_persons_among_the_hiring_team_of_this_job_post')" />
                        <app-input id="attendees" type="multi-select" v-model="formData.attendees" :required="true"
                            :list="attendeesList" />

                    </div>
                </div>

                <div class="form-group row">
                    <label for="description" class="col-sm-3 mb-0">
                        {{ $t('description') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="description" type="textarea" :text-area-rows="4"
                            :placeholder="$placeholder('description')" v-model="formData.description" />
                    </div>
                </div>

                <div class="form-group row">
                    <label for="video_meeting" class="col-sm-3 mb-0">
                        {{ $t('video_meeting') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input @input="radioButtonOnChange" id="video_meeting" type="radio"
                            custom-radio-type="customized-radio radio-default mb-2" :list="enableList"
                            v-model="formData.video_meeting" />
                    </div>
                </div>

                <div v-if="isDivActive">
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 mb-0">
                            {{ $t('topic') }}
                        </label>
                        <div class="col-sm-9">
                            <app-input id="topic" type="text" :text-area-rows="4" :placeholder="$placeholder('topic')"
                                v-model="formData.topic" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="duration" class="col-sm-3 mb-0">
                            {{ $t('duration') }} (min)
                        </label>
                        <div class="col-sm-9">
                            <app-input id="duration" type="number" :text-area-rows="4"
                                :placeholder="$placeholder('duration')" v-model="formData.duration" />
                        </div>
                    </div>
                </div>

            </form>
        </template>
    </modal>
</template>

<script>
import { ModalMixin } from '../../../../Mixins/ModalMixin';
import { FormMixin } from '../../../../../core/mixins/form/FormMixin';
import { mapGetters } from "vuex";
import { axiosGet } from "../../../../Helpers/AxiosHelper";
import { EVENT, SELECTABLE } from "../../../../Config/ApiUrl";
import {
    formatDateTimeForServer,
    formatDateTimeDateTime,
    localDateTimeFromUtc
} from "../../../../Helpers/DateTimeHelper";
import moment from 'moment-timezone';

export default {
    name: 'EventAddEditModal',
    mixins: [FormMixin, ModalMixin],
    props: {
        selectedJobPost: {},
        selectedApplicant: {},
        jobApplicantId: {}
    },
    data() {
        return {
            modalId: 'event-add-edit-modal',
            parseInt,
            EVENT,
            formData: {
                event_type_id: '',
                start_at: new Date(),
                end_at: new Date(),
                location: '',
                attendees: [],
                description: '',
                video_meeting: 2,
                topic: '',
                duration: '',

            },
            isDivActive: false,
            enableList: [
                { id: 1, value: this.$t('yes') },
                { id: 2, value: this.$t('no') }
            ],
            attendeesList: [],
            attendeesLoading: false
        }
    },
    created() {
        this.getAttendees();
    },
    computed: {
        ...mapGetters([
            'eventTypeList'
        ]),
        eventLoader() {
            return this.preloader || this.attendeesLoading
        },

    },
    methods: {
        submit() {
            let submittedData = { ...this.formData };

            if (submittedData.attendees.length > 0) {
                submittedData.attendees = submittedData.attendees.map(e => {
                    return { hiring_team_id: e }
                });
            }

            submittedData.start_at = formatDateTimeForServer(submittedData.start_at);
            submittedData.end_at = formatDateTimeForServer(submittedData.end_at);

            submittedData.job_applicant_id = this.jobApplicantId;

            this.save(submittedData)
        },
        afterSuccess(response) {
            this.$hub.$emit('activeTargetTab', 'events');
            this.$hub.$emit('getSelectedJobApplicantEvents');
            this.preloader = false;
            this.$toastr.s(response.data.message);
        },
        afterError(error) {
            this.preloader = false;
            this.$toastr.s(error.data.message);
        },
        getAttendees() {
            this.attendeesLoading = true;
            let url = `${SELECTABLE}/${this.selectedJobPost.id}/hiring-team`;
            axiosGet(url).then((res) => {
                this.attendeesList = res.data.map(el => {
                    return {
                        id: el.id,
                        value: el.user.full_name
                    }
                });
                this.attendeesLoading = false;
            })
        },
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;

            // new Date(response.data.start_at + " " + response.data.start_time);
            this.formData.start_at = localDateTimeFromUtc(response.data.start_at);


            // new Date(response.data.end_at + " " + response.data.end_at);
            this.formData.end_at = localDateTimeFromUtc(response.data.end_at);

            this.formData.attendees = this.formData.attendees.map(item => item.hiring_team_id);

            if (!response.data.meeting) {
                this.formData.video_meeting = 2;
            } else {
                this.formData.video_meeting = 1;
                this.formData.topic = response.data.meeting.topic;
                this.formData.duration = response.data.meeting.duration;
                this.isDivActive = true;
            }

            this.preloader = false;
        },
        setEndDateAsStartDate() {

            let tempStartDate = moment(moment(Date.parse(this.formData.start_at))).format('YYYY-MM-DD'),
                tempEndDate = moment(moment(Date.parse(this.formData.end_at))).format('YYYY-MM-DD'),
                difference = moment(tempEndDate).diff(moment(tempStartDate), 'days');

            if (difference < 0) {
                this.formData.end_at = this.formData.start_at;
            }
        },
        radioButtonOnChange() {
            this.isDivActive = parseInt(this.formData.video_meeting) === 1;
        },
    },
}
</script>