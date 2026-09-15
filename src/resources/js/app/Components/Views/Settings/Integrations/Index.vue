<template>
    <div class="content-wrapper">
        <app-breadcrumb :page-title="$t('integrations')" :icon="pageIcon"/>
        <app-tab :tabs="tabs" :icon="pageIcon"/>
    </div>
</template>

<script>
export default {
    name: 'Index',
    props: {
        permissions: {}
    },
    data() {
        return {
            pageIcon: 'settings',
            tabs: [
                {
                    name: this.$t('zoom_settings'),
                    title: this.$t('zoom_settings'),
                    component: 'zoom-settings',
                    //permission: this.$have('PERMISSION_MANAGE_GLOBAL_APPLICATION_FORM'),
                    permission: true,
                },
                {
                    name: this.$t('google_search_integration'),
                    title: this.$t('google_search_integration'),
                    component: 'google-search-integration',
                },
            ]
        }
    }
}
</script>