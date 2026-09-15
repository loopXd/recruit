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
                    <!-- <a class="nav-link">
                        <span class="step-number">
                            01
                            <span class="complete-icon">
                                <app-icon name="check" />
                            </span>
                            <span class="step-divider"><span class="divider" /></span>
                        </span>
                        <span class="step-name">{{ basicInformation.title }}</span>
                    </a> -->
                    <!-- <pre>{{ applyFormSettings }}</pre> -->
                    <a v-for="(item, index) in applyFormSettings" class="nav-link" :id="`v-pills-${index}-tab`">
                        <!-- <pre>{{ item }}</pre> -->
                        <span class="step-number">
                            {{ `${(index + 1) > 10 ? (index + 1) : `0${index + 1}`}` }}
                            <span v-if="currentIndex > index" class="complete-icon">
                                <app-icon name="check" />
                            </span>
                            <span class="step-divider"><span class="divider" /></span>
                        </span>
                        <span class="step-name">
                            {{ item.title && item.title.use_custom ? item.title.custom : item.title.default }}
                        </span>
                    </a>
                </div>
            </div>
            <div class="candidate-step-content">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active">
                        <h4 class="mb-5">
                            {{ selectedApplyFormSettings.title && selectedApplyFormSettings.title.use_custom ?
                                selectedApplyFormSettings.title.custom : selectedApplyFormSettings.title.default }}
                        </h4>
                        <div class="row">
                            <div class="col-lg-9 col-xl-7">
                                <CandidateInputReceiveFields :key="selectedApplyFormSettings.step"
                                    :formBody="selectedApplyFormSettings" :current-index="currentIndex"
                                    @next="goNextQuesStep" @previous="goPrevQuesStep" />
                            </div>
                        </div>
                    </div>
                    <!-- <div v-for="(item, parentIndex) in applyForm" class="tab-pane fade"
                       >
                        <div class="row">
                            <div class="col-lg-9 col-xl-7">
                                <div>
                                    <h4 class="mb-4">
                                        {{ translateLang(item.title) }}
                                    </h4>
                                    <div v-for="(field, childIndex) in item.fields" class="mb-4">
                                        <div v-if="field.show">
                                            <div v-if="field.type === 'custom-form'" class="d-flex justify-content-between">
                                                <label
                                                    class=" mx-2 mb-3 text-black-50 font-weight-bold text-uppercase font-2xl"
                                                    :for="nameGen(field.name)" style=" letter-spacing: 6px;"> {{
                                                        translateLang(field.name) }}
                                                    <sup v-if="field.require">*</sup>
                                                </label>

                                                <p v-if="errorObj[nameGen(field.name)]"
                                                    class="text-danger validation-error mb-0">{{ translateLang(field.name) +
                                                        ' ' + $t('is_required') }}</p>

                                            </div>
                                            <label v-else :for="nameGen(field.name)">
                                                {{ translateLang(field.name) }}
                                                <sup v-if="field.require">*</sup>
                                            </label>
                                            <template v-if="field.type === 'custom-form'">
                                                <div class="px-2" :key="checkLoop">
                                                    <div class="mb-4" v-for="(subField, subIndex) in field.custom_form"
                                                        :key="'subField' + subIndex">
                                                        <label :for="nameGen(subField.name)">{{ translateLang(subField.name)
                                                        }}
                                                            <sup v-if="field.require">*</sup>
                                                        </label>
                                                        <app-input class="mb-1" :type="subField.type"
                                                            :id="field.name + subField.name + subIndex"
                                                            :list="listGen(subField.options, subField.type, subField)"
                                                            :radio-checkbox-name="(subField.type === 'radio' || subField.type === 'checkbox') ? nameGen(subField.name) : ''"
                                                            :error-message="$errorMessage(errorObj, nameGen(subField.name) + [subIndex], false)"
                                                            :name="field.name + subField.name + subIndex"
                                                            :required="field.require"
                                                            @input="getNestedInput(item.key, nameGen(field.name), field.custom_form, nameGen(subField.name), subField.group, $event)"
                                                            v-model="test[`${nameGen(field.name)}_${subIndex}`]"
                                                            :listItemModelValue="['radio', 'checkbox', 'select'].includes(subField.type) ? 'value' : 'id'" />
                                                        <div class="d-flex justify-content-end">
                                                            <button
                                                                v-if="subField.group > 0 && (subField.group < field?.custom_form[subIndex + 1]?.group || (field.custom_form.length - 1) == subIndex)"
                                                                type="button"
                                                                class="btn text-danger d-inline-flex align-items-center mt-3 p-0"
                                                                @click="deleteSubFields(parentIndex, childIndex, subField.group, item.key, nameGen(field.name))">
                                                                {{ $t('remove') }}
                                                            </button>
                                                        </div>
                                                        <div v-if="subField.group < field?.custom_form[subIndex + 1]?.group"
                                                            class="mb-2 mt-4 border-bottom">
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane-action mb-5">
                                                        <button type="button" class="btn btn-block justify-content-center"
                                                            @click="addSubFields(parentIndex, childIndex)">
                                                            <app-icon name="plus" class="size-14 mr-2" />
                                                            {{ $t('add_new') }}
                                                        </button>
                                                    </div>
                                                </div>

                                            </template>


