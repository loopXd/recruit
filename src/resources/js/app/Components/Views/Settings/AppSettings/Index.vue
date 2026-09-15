<template>
    <div class="content-wrapper">
        <app-breadcrumb :page-title="pageTitle" :icon="pageIcon" />
        <app-tab :tabs="tabs" :icon="pageIcon" />
    </div>
</template>

<script>
export default {
    name: 'SettingsLayout',
    props: {
        permissions: {}
    },

    data() {
        return {
            pageTitle: this.$t('app_settings'),
            pageIcon: 'settings',
            tabs: [
                {
                    name: this.$t('general'),
                    title: this.$t('general'),
                    component: "general-setting",
                    permission: this.permissions.general,
                },
                {
                    name: this.$t('cron_job'),
                    title: this.$t('cron_job'),
                    component: "app-cron-job-settings",
                    props: {},
                    permission: this.$have('PERMISSION_VIEW_CRON_JOB_SETTINGS'),
                },
                {
                    name: this.$t('email_setup'),
                    title: this.$t('email_setup'),
                    component: "email-setting",
                    permission: this.permissions.email_settings,
                },
                {
                    name: this.$t('imap_settings'),
                    title: this.$t('imap_settings'),
                    component: 'imap-settings',
                    permission: this.$have('PERMISSION_VIEW_IMAP_SETTINGS'),
                },
                {
                    "name": this.$t('notification'),
                    "title": this.$t('notification'),
                    "component": "notification-settings",
                    //"permission": this.permissions.notification,
                    permission: this.$have('PERMISSION_VIEW_IMAP_SETTINGS'),
                },
                {
                    "name": this.$t('storage_configuration'),
                    "title": this.$t('storage_configuration'),
                    "component": "storage-setting",
                    permission: this.$have('PERMISSION_VIEW_STORAGE_SETTINGS'),
                }
            ]
        }
    },
}
</script>
