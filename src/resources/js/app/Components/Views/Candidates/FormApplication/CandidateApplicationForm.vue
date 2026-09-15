<template>
    <div>
        <div class="candidate-application-form">
            <div class="candidate-step-menu custom-scrollbar">
                <div class="toggle-sidebar" @click="toggleSidebar">
                    <div class="bar1" />
                    <div class="bar2" />
                    <div class="bar3" />
                </div>
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <div class="step-menu-heading d-flex align-items-center">
                        <div>
                            <p class="lead mb-0">{{ applyFormData.name }}</p>
                            <span class="text-muted mb-0" style="font-size: 0.835rem;">{{
                                applyFormData?.location?.address }}</span>
                        </div>
                    </div>
                    <template v-for="(item, index) in formDataBody">
                        <a v-if="item.is_visible" class="nav-link"
                            :class="{ 'complete': formData.length >= index, 'active': formData.length === index }"
                            :id="`v-pills-${index}-tab`" :key="`nav-link-${index}`">
                            <!-- <pre>{{ item }}</pre> -->
                            <span class="step-number">
                                {{ `${(index + 1) > 10 ? (index + 1) : `0${index + 1}`}` }}
                                <span v-if="formData.length > index" class="complete-icon">
                                    <app-icon name="check" />
                                </span>
                                <span class="step-divider"><span class="divider" /></span>
                            </span>
                            <span class="step-name">{{ item.title }}</span>
                        </a>
                    </template>
                </div>
            </div>
            <div class="candidate-step-content">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" v-if="isEmailVerified">
                        <app-overlay-loader v-if="loading" />
                        <template v-else>
                            <h4 class="mb-5">{{ formDataBody[formData.length]?.title }}</h4>
                            <div class="row">
                                <div class="col-lg-9 col-xl-7">
                                    <div v-for="(form, index) in formDataBody" :key="form.id">
                                        <form-step v-if="formData.length === index && form.is_visible"
                                            :selected-form="selectedForm" :steps="formDataBody.length" :index="index"
                                            :form-body="form" @next="goNextStep" @previous="goPrevStep"
                                            :key="formDataBody.length" />
                                    </div>
                                    <div v-if="readyToSubmit">
                                        <div v-for="(step, stepIndex) in formData" :key="`step-${stepIndex}`">
                                            <h4>{{ step.title }}</h4>
                                            <div v-for="(form, formIndex) in step.items" :key="`form-${formIndex}`"
                                                class="my-2 text-muted">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr v-for="(field, index) in form.fields"
                                                            :key="`item-${index}`">
                                                            <td class="w-50 px-0"
                                                                :colspan="field.type === 'custom-form' ? 2 : 1"
                                                                :class="{ 'border-bottom': field.type === 'custom-form' }">
                                                                <template v-if="field.type === 'custom-form'">
                                                                    <label
                                                                        class="mb-3 text-black-50 font-weight-bold text-uppercase font-2xl"
                                                                        style=" letter-spacing: 6px;">{{ field.title
                                                                        }}</label>
                                                                </template>
                                                                <template v-else>
                                                                    <span class="text-muted">{{ field.title }}</span>
                                                                </template>
                                                                <table v-if="field.type === 'custom-form'"
                                                                    class="table table-borderless m-0">
                                                                    <tbody>
                                                                        <tr v-for="(f, i) in field.fields"
                                                                            :key="`custome-field-${index}-${i}`">
                                                                            <td class="w-50 px-0">
                                                                                <span class="text-muted">{{ f.title
                                                                                    }}</span>
                                                                            </td>
                                                                            <td class="w-50 px-0">
                                                                                <span class="text-muted"
                                                                                    v-if="['text', 'email', 'number', 'textarea', 'tel-input'].includes(f.type)">{{
                                                                                        f.value
                                                                                        || '-' }}</span>
                                                                                <span class="text-muted"
                                                                                    v-else-if="['radio', 'checkbox', 'select'].includes(f.type)">
                                                                                    <app-input
                                                                                        :id="`item-${f.id}-${index}`"
                                                                                        :key="`item-${f.id}-${index}`"
                                                                                        :radioCheckboxName="f.title"
                                                                                        v-model="f.value"
                                                                                        :list="(f.options || []).map(i => ({ id: i, value: i }))"
                                                                                        :type="f.type"
                                                                                        :disabled="true" />
                                                                                </span>
                                                                                <span class="text-muted" v-else>{{
                                                                                    f.type === 'date' ?
                                                                                        formatDateToLocal(f.value) || '-' :
                                                                                        f.value || '-' }}</span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td class="w-50 px-0" v-if="field.type !== 'custom-form'">
                                                                <span v-if="field.type === 'dropzone'"
                                                                    class="text-muted">
                                                                    <div v-for="(file) in field.value">
                                                                        <span>{{ file.name }}</span>
                                                                    </div>
                                                                </span>
                                                                <span class="text-muted"
                                                                    v-else-if="['text', 'email', 'number', 'textarea', 'tel-input'].includes(field.type)">{{
                                                                        field.value
                                                                        || '-' }}</span>
                                                                <span class="text-muted"
                                                                    v-else-if="['radio', 'checkbox', 'select'].includes(field.type)">
                                                                    <app-input :id="`item-${field.id}-${index}`"
                                                                        :key="`item-${field.id}-${index}`"
                                                                        :radioCheckboxName="field.title"
                                                                        v-model="field.value"
                                                                        :list="(field.options || []).map(i => ({ id: i, value: i }))"
                                                                        :type="field.type" :disabled="true" />
                                                                </span>
                                                                <span v-else class="text-muted">{{ field.type === 'date'
                                                                    ? formatDateToLocal(field.value) || '-' :
                                                                    field.value || '-' }}</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <!--                                        <button type="button" @click="submitApplication" :disabled="applicationSubmitted" class="btn btn-success">{{ $t('submit') }}</button>-->
                                            <app-submit-button :label="$t('submit')" :loading="preloader"
                                                @click="submitApplication" />
                                            <button type="button" @click="editApplication"
                                                :disabled="applicationSubmitted" class="btn btn-primary ml-2">{{
                                                    $t('edit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="tab-pane fade">
                    </div>
                </div>
            </div>
        </div>
        <!-- Email Verification Modal -->
        <candidate-email-verification-modal v-if="emailVerificationModal" :modal-id="emailVerificationModalId"
            :job-post-id="applyFormData.id" @cancelApplication="goToJobPostView" @verifiedData="afterVerifiedEmail"
            @close="closeEmailVerificationModal" />

        <app-confirmation-modal v-if="showConfirmationModal" :icon="confirmationModalIcon" :hide-second-button="true"
            :title="confirmationModalTitle" :message="confirmationModalMessage" :modal-class="confirmationModalClass"
            modal-id="confirmation-modal" :first-button-name="$t('ok')" :second-button-name="''"
            @confirmed="redirectToHome" />
    </div>
</template>

<script>
import { TENANT_BASE_URL } from "../../../../Config/UrlHelper";
import FormStep from "./FormStep.vue";
import { axiosPost } from "../../../../Helpers/AxiosHelper";
import { urlGenerator } from "../../../../Helpers/AxiosHelper";
import { formatDateToLocal } from "../../../../Helpers/DateTimeHelper";
import DateFunction from "../../../../../core/helpers/date/DateFunction";
import { FormMixin } from "../../../../../core/mixins/form/FormMixin";
import moment from 'moment-timezone';

const FIELD_MAPPING = {
    first_name: 0,
    last_name: 1,
    email: 9,
    gender: 7,
    date_of_birth: 4,
    phone: 8,
    mother_name: 2,
    father_name: 3,
    rg: 5,
    cpf: 6,
};

export default {
    props: ['applyFormData'],
    mixins: [FormMixin],
    components: { FormStep },
    data() {
        return {
            formatDateToLocal,
            preloader: false,
            emailVerificationModal: false,
            emailVerificationModalId: 'email-verification-modal',
            showConfirmationModal: false,
            confirmationModalMessage: '',
            confirmationModalTitle: '',
            confirmationModalClass: '',
            confirmationModalIcon: '',
            applicant_id: null,

            appliedDone: false,

            formDataBody: (typeof this.applyFormData.apply_form_settings === 'string'
                ? JSON.parse(this.applyFormData.apply_form_settings)
                : this.applyFormData.apply_form_settings).filter(item => item.is_visible),
            formData: [],
            selectedForm: null,
            readyToSubmit: false,
            applicationSubmitted: false,
            isEmailVerified: false,
            loading: false
        }
    },
    methods: {
        toggleSidebar() {
            $('.toggle-sidebar').toggleClass('change');
            $('.candidate-step-menu').toggleClass('active');
        },
        goNextStep(data, isFinalStep) {
            this.loading = true
            this.readyToSubmit = isFinalStep
            this.formData.push(data)
            this.selectedForm = null
            setTimeout(() => {
                this.loading = false
            }, 200)
        },
        goPrevStep() {
            this.loading = true
            let formData = this.formData.pop();
            formData.items = formData.items.map(i => ({ ...i, fields: i.fields.map(f => ({ ...f, value: f.type === 'dropzone' ? '' : f.value })) }))
            this.selectedForm = formData
            setTimeout(() => {
                this.loading = false
            }, 200)
        },
        goToJobPostView() {
            window.location = urlGenerator(`${TENANT_BASE_URL}/${this.applyFormData.slug}`);
        },
        afterVerifiedEmail(email, data) {
            const basic_information_form = this.formDataBody.find(i => i.key === 'basic_information');
            const fields = basic_information_form?.items?.[0]?.fields;

            if (!fields) return console.warn("⚠️ basic_information_form inválido ou sem campos.");

            // Preenche email e bloqueia edição
            if (fields[FIELD_MAPPING.email]) {
                fields[FIELD_MAPPING.email].value = email;
                fields[FIELD_MAPPING.email].disabled = true;
            }

            // Define limite da data de nascimento
            if (fields[FIELD_MAPPING.date_of_birth]) {
                fields[FIELD_MAPPING.date_of_birth].dateOptions = {
                    maxDate: moment().tz("America/Sao_Paulo").format("YYYY-MM-DD")
                };
            }

            // Preenche dados do usuário
            if (data) {
                for (const [key, index] of Object.entries(FIELD_MAPPING)) {
                    if (fields[index]) {
                        fields[index].value = data[key] ?? '';
                    }
                }
                this.applicant_id = data.id;
            } else {
                this.emailVerificationModal = false;
            }

            this.isEmailVerified = true;
        },
        submitApplication() {
            this.preloader = true;
            const url = `${TENANT_BASE_URL}/${this.applyFormData.slug}/apply`;
            const formData = new FormData();

            // Processa anexos
            for (let step of this.formData) {
                for (let item of step.items) {
                    for (let field of item.fields) {
                        if (field.type === 'dropzone' && field.value?.length) {
                            field.value.forEach((file, i) => {
                                formData.append(`attachments[${this.nameGen(field.title)}][${i}]`, file);
                            });
                            field.value = [];
                        }
                    }
                }
            }

            // Campos básicos
            const basic_information_form = this.formData.find(i => i.key === 'basic_information');
            const fields = basic_information_form?.items?.[0]?.fields || [];

            const basic_information = {};
            for (const [key, index] of Object.entries(FIELD_MAPPING)) {
                let value = fields[index]?.value ?? '';
                if (key === 'date_of_birth' && value) {
                    value = DateFunction.getDateFormatForBackend(value);
                }
                basic_information[key] = value;
            }

            formData.append('applicant_id', this.applicant_id);
            formData.append('basic_information', JSON.stringify(basic_information));
            formData.append('apply_form_setting', JSON.stringify(this.formData));

            axiosPost(url, formData)
                .then(res => {
                    this.confirmationModalMessage = res.data.message;
                    this.confirmationModalClass = 'success';
                    this.confirmationModalTitle = this.$t('thank_you');
                    this.confirmationModalIcon = 'check-circle';
                    this.appliedDone = true;
                    this.applicationSubmitted = true;
                })
                .catch(({ response }) => {
                    let errorMessage = '';
                    
                    if (response.data && response.data.errors && Array.isArray(response.data.errors.errors)) {
                        response.data.errors.errors.forEach(errorArray => {
                            if (errorArray && errorArray.length > 0) {
                                errorMessage += errorArray[0] + '\n';
                            }
                        });
                    } else {
                        errorMessage = response.data.message;
                    }

                    this.confirmationModalMessage = errorMessage.trim();
                    this.confirmationModalTitle = this.$t('oops');
                    this.confirmationModalClass = 'danger';
                    this.confirmationModalIcon = 'x-circle';
                    this.$toastr.e(this.confirmationModalMessage);
                })
                .finally(() => {
                    this.showConfirmationModal = true;
                    this.preloader = false;
                });
        },
        editApplication() {
            this.readyToSubmit = false;
            this.goPrevStep()
        },
        nameGen(value) {
            return value.split(' ').map(i => i.toLowerCase()).join('-')
        },
        closeEmailVerificationModal() {
            $(`#${this.emailVerificationModalId}`).modal('hide');
            this.emailVerificationModal = false
        },
        redirectToHome() {
            window.location = urlGenerator('/')
        }
    },
    mounted() {
        this.emailVerificationModal = false;
        this.isEmailVerified = true;
    },
}
</script>

