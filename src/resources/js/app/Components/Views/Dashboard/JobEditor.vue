<template>
    <div>
        <app-overlay-loader v-if="preloader || loader" />

        <app-editor :class="preloader || loader ? 'loading-opacity' : ''"
            :editor-content="{ ...jobSetting.content, vacancy_count: jobSetting.vacancy_count }"
            :editor-logo="logo"
            :editor-icon="icon"
            :editor-page-style="jobSetting.pageStyle"
            :editor-page-blocks="jobSetting.pageBlocks"
            :publish-btn="publishBtn"
            :data="job"
            @viewPreview="goToPreview"
            @changed="saveChangedData"
            @published="publishData"
        />
    </div>
</template>

<script>
import {JOB_POST} from "../../../Config/ApiUrl";
import {axiosPatch, urlGenerator} from "../../../Helpers/AxiosHelper";
import AppFunction from "../../../../core/helpers/app/AppFunction";
import {mapGetters} from "vuex";

export default {
    name: 'JobEditor',
    props: {
        job: {},
        previewLink: {}
    },
    data() {
        return {
            preloader: false,
            modified: false,
            modifiedData: null,
            logo: urlGenerator(window.settings.company_logo),
            icon: urlGenerator(window.settings.company_icon),
        }
    },
    computed: {
        ...mapGetters(['statusListForJobPost', 'loader']),
        publishBtn() {
            if(!this.$have('PERMISSION_CHANGE_JOB_POST_STATUS')) return false;
            let draftId = Number(this.statusListForJobPost.find(item => item.name === 'status_draft').id),
                editJobStatusId = Number(this.job.status_id);
            return draftId === editJobStatusId;
        },
        jobSetting() {
            return {...this.modified ? this.modifiedData.job_post_settings : (typeof this.job['job_post_settings'] === 'string' ?
                JSON.parse(this.job['job_post_settings']) : this.job['job_post_settings']), vacancy_count: this.job.vacancy_count};
        }
    },
    methods: {
        changeAndReload(url, data, callback) {
            this.preloader = true;
            let form = data;
            axiosPatch(url, form).then(res => {
                this.$toastr.s(res.data.message)
                if(callback) callback();
                this.preloader = false;
                this.modified = true;
                this.modifiedData = _.cloneDeep(data);
            }).catch(({response}) => {
                this.$toastr.e(response.data.message)
                location.reload();
            })
        },
        saveChangedData(data) {
            let url = `${JOB_POST}/${this.job.id}/update-job-post`;
            this.changeAndReload(url, data);
        },
        publishData(data) {
            let url = `${JOB_POST}/${this.job.id}/publish-job-post`;
            this.changeAndReload(url, data, ()=> location.reload());
        },
        goToPreview() {
            window.open(this.previewLink);
        }
    },
    created() {
        this.$store.dispatch('getStatuses');
    }
}
</script>