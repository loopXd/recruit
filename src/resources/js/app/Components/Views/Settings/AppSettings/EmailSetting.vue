<template>
    <div class="container-fluid p-0">
        <app-overlay-loader v-if="preloader"/>
        <form v-else ref="form" data-url="/admin/app/settings/delivery-settings"
              class="mb-0"
              :class="{'loading-opacity': preloader}">
            <div class="form-group row align-items-center">
                <label for="emailSettingsProvider" class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t('supported_mail_services') }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input id="emailSettingsProvider"
                               type="select"
                               v-model="emailSettings.provider"
                               :list="providerList"
                               :required="true"/>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label for="emailSettingsFromName" class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t('email_sent_from_name') }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input id="emailSettingsFromName"
                               type="text"
                               v-model="emailSettings.from_name"
                               :placeholder="$placeholder('email_sent_from_name')"
                               :required="true"/>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label for="emailSettingsFromEmail" class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t('email_sent_from_email') }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input id="emailSettingsFromEmail"
                               type="email"
                               v-model="emailSettings.from_email"
                               :placeholder="$placeholder('email_sent_from_email')"
                               :required="true"/>
                </div>
            </div>


            <!-- For Amazon SES -->
            <fieldset v-if="emailSettings.provider === 'ses'">
                <div class="form-group row align-items-center">
                    <label for="emailSettingsHostname" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t('hostname') }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input id="emailSettingsHostname"
                                   type="text"
                                   v-model="emailSettings.hostname"
                                   :placeholder="$placeholder('hostname')"
                                   :required="true"/>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="emailSettingsAccessKeyId" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t('access_key_id') }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input id="emailSettingsAccessKeyId"
                                   type="password"
                                   :show-password="true"
                                   v-model="emailSettings.access_key_id"
                                   :placeholder="$placeholder('access_key_id')"
                                   :required="true"/>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="emailSettingsSecretAccessKey" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t('secret_access_key') }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input id="emailSettingsSecretAccessKey"
                                   type="password"
                                   :show-password="true"
                                   v-model="emailSettings.secret_access_key"
                                   :placeholder="$placeholder('secret_access_key')"
                                   :required="true"/>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="emailSettingsRegion" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t('region') }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input id="emailSettingsRegion"
                                   type="text"
                                   v-model="emailSettings.region"
                                   :placeholder="$placeholder('region')"
                                   :required="true"/>
                    </div>
                </div>
            </fieldset>

            <!-- For Mailgun -->
            <fieldset v-else-if="emailSettings.provider === 'mailgun'">
                <div class="form-group row align-items-center">
                    <label for="emailSettingsDomainName" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t('domain_name') }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input id="emailSettingsDomainName"
                                   type="text"
                                   v-model="emailSettings.domain_name"
                                   :placeholder="$placeholder('domain_name')"
                                   :required="true"/>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="emailSettingsApiKey" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t('api_key') }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input id="emailSettingsApiKey"
                                   type="password"
                                   :show-password="true"
                                   v-model="emailSettings.api_key"
                                   :placeholder="$placeholder('api_key')"
                                   :required="true"/>
                    </div>
                </div>
            </fieldset>

            <!-- For SMTP -->
            <fieldset v-else-if="emailSettings.provider === 'smtp'">
                <div class="form-group row align-items-center">
                    <label for="user_name" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t("user_name") }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input
                            id="user_name"
                            type="text"
                            v-model="emailSettings.smtp_user_name"
                            :placeholder="$placeholder('user_name')"
                            :required="true"
                        />
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label for="emailSettingsSmtpHost" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t('smtp_host') }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input id="emailSettingsSmtpHost"
                                   type="text"
                                   v-model="emailSettings.smtp_host"
                                   :placeholder="$placeholder('smtp_host')"
                                   :required="true"/>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="emailSettingsSmtpPort" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t('port') }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input id="emailSettingsSmtpPort"
                                   type="number"
                                   v-model="emailSettings.smtp_port"
                                   :placeholder="$placeholder('port')"
                                   :required="true"/>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="emailSettingsEmailPassword" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t('password_to_access') }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input id="emailSettingsEmailPassword"
                                   type="password"
                                   :show-password="true"
                                   v-model="emailSettings.email_password"
                                   :placeholder="$placeholder('password_to_access')"
                                   :required="true"/>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="emailSettingsEncryptionType" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t('encryption_type') }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input id="emailSettingsEncryptionType"
                                   type="select"
                                   v-model="emailSettings.encryption_type"
                                   :placeholder="$placeholder('encryption_type')"
                                   :list="encryptionType"
                                   :required="true"/>
                    </div>
                </div>
            </fieldset>

            <div v-if="$have('PERMISSION_UPDATE_DELIVERY_SETTINGS')" class="mt-5 action-buttons">
                <button class="btn btn-primary mr-2" @click.prevent="submit">
                    {{ $t('save') }}
                </button>
                <app-button
                    v-if="testMailButton"
                    :isDisabled="false"
                    :label="$t('test_email')"
                    class="btn-secondary ml-2"
                    @submit="testMailModal = true"
                />
            </div>
        </form>
        <app-test-mail-modal
            v-if="testMailModal"
            v-model="testMailModal"
        />
        <app-confirmation-modal
            :title="$t('wait_a_minute')"
            :message="$t('popup_subtitle_for_redirect_notification_template')"
            modal-class="primary"
            :first-button-name="$t('okay')"
            :second-button-name="$t('no')"
            icon="trello"
            v-if="promptIsActive"
            modal-id="app-confirmation-modal"
            @confirmed="triggerConfirm"
            @cancelled="promptIsActive = false"
        />
    </div>