<app-input v-else :type="field.type" :id="nameGen(field.name)" :list="listGen(field.options, field.type, field)"
    :radio-checkbox-name="(field.type === 'radio' || field.type === 'checkbox') ? nameGen(field.name) : ''"
    :error-message="$errorMessage(errorObj, nameGen(field.name), false)" :required="field.require"
    v-model="submitData[item.key][nameGen(field.name)]"
    :listItemModelValue="['radio', 'checkbox', 'select'].includes(field.type) ? 'value' : 'id'" />
</div>
</div>
</div>
<div class="tab-pane-action">
    <button type="button"
        @click.prevent="goPrevQuesStep(parentIndex, item.key, parentIndex > 0 ? applyForm[parentIndex - 1].key : '')">
        <app-icon name="chevron-left" class="mr-2" />
        {{ $t('previous') }}
    </button>
    <button type="button"
        @click.prevent="goNextQuesStep(parentIndex, item.key, parentIndex < (applyForm.length - 1) ? applyForm[parentIndex + 1].key : '')">
        {{ $t('next') }}
        <app-icon name="chevron-right" class="ml-2" />
    </button>
</div>
</div>
</div>
</div> -->
                    <div class="tab-pane fade">
                        <div>
                            <h4 class="mb-2">
                                <!-- {{ finalStep.title }} - {{ appliedDone ? $t('submitted') : $t('in_progress') }} -->
                            </h4>
                            <div class="row mb-4">
                                <div class="col-12 min-height-300 py-primary">
                                    <!-- <h5 class="mb-3">{{ basicInformation.title }}</h5> -->
                                    <hr />
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="w-50">
                                                    <span class="text-muted">{{ $t('first_name') }}</span>
                                                    <!-- <p class=" fs-6">{{ submitApplicant.first_name }}</p> -->
                                                </td>
                                                <td class="w-50">
                                                    <span class="text-muted">{{ $t('last_name') }}</span>
                                                    <!-- <p class=" fs-6">{{ submitApplicant.last_name }}</p> -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w-50">
                                                    <span class="text-muted">{{ $t('email') }}</span>
                                                    <!-- <p class=" fs-6">{{ submitApplicant.email }}</p> -->
                                                </td>
                                                <td class="w-50">
                                                    <span class="text-muted">{{ $t('phone') }}</span>
                                                    <!-- <p class=" fs-6">{{ submitApplicant.phone }}</p> -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w-50" v-if="submitApplicant.gender">
                                                    <span class="text-muted">{{ $t('gender') }}</span>
                                                    <p class=" fs-6">{{ submitApplicant.gender }}</p>
                                                </td>
                                                <td class="w-50" v-if="submitApplicant.date_of_birth">
                                                    <span class="text-muted">{{ $t('date_of_birth') }}</span>
                                                    <p class=" fs-6">{{ submitApplicant.date_of_birth }}</p>
                                                </td> -->
                                            </tr>
                                        </tbody>
                                    </table>

                                    <h5 class="mt-3">{{ $t('others_information') }}</h5>
                                    <hr />
                                    <template>
                                        <!-- <div v-for="(ans, ansIndex) in tempFormData.question_answer" v-if="ans.answer"
                                            class="mt-1 p-2">
                                            <div class="row" v-if="typeof ans.answer === 'string'">
                                                <span class="text-muted col-3 col-xl-2">{{ questionTitle(ans.question)
                                                }}</span>
                                                <p class="mb-0 col-9 col-xl-10">{{ ans.answer }}</p>
                                            </div>
                                            <div v-else>
                                                <h6 class="mb-3 text-black-50 text-uppercase mt-4"
                                                    style=" letter-spacing: 6px;">
                                                    {{
                                                        questionTitle(ans.question) }}</h6>
                                                <div class="" v-for="(value, tempIndex) in ans.answer"
                                                    :key="`item${tempIndex}`">
                                                    <div v-for="(labelValue, labelName, subIndex) in value"
                                                        :key="labelName + subIndex" class="row">
                                                        <div class="col-3 col-xl-2">
                                                            <p class="mb-1 text-muted">{{ $t(labelName) }}</p>
                                                        </div>
                                                        <div class="col-9 col-xl-10">
                                                            <p>{{ labelValue }}</p>
                                                        </div>
                                                    </div>
                                                    <hr v-if="tempIndex < Object.keys(value).length - 1" />
                                                    <div v-else class="pb-4"></div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane-action">
                            <button type="button" @click.prevent="goToQuestionLastStep">
                                <app-icon name="chevron-left" class="mr-2" />
                                {{ $t('previous') }}
                            </button>
                            <app-load-more :preloader="preloader" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Email Verification Modal -->
        <candidate-email-verification-modal v-if="emailVerificationModal" :job-post-id="applyFormData.id"
            @dontVerified="goToJobPostView" @verifiedData="afterVerifiedEmail" />

        <app-confirmation-modal v-if="showConfirmationModal" :icon="confirmationModalIcon" :hide-second-button="true"
            :title="confirmationModalTitle" :message="confirmationModalMessage" :modal-class="confirmationModalClass"
            modal-id="confirmation-modal" :first-button-name="$t('ok')" :second-button-name="''" />
    </div>
