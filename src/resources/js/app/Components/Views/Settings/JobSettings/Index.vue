<template>
    <div class="content-wrapper">
        <app-breadcrumb
            :page-title="$t('job_settings')"
        />
        <app-tab
            class="job-setting-tab"
            :tabs="tabs"
            :icon="pageIcon"
        />
    </div>
</template>

<script>
export default {
    name: 'JobSetting',
    props: {
        permissions: {}
    },
    data() {
        return {
            pageIcon: 'settings',
            tabs: [
                {
                    name: this.$t('application_form'),
                    title: this.$t('application_form'),
                    component: 'application-form-setting',
                    permission: this.$have('PERMISSION_MANAGE_GLOBAL_APPLICATION_FORM'),
                },
                {
                    name: this.$t('hiring_stage'),
                    title: this.$t('hiring_stage'),
                    component: 'hiring-stage-setting',
                    permission: this.$have('PERMISSION_VIEW_STAGE'),
                    headerButton: this.$have('PERMISSION_CREATE_STAGE') ? {
                        label: this.$addLabel('hiring_stage'),
                        class: 'btn btn-primary mr-primary'
                    } : {}
                },
                {
                    name: this.$t('event_type'),
                    title: this.$t('event_type'),
                    component: 'event-type-setting',
                    permission: this.$have('PERMISSION_VIEW_EVENT_TYPE'),
                    headerButton: this.$have('PERMISSION_CREATE_EVENT_TYPE') ? {
                        label: this.$addLabel('event_type'),
                        class: 'btn btn-primary mr-primary'
                    } : {}
                },
                {
                    name: this.$t('experience_level'),
                    title: this.$t('experience_level'),
                    component: 'experience-level-setting',
                    permission: this.$have('PERMISSION_VIEW_EXPERIENCE_LEVEL'),
                    headerButton: this.$have('PERMISSION_CREATE_EXPERIENCE_LEVEL') ? {
                        label: this.$addLabel('experience_level'),
                        class: 'btn btn-primary mr-primary'
                    } : {}
                },
                {
                    name: this.$t('job_type'),
                    title: this.$t('job_type'),
                    component: 'job-type-setting',
                    permission: this.$have('PERMISSION_VIEW_JOB_TYPE'),
                    headerButton: this.$have('PERMISSION_CREATE_JOB_TYPE') ? {
                        label: this.$addLabel('job_type'),
                        class: 'btn btn-primary mr-primary'
                    } : {}
                },
                {
                    name: this.$t('department'),
                    title: this.$t('department'),
                    component: 'department-setting',
                    permission: this.$have('PERMISSION_VIEW_DEPARTMENT'),
                    headerButton: this.$have('PERMISSION_CREATE_DEPARTMENT') ? {
                        label: this.$addLabel('department'),
                        class: 'btn btn-primary mr-primary'
                    } : {}
                },
                {
                    name: this.$t('location'),
                    title: this.$t('location'),
                    component: 'location-setting',
                    permission: this.$have('PERMISSION_VIEW_COMPANY_LOCATION'),
                    headerButton: this.$have('PERMISSION_CREATE_COMPANY_LOCATION') ? {
                        label: this.$addLabel('location'),
                        class: 'btn btn-primary mr-primary'
                    } : {}
                }
            ]
        }
    }
}
</script>