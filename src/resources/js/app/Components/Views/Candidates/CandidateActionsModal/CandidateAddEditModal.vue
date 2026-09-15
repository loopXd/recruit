<template>
    <modal :modal-id="modalId" :title="selectedUrl ? $editLabel('candidate') : $addLabel('candidate')"
        :preloader="preloader" :modal-scroll="false" modal-size="large" @submit="submit" @closeModal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader" />
            <form class="mb-0" :class="{ 'loading-opacity': preloader }" ref="form" data-url="">
                <div class="form-group row align-items-center">
                    <label for="firstName" class="col-sm-3 mb-0">
                        {{ $t('first_name') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="firstName" type="text" v-model="formData.first_name"
                            :placeholder="$placeholder('first_name')" :required="true" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="lastName" class="col-sm-3 mb-0">
                        {{ $t('last_name') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="lastName" type="text" v-model="formData.last_name"
                            :placeholder="$placeholder('last_name')" :required="true" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="email" class="col-sm-3 mb-0">
                        {{ $t('email') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="email" type="email" v-model="formData.email" :placeholder="$placeholder('email')"
                            :required="true" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-sm-3 mb-0">
                        {{ $t('gender') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input type="radio" v-model="formData.gender" :list="genderLists" :required="true" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="phone" class="col-sm-3 mb-0">
                        {{ $t('phone') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="phone" type="tel-input" v-model="formData.phone"
                            :placeholder="$placeholder('phone')" :required="true" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="birthDate" class="col-sm-3 mb-0">
                        {{ $t('date_of_birth') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input id="birthDate" type="date" v-model="formData.date_of_birth" />
                    </div>
                </div>
                <div v-if="!(selectedUrl || selectedJobId)" class="form-group row align-items-center">
                    <label for="jobPost" class="col-sm-3 mb-0">
                        {{ $t('job_post') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input type="search-select" :placeholder="$chooseLabel('job_post')" id="jobPost"
                            list-value-field="name" :list="jobList" :required="true" v-model="formData.job_post_id" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="email" class="col-sm-3 mb-0">
                        {{ $t('upload_resume') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input type="file" :label="$t('upload_resume')" v-model="resume"
                            :error-message="$errorMessage(errors, 'resume')" />
                    </div>
                </div>
            </form>
        </template>
    </modal>
</template>

<script>
import { ModalMixin } from '../../../../Mixins/ModalMixin';
import { FormMixin } from '../../../../../core/mixins/form/FormMixin';
import ErrorHandlerMixin from "../../../../Mixins/ErrorHandlerMixin";
import { CANDIDATE } from "../../../../Config/ApiUrl";
import DateFunction from "../../../../../core/helpers/date/DateFunction";
import { mapGetters } from "vuex";
import { formDataAssigner } from "../../../../Helpers/Helpers";

export default {
    name: 'CandidateAddEditModal',
    mixins: [ModalMixin, FormMixin, ErrorHandlerMixin],
    props: {
        tableId: {},
        verifyEmail: {},
        selectedJobId: {}
    },
    data() {
        return {
            CANDIDATE,
            preloader: false,
            modalId: 'candidate-add-edit-Modal',
            formData: {
                first_name: '',
                last_name: '',
                email: this.verifyEmail ? this.verifyEmail : '',
                job_post_id: this.selectedJobId ? this.selectedJobId : '',
                date_of_birth: new Date(),
                gender: 'male',
                phone: '',
            },
            resume: null,
            genderLists: [
                { id: 'male', value: this.$t('male') },
                { id: 'female', value: this.$t('female') },
                { id: 'other', value: this.$t('other') },
            ],
            editableEmail: ''
        }
    },
    computed: {
        ...mapGetters(['jobList'])
    },
    methods: {
        submit() {
            this.errorHandle = true;
            let url = this.selectedUrl ? this.selectedUrl : CANDIDATE;

            if (this.formData.date_of_birth)
                this.formData.date_of_birth = DateFunction.getDateFormatForBackend(this.formData.date_of_birth);

            let formData = { ...this.formData };
            if (this.selectedUrl && (this.editableEmail === this.formData.email)) {
                delete formData.email;
            }
            formData = formDataAssigner(new FormData, formData);

            if (this.resume) {
                formData.append("resume", this.resume);
            }

            if (this.selectedUrl) {
                formData.append('_method', 'patch')
            }
            this.submitFromFixin('post', url, formData);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit(`reload-${this.tableId}`);
            this.closeModal();
        },
        afterError(res) {
            this.errors = res.data.errors;
            if (this.errorHandle) this.handleFormSubmissionError('formData', res.data.errors);
        },
        afterSuccessFromGetEditData(response) {
            this.formData.first_name = response.data.first_name;
            this.formData.last_name = response.data.last_name;
            this.formData.email = response.data.email;
            this.formData.gender = response.data.gender;
            this.formData.phone = response.data.phone;
            this.formData.date_of_birth = response.data.date_of_birth;
            this.editableEmail = response.data.email;
            this.preloader = false;
        }
    },
}
</script>
