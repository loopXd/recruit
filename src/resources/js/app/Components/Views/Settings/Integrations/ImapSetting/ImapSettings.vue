<template>
    <div class="container-fluid p-0">
        <app-overlay-loader v-if="preloader" />
        <form v-else ref="form" data-url="/admin/app/settings/imap-settings" class="mb-0"
            :class="{ 'loading-opacity': preloader }">
            <app-note :class="'mb-primary clearfix'" :title="$fieldTitle('note')" content-type="html"
                :notes="$t('imap_documentation_notes')" />

            <div class="form-group row align-items-center">
                <label for="imap_server" class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t('imap_server') }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input id="imap_server" type="text" v-model="formData.imap_server"
                        :placeholder="$placeholder('imap_server')" :required="true" />
                </div>
            </div>

            <div class="form-group row align-items-center">
                <label for="imap_port" class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t('imap_port') }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input id="imap_port" type="number" v-model="formData.imap_port"
                        :placeholder="$placeholder('imap_port')" :required="true" />
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label for="imapEncryptionType" class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t('encryption_type') }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input id="imapEncryptionType" type="select" v-model="formData.imap_encryption"
                        :placeholder="$placeholder('encryption_type')" :list="encryptionTypes" :required="true" />
                </div>
            </div>

            <div class="form-group row align-items-center">
                <label for="imap_user" class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t('user') }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input id="imap_user" type="text" v-model="formData.imap_user"
                        :placeholder="$placeholder('user')" :required="true" />
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label for="password" class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t('password') }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input id="password" type="password" v-model="formData.imap_password"
                        :placeholder="$placeholder('password')" :show-password="true" :required="true" />
                </div>
            </div>

            <div v-if="$have('PERMISSION_UPDATE_DELIVERY_SETTINGS')" class="mt-5 action-buttons">
                <button class="btn btn-primary mr-2" @click.prevent="submit">
                    {{ $t('save') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { axiosGet } from "../../../../../Helpers/AxiosHelper";
import { GET_IMAP_SETTINGS } from "../../../../../Config/ApiUrl";
import { FormMixin } from "../../../../../../core/mixins/form/FormMixin";

export default {
    name: "ImapSettings",
    mixins: [FormMixin],
    data() {
        return {
            preloader: false,
            formData: {
                imap_server: '',
                imap_port: '',
                imap_encryption: '',
                imap_user: '',
                imap_password: ''
            },
            encryptionTypes: [
                { id: '', value: this.$t('choose_one') },
                { id: 'tls', value: this.$t('tls') },
                { id: 'ssl', value: this.$t('ssl') },
            ],
            imap_documentation_notes: 'The application will support PHP 8.x version after this update. We request you to upgrade your server\'s PHP version to 8.x immediately. Our future update will not be compatible with PHP 7.4 version any more. ' +
                'Make sure that these extension are enabled,\n' +
                '<br>\n' +
                '<br>\n' +
                'For php8.0<br>\n' +
                '<kbd>nd_mysqli</kbd> <kbd>nd_pdo_mysql</kbd> <kbd>pdo</kbd> <kbd>pdo_oci</kbd> <kbd>pdo_odbc</kbd>\n' +
                ' <kbd>pcntl_signal</kbd><br>\n' +
                '<br>\n If you update to php8.1 that will also work.'
        }
    },
    created() {
        this.getZoomSettings();
    },
    methods: {
        beforeSubmit() {
            this.preloader = true;
        },
        submit() {
            this.save(this.formData);
        },
        afterFinalResponse() {
            this.preloader = false;
        },
        afterSuccess(res) {
            this.$toastr.s(res.data.message);
        },
        afterError(res) {
            this.$toastr.e(res.data.message);
        },
        getZoomSettings() {
            this.preloader = true;
            axiosGet(GET_IMAP_SETTINGS).then((res) => {
                if (res.data.length !== 0) {
                    this.formData = res.data;
                }
                this.preloader = false;
            })
        },
    }
}
</script>