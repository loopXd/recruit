<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <app-breadcrumb :page-title="$t('user_and_roles')" :icon="'user-check'" />
            </div>
            <div class="col-sm-12 col-md-6 breadcrumb-side-button">
                <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
                    <button v-if="$have('PERMISSION_INVITE_USER')" type="button"
                        class="btn btn-success btn-with-shadow mr-2" data-toggle="modal"
                        @click="operationForUserInvitation">
                        {{ $t('invite_users') }}
                    </button>
                    <button v-if="$have('PERMISSION_CREATE_ROLES')" type="button"
                        class="btn btn-primary btn-with-shadow" data-toggle="modal" @click="operateRoles(true)">
                        {{ $t('add_role') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5 mb-4 mb-lg-0 mb-xl-0">
                <user :data="userAndRoles.users" @action="getActionUser" />
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-7">
                <role :data="userAndRoles.roles" @action="getActionRole" />
            </div>
        </div>

        <user-invitation-modal v-if="userAndRoles.users.isInviteModalActive" @closeModal="closeInviteModal" />

        <user-modal v-if="userAndRoles.users.isUserModalActive" @closeModal="closeUserModal" />

        <manage-users-modal v-if="userAndRoles.users.isManageUsersModalActive" @closeModal="closeManageUserModal" />

        <roles-add-edit-modal v-if="userAndRoles.roles.isAddEditModalActive" :selected-url="selectedUrl"
            :manage="manage" @closeModal="closeModalRoleAddEditModal" />

        <app-delete-modal v-if="confirmation.isActive" :preloader="deleteLoader" :modal-id="confirmation.modalId"
            @confirmed="confirmed" @cancelled="cancelled" />

        <app-confirmation-modal v-if="isUserCancelInvitationModalActive"
            :message="$t('you_are_going_to_cancel_an_invitation')" :modal-id="confirmation.modalId"
            @confirmed="cancelUserInvitation('user-table', 'role-table')"
            @cancelled="isUserCancelInvitationModalActive = false" :self-close="false" />
    </div>
</template>

<script>
import AppLibrary from "../../../Helpers/AppLibrary";

import UserModal from "./Users/UserModal";
import UserInvitationModal from "./Users/UserInvitationModal";
import ManageUsersModal from "./Users/ManageUsersModal";
import User from "./Users/Index";

import RolesAddEditModal from "./Roles/RolesAddEditModal";
import Role from "./Roles/Index";

import { UserAndRoleMixin } from './Mixins/UserAndRoleMixin';

import * as actions from '../../../Config/ApiUrl';

export default {
    name: "UserAndRoles",
    extends: AppLibrary,
    mixins: [UserAndRoleMixin],
    components: {
        UserModal,
        UserInvitationModal,
        RolesAddEditModal,
        User,
        ManageUsersModal,
        Role
    },
    data() {
        return {
            deleteLoader: false,
            rowData: {},
            selectedUrl: '',
            manage: false,
            confirmation: {
                modalId: 'confirmation-Modal',
                isActive: false,
                url: '',
                tableId: '',
            },
            isUserCancelInvitationModalActive: false,
        }
    },
    created() {
        this.$store.dispatch('getPermissions');
    },
    methods: {
        getActionUser(rowData, actionObj, active) {

            this.$store.dispatch('setRowData', rowData);

            if (actionObj.title === this.$t('manage_role')) {

                this.selectedUrl = `${actions.INVITE_USER}/${rowData.id}`;
                this.operationForUserInvitation();

            } else if (actionObj.title === this.$t('delete')) {

                this.confirmation.url = `${actions.USERS}/${rowData.id}`;
                this.confirmation.tableId = this.userAndRoles.users.tableId;
                this.openConfirmationModal();

            } else if (actionObj.title === this.$t('cancel_invitation')) {
                this.confirmation.url = `${actions.CANCEL_INVITATION}/${rowData.id}`;
                this.isUserCancelInvitationModalActive = true;
            } else if (actionObj.title === this.$t('edit')) {
                this.selectedUrl = `${actions.UPDATE_USER_NAME}/${rowData.id}`;
                this.openUserModal();

            } else if (actionObj.title === this.$t('active')) {

                this.changeUserStatus(1, rowData.id);

            } else if (actionObj.title === this.$t('de_activate')) {

                this.changeUserStatus(2, rowData.id);
            }
        },

        /* *
         * $EMIT FROM ROLE TABLE
         * */
        getActionRole(rowData, actionObj, active) {
            this.$store.dispatch('setRowData', rowData);
            this.manage = false;
            if (actionObj.title === this.$t('edit')) {

                this.selectedUrl = `${actions.ROLES}/${rowData.id}`;
                this.operateRoles(true);

            } else if (actionObj.title === this.$t('delete')) {

                this.confirmation.url = `${actions.ROLES}/${rowData.id}`;
                this.confirmation.tableId = this.userAndRoles.roles.tableId;
                this.openConfirmationModal();

            } else if (actionObj.title === this.$t('manage_users')) {
                this.operationForManageUser();
            } else if (actionObj.title === this.$t('permission')) {
                this.manage = true,
                    this.selectedUrl = `${actions.ROLES}/${rowData.id}`;
                this.operateRoles(true, 'manage_permission')
            }
        },

        /* *
         * OPERATION FOR ROLES
         * */
        operateRoles(isActive, title = 'role') {
            const obj = {
                isActive: isActive,
                title: title
            };
            this.$store.dispatch('operateRoles', obj);
        },

        /* *
         * CLOSE ROLE ADD EDIT MODAL
         * */
        closeModalRoleAddEditModal() {
            $('#' + this.userAndRoles.roles.addEditModalId).modal('hide');
            this.operateRoles(false);
            this.resetData();
        },

        /* *
         * OPEN MANAGE USER MODAL
         * */
        operationForManageUser(isActive = true) {
            this.$store.dispatch('operationForManageUser', isActive);
        },

        closeManageUserModal() {
            $('#' + this.userAndRoles.users.manageUserModalId).modal('hide');
            this.operationForManageUser(false);
            this.resetData();
        },

        /* *
         * OPEN INVITE MODAL
         * */
        operationForUserInvitation(isActive = true) {
            this.$store.dispatch('operationForUserInvitation', isActive);
        },

        /**
         * Open user modal
         * */
        openUserModal(isActive = true) {
            this.$store.dispatch('openUserModal', isActive)
        },
        /**
         * Close user modal
         * */
        closeUserModal() {
            $('#' + this.userAndRoles.users.userModalId).modal('hide');
            this.openUserModal(false);
            this.resetData();
        },

        /**
         * CLOSE INVITE MODAL
         * */
        closeInviteModal() {
            $('#' + this.userAndRoles.users.inviteModalId).modal('hide');
            this.operationForUserInvitation(false);
            this.resetData();
        },

        /**
         * Change user status
         * */
        changeUserStatus(status, id) {
            let url = `${actions.USERS_LIST}/${id}`,
                reqData = this.userAndRoles.rowData;

            reqData.status_id = status;

            this.axiosPatch({
                url: url,
                data: reqData
            }).then(res => {
                this.$toastr.s(res.data.message);
                this.reLoadTable();

            }).catch(error => {
                let { message } = error.response.data;
                this.$toastr.e(message);
            }).finally(() => {
                this.resetData();
            });
        },

        /* *
         * OPEN CONFIRMATION MODAL
         * */
        openConfirmationModal() {
            this.confirmation.isActive = true;
        },

        /* *
         * AFTER CONFIRMED FROM CONFIRMATION MODAL
         * */
        confirmed() {
            let url = this.confirmation.url;
            this.deleteLoader = true;
            this.axiosDelete(url)
                .then(res => {
                    this.$toastr.s(res.data.message);
                }).catch(error => {
                    let { message } = error.response.data;
                    this.$toastr.e(message);
                }).finally(() => {
                    this.deleteLoader = false;
                    $("#" + this.confirmation.modalId).modal('hide');
                    this.cancelled();
                    this.reLoadTable();
                });

        },
        cancelUserInvitation() {
            this.deleteLoader = true;
            this.axiosDelete(this.confirmation.url)
                .then(res => {
                    this.$toastr.s(res.data.message);
                }).catch(error => {
                    let { message } = error.response.data;
                    this.$toastr.e(message);
                }).finally(() => {
                    this.deleteLoader = false;
                    $("#" + this.confirmation.modalId).modal('hide');
                    this.isUserCancelInvitationModalActive = false;
                    this.cancelled();
                    this.reLoadTable();
                });
        },

        /* *
         * AFTER CANCELLED FROM CONFIRMATION MODAL
         * */
        cancelled() {
            this.confirmation.isActive = false;
            this.resetData();
        },

        /* *
         * RESET DATA
         * */
        resetData() {
            this.$store.dispatch('setRowData', {});
            this.selectedUrl = '';
        },

    }
}
</script>
