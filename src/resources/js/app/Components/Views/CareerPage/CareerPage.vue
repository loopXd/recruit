<template>

    <app-editor
        v-if="!preloader && $have('PERMISSION_VIEW_CAREER_PAGE')"
        :editor-content="pageData.content"
        :editor-logo    ="urlGenerator(logo)"
        :editor-icon="urlGenerator(icon)"
        :editor-job-post-cover="urlGenerator(job_post_cover)"
        :editor-is-career="isCareer"
        :editor-job-list="jobList"
        :editor-page-style="pageData.pageStyle"
        :editor-page-blocks="pageData.pageBlocks"
        :publish-btn="false"
        :save-btn="$have('PERMISSION_UPDATE_CAREER_PAGE')"
        @viewPreview="goToPreview"
        @changed="saveChangedData"
        @cover-changed="handleCoverChange"
        minimumShow
        :career-page="true"
    />

    <div v-else class="content-wrapper">
        <div class="card border-0 shadow min-height-350">
            <div class="card-body">
                <app-overlay-loader/>
            </div>
        </div>
    </div>

</template>

<script>
import {axiosPatch, urlGenerator} from "../../../Helpers/AxiosHelper";
import {CAREER_PAGE, PUBLIC_CAREER_PAGE, PUBLIC_JOB_POST} from "../../../Config/ApiUrl";
import {TENANT_BASE_URL} from "../../../Config/UrlHelper";

export default {
    name: 'CareerPage',
    props: {
        careerPage: {},
        jobPosts: {}
    },
    data() {
        return {
            urlGenerator,
            preloader: false,
            modified: false,
            modifiedData: null,
            logo: window.settings.company_logo,
            icon: window.settings.company_icon,
            job_post_cover: window.settings.job_post_cover,
            isCareer: true,
            coverChanged: false
        }
    },
    computed: {
        pageData() {
            let data = this.modified ? this.modifiedData : (typeof this.careerPage === 'string' ?
                JSON.parse(this.careerPage) : this.careerPage);
            return data.job_post_settings ? data.job_post_settings : data;
        },
        jobList() {
            return this.jobPosts.map(item => {
                return {
                    title: item.name,
                    type: item['job_type'].name,
                    location: item['location'].address,
                    working_arrangement: item.working_arrangement,
                    department: item.department,
                    salary: item.salary,
                    vacancy_count: item.vacancy_count,
                    last_submission_date: item.last_submission_date,
                    url: urlGenerator(`${TENANT_BASE_URL}/${item.slug}`)
                }
            })
        }
    },
    methods: {
        handleCoverChange(state) {
            this.coverChanged = state;
        },
        changeAndReload(url, data) {
            this.preloader = true;
            let form = {
                career_page: data
            }
            axiosPatch(url, form).then(res => {
                this.$toastr.s(res.data.message)
                if (this.coverChanged) return location.reload();
                this.preloader = false;
                this.modified = true;
                this.modifiedData = _.cloneDeep(data);
            }).catch(({response}) => {
                this.$toastr.e(response.data.message)
                location.reload();
            })
        },
        saveChangedData(data) {
            let url = `${CAREER_PAGE}`;
            this.changeAndReload(url, data);
        },
        goToPreview() {
            window.open(urlGenerator(PUBLIC_CAREER_PAGE));
        }
    }
}
</script>

