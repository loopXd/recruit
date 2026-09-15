<template>
    <div class="p-primary">
        <FileInput
            endpoint="app/google-ownership-file-update"
            :input-id="'uploader-1'"
            :muted-text="$t('file_must_be_html')"
            :button-text="$t('upload')"
            :label-text="$t('upload_google_ownership_file')"/>

        <FileInput
            endpoint="app/google-service-account-file-update"
            :input-id="'uploader-2'"
            :muted-text="$t('file_must_be_json')"
            :button-text="$t('upload')"
            :label-text="$t('google_service_account_credential')"/>
    </div>
</template>

<script>

import FileInput from "./FileInput";

export default {
    name: "GoogleSearchIntegration",
    components: {
        FileInput
    },
}
</script>