</template>

<script>
import * as actions from '../../../../Config/ApiUrl';
import {FormMixin} from '../../../../../core/mixins/form/FormMixin';
import {urlGenerator} from "../../../../Helpers/AxiosHelper";
import {NOTIFICATION_SETTINGS_FRONT_END} from "../../../../Config/ApiUrl";

export default {
    name: 'EmailSetting',
    mixins: [FormMixin],
    data() {
        return {
            promptIsActive: false,
            promptActiveAble: false,
            preloader: false,
            emailSettings: {region: 'us-east-1'},
            providerList: [
                {id: '', value: this.$t('choose_one')},
                {id: 'ses', value: this.$t('amazon_ses')},
                {id: 'mailgun', value: this.$t('mailgun')},
                {id: 'smtp', value: this.$t('smtp')},
                {id: 'sendmail', value: this.$t('sendmail')},
            ],
            encryptionType: [
                {id: '', value: this.$t('choose_one')},
                {id: 'tls', value: this.$t('tls')},
                {id: 'ssl', value: this.$t('ssl')},
            ],
            testMailButton: false,
            testMailModal: false,
        }
    },
    created() {
        this.getEmailData();
    },
    methods: {
        getEmailData() {
            this.preloader = true;
            let instance = this;
            instance.axiosGet(
                actions.GET_EMAIL_SETTINGS
            ).then(response => {
                if (!_.isEmpty(response.data)) {
                    instance.emailSettings = response.data;
                    this.testMailButton = true;
                }else{
                    this.promptActiveAble = true
                }
            }).catch(({response}) => {

            }).finally(() => {
                this.preloader = false;
            });
        },
        beforeSubmit() {
            this.preloader = true;
        },
        submit() {
            this.save(this.emailSettings);
        },
        afterFinalResponse() {
            this.preloader = false;
        },
        afterSuccess(res) {
            if (this.promptActiveAble) {
                this.promptIsActive = true;
            }
            this.testMailButton = true;
            this.$toastr.s(res.data.message);
        },
        afterError(res) {
            this.$toastr.e(res.data.message);
        },
        triggerConfirm(){
            window.location = urlGenerator(NOTIFICATION_SETTINGS_FRONT_END);
        }
    }
}
</script>
