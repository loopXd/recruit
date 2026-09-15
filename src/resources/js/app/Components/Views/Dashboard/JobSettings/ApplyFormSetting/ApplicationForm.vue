<template>
    <div class="p-primary">
        <app-overlay-loader v-if="preloader"/>
        <form-gen v-else :form-data-response="applyForm" :preview="true" @viewForm="viewForm" @submit="updateSetting" :key="key" />
    </div>
</template>

<script>

import {mapGetters} from "vuex";
import {JOB_POST, PUBLIC_JOB_POST} from "../../../../../Config/ApiUrl";
import {axiosPatch, urlGenerator} from "../../../../../Helpers/AxiosHelper";
import {TENANT_BASE_URL} from "../../../../../Config/UrlHelper";
import FormGen from "../../../../Helpers/ApplyForm/formGen/FormGen.vue";

export default {
    name: 'ApplicationForm',
    components: { FormGen },
    data() {
        return {
            axiosPatch,
            applyForm: [],
            preloader: true,
            key: 0
        }
    },
    computed: {
        ...mapGetters([
            'applicationForm',
            'selectedJobDetails',
            'loader'
        ])
    },
    watch: {
        selectedJobDetails: {
            handler: function (data) {
                this.applyForm = data.apply_form_settings ?
                    (typeof data.apply_form_settings === 'string' ? JSON.parse(data.apply_form_settings) : data.apply_form_settings)
                    : this.applicationForm;
                    this.key++

                this.preloader = false;
            }
        }
    },
    methods: {
        updateSetting(data) {
            this.preloader = true;
            let url = `${JOB_POST}/${this.selectedJobDetails.id}/edit-apply-form-setting`,
                form = {apply_form_settings: data}
            axiosPatch(url, form).then(res => {
                this.$toastr.s(res.data.message);
                this.$store.dispatch('getJobDetails', this.selectedJobDetails.id);
            }).finally(() => {
                this.preloader = false;
            })
        },
        viewForm() {
            window.open(urlGenerator(`${TENANT_BASE_URL}/${this.selectedJobDetails.slug}/apply`));
        }
    },
    mounted(){
        if(this.selectedJobDetails){
            let data = this.selectedJobDetails;
            this.applyForm = data.apply_form_settings ?
                    (typeof data.apply_form_settings === 'string' ? JSON.parse(data.apply_form_settings) : data.apply_form_settings)
                    : this.applicationForm;
                    this.key++
            this.preloader = false
        }
    }
}
</script>
