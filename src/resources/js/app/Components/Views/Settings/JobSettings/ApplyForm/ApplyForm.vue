<template>
    <!-- <apply-form-settings
        :form-setting="applicationForm"
        :preloader="preloader"
        :view-button="false"
        @update="updateSetting"
    /> -->
    <div class="p-primary">
        <app-overlay-loader v-if="preloader"/>
        <form-gen v-else :form-data-response="applicationForm" @submit="updateSetting" />
    </div>
</template>

<script>

import CoreLibrary from "../../../../../../core/helpers/CoreLibrary";
import {GLOBAL_APPLICATION_FORM} from "../../../../../Config/ApiUrl";
import FormGen from "../../../../Helpers/ApplyForm/formGen/FormGen.vue";

export default {
    name: 'ApplicationFormSetting',
    extends: CoreLibrary,
    components: { FormGen },
    data() {
        return {
            applicationForm: [],
            preloader: false,
        }
    },
    created() {
        this.getSetting();
    },
    methods: {
        getSetting() {
            this.preloader = true;
            this.axiosGet(GLOBAL_APPLICATION_FORM).then(res => {
                this.applicationForm = res.data?.application_form ? res.data?.application_form : '';
            }).finally(()=> {
                this.preloader = false
            })
        },
        updateSetting(data) {
            this.preloader = true;
            this.axiosPatch({
                url: GLOBAL_APPLICATION_FORM,
                data: {
                    application_form: data
                }
            }).then(res => {
                this.$toastr.s(res.data.message);
                this.getSetting();
            })
        }
    }
}
</script>