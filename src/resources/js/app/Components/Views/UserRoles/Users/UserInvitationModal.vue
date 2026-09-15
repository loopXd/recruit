<template>
    <modal :modal-id="userAndRoles.users.inviteModalId" :title="modalTitle" :preloader="preloader" :modal-scroll="false"
        @submit="submit" :hide-submit-button="!isMailSettingExist"
        :cancel-button-text="isMailSettingExist ? $t('cancel') : $t('close')" @closeModal="closeModal">

        <template slot="body">
            <app-overlay-loader v-if="preloader" />
            <form ref="form" v-if="isMailSettingExist || hasData"
                :data-url='hasData ? `admin/auth/users/attach-roles/${userAndRoles.rowData.id}` : `/admin/auth/users/invite-user`'
                :class="{ 'loading-opacity': preloader }">
                <div class="form-group row align-items-center" v-if="!hasData">
                    <label for="userEmail" class="col-sm-3 mb-0">
                        {{ $t('email') }}
                    </label>
                    <app-input id="userEmail" class="col-sm-9" type="email" v-model="formData.email"
                        :placeholder="$placeholder('email')" :required="true" />
                </div>
                <div class="form-group row align-items-center mb-0">
                    <label for="roles" class="col-sm-3 mb-0">
                        {{ $t('role') }}
                    </label>
                    <app-input id="roles" class="col-sm-9" type="search-select" :placeholder="$chooseLabel('role')"
                        :list="roleLists" list-value-field="name" :isAnimatedDropdown="true" v-model="role"
                        :required="true" />
                </div>
            </form>
            <app-note v-else :title="$t('no_delivery_settings_found')" :notes="`<ol>
                     <li>${$t('no_delivery_settings_warning', {
                location: `<a href='${urlGenerator(JOB_POINT_EMAIL_SETUP_SETTING)}'>${$t('here')}</a>`
            })}</li>
                  </ol>`" content-type="html" />
        </template>
    </modal>
</template>

<script>
import { FormMixin } from '../../../../../core/mixins/form/FormMixin';
import { ModalMixin } from '../../../../Mixins/ModalMixin';
import { UserAndRoleMixin } from '../Mixins/UserAndRoleMixin';
import * as actions from '../../../../Config/ApiUrl';
import { axiosGet, urlGenerator } from "../../../../Helpers/AxiosHelper";
import { JOB_POINT_EMAIL_SETUP_SETTING, MAIL_SETUP_CHECK_URL } from "../../../../Config/ApiUrl";
import ErrorHandlerMixin from "../../../../Mixins/ErrorHandlerMixin";


export default {
    name: "UserInvitationModal",
    mixins: [ModalMixin, FormMixin, UserAndRoleMixin, ErrorHandlerMixin],
    data() {
        return {
            urlGenerator,
            JOB_POINT_EMAIL_SETUP_SETTING,
            isMailSettingExist: true,
            formData: {},
            roles: [],
            role: '',
            roleLists: [],
            hasData: false,
            modalTitle: this.$t('invite_users'),
        }
    },
    created() {
        this.checkMailSettings();
        if (!_.isEmpty(this.userAndRoles.rowData)) {
            this.hasData = true;
            this.modalTitle = this.$t('manage_role');
            this.formData.email = this.userAndRoles.rowData.email;
            this.roles = this.userAndRoles.rowData.roles.map(function (roles) {
                return roles.id;
            });
        }
        this.getRoles();
    },
    methods: {

        submit() {
            this.errorHandle = true;
            this.formData.roles = [this.role];
            this.save(this.formData);
        },

        afterSuccess(res) {
            this.$toastr.s(res.data.message);
            this.reLoadTable();
            this.closeModal();
        },
        getRoles() {
            let url = actions.ROLES;

            this.preloader = this.hasData;

            this.axiosGet(url).then(response => {

                this.roleLists = response.data.data;

            }).catch(({ response }) => {

            }).finally(() => {
                this.preloader = false;
            });
        },
        checkMailSettings() {
            this.preloader = true;
            axiosGet(MAIL_SETUP_CHECK_URL).then(response => {
                this.isMailSettingExist = !!response.data;
            }).finally(() => {
                this.preloader = false;
            });
        },
    }
}
</script>