</template>

<script>

import CandidateInputReceiveFields from "./CandidateInputReceiveFields.vue";

export default {
    name: 'CandidateApplicationForm',

    components: { CandidateInputReceiveFields },
    props: {
        applyFormData: {}
    },
    data() {
        return {
            preloader: false,
            emailVerificationModal: false,
            showConfirmationModal: false,
            selectedApplyFormSettings: "",
            formData: [],
            currentIndex: 0
        }
    },
    computed: {
        applyFormSettings() {
            const newArr = []

            this.applyFormData.apply_form_settings.forEach((item, index) => {
                const obj = {};
                obj.step = index;
                obj.is_visible = item.isVisible;
                obj.title = {};
                obj.title.use_custom = true;
                obj.title.custom = item.title;
                obj.title.default = item.title;
                obj.icon = item.icon;
                obj.fields = [];
                item.fields.forEach((iterator, idx) => {
                    obj.fields[idx] = {};
                    obj.fields[idx].name = {}
                    obj.fields[idx].name.label = iterator.name
                    obj.fields[idx].name.key = this.nameGen(iterator.name)
                    obj.fields[idx].type = iterator.type
                    obj.fields[idx].value = ''
                    obj.fields[idx].is_visible = iterator.show
                    obj.fields[idx].require = iterator.require

                    if ('custom_form' in iterator) {
                        obj.fields[idx].custom_form = [];
                        iterator.custom_form.forEach((subField, subIdx) => {
                            console.log(subIdx);
                            obj.fields[idx].custom_form[subIdx] = {}
                            obj.fields[idx].custom_form[subIdx][this.nameGen(subField.name)] = {}
                            obj.fields[idx].custom_form[subIdx][this.nameGen(subField.name)].label = subField.name
                            obj.fields[idx].custom_form[subIdx][this.nameGen(subField.name)].type = subField.type
                            obj.fields[idx].custom_form[subIdx][this.nameGen(subField.name)].value = ""
                            obj.fields[idx].custom_form[subIdx][this.nameGen(subField.name)].is_visible = subField.show
                            obj.fields[idx].custom_form[subIdx][this.nameGen(subField.name)].require = subField.require
                            // obj.fields[idx].custom_form[subIdx].name.label = subField.name
                            // obj.fields[idx].custom_form[subIdx].name.key = this.nameGen(subField.name)
                            // obj.fields[idx].custom_form[subIdx].type = subField.type
                            // obj.fields[idx].custom_form[subIdx].value = ''
                            // obj.fields[idx].custom_form[subIdx].is_visible = subField.show
                            // obj.fields[idx].custom_form[subIdx].require = subField.require
                        })
                    }
                })
                newArr.push(obj)
            })
            return newArr;
        }
    },
    mounted() {
        this.selectedApplyFormSettings = this.applyFormSettings[this.currentIndex]
    },
    methods: {
        toggleSidebar() {
            $('.toggle-sidebar').toggleClass('change');
            $('.candidate-step-menu').toggleClass('active');
        },
        nameGen(name) {
            return _.snakeCase(_.lowerCase(name));
        },
        goNextQuesStep(data) {
            this.formData.push(data)
            this.currentIndex++;
            this.selectedApplyFormSettings = this.applyFormSettings[this.currentIndex]
        },
        goPrevQuesStep() {
            this.formData.pop();
            this.currentIndex--;
            this.selectedApplyFormSettings = this.applyFormSettings[this.currentIndex]
        }
    }
}
</script>
