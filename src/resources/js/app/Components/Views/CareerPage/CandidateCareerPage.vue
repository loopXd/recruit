<template>
    <div class="content-wrapper p-0">
        <div v-if="!checkCondition(careerPage)" class="hero position-relative pt-5">
            <div class="hero__item--content container">
                <div class="d-flex justify-content-end mb-3">
                    <a :href="urlGenerator(loggedIn ? 'admin/dashboard' : 'admin/users/login')"
                        class="btn btn-primary">{{ $t(loggedIn ? 'dashboard' : 'login') }}</a>
                </div>
            </div>
        </div>
        <hero ref="hero" v-else :is-candidate="isCandidate" :cover="jobPostCover" :icon="icon" :logged-in="loggedIn" :logo="logo"
                :pageSettingContent="careerPage.job_post_settings.content.bodySection" :body-section="careerPage.job_post_settings.content.bodySection" :active-preview="activePreview">
            <template #content v-slot:activator="{ on, attrs }">
                <div v-if="pageBlocks[pageView].header" class="--text-center mb-5">
                    <h1 :style="`font-size: ${titleStyle.fontSize}; font-weight: ${titleStyle.fontWeight}; letter-spacing: ${titleStyle.letterSpacing}; color: ${titleStyle.color};`"
                        class="mb-4">
                        {{ content.title }}
                    </h1>

                    <p :style="`font-size: ${subTitleStyle.fontSize}; font-weight: ${subTitleStyle.fontWeight}; letter-spacing: ${subTitleStyle.letterSpacing}; color: ${subTitleStyle.color};`"
                        class="mb-4">
                        {{ content.subtitle }}
                    </p>
                </div>
            </template>
        </hero>

        <div class="editor-content">
            <div class="preview-content career-page" style=" padding-top: 0 !important;">
                <div class="preview container">
                    <template
                        v-if="!(careerPage && careerPage.job_post_settings && careerPage.job_post_settings.content && careerPage.job_post_settings.content.hero)">
                        <div v-if="pageBlocks[pageView].logo" class="d-flex flex-column align-items-center mb-5">
                            <img :src="icon" alt="" class="candidate-viewable-icon img-fluid" />
                        </div>
                        <div v-if="pageBlocks[pageView].header" class="text-center mb-5">
                            <h1 :style="`font-size: ${titleStyle.fontSize}; font-weight: ${titleStyle.fontWeight}; letter-spacing: ${titleStyle.letterSpacing}; color: ${titleStyle.color};`"
                                class="mb-4">
                                {{ content.title }}
                            </h1>

                            <p :style="`font-size: ${subTitleStyle.fontSize}; font-weight: ${subTitleStyle.fontWeight}; letter-spacing: ${subTitleStyle.letterSpacing}; color: ${subTitleStyle.color};`"
                                class="mb-4">
                                {{ content.subtitle }}
                            </p>
                        </div>
                    </template>

                    <div class=" d-flex justify-content-center ">
                        <Search
                            class="w-75 w-md-50"
                            :readonly="false" 
                            @enterpress="handleEnterPress"
                            @search-term-clear="fetchJobs"
                        />
                    </div>

                    <div id="jobs" class="mt-5">
                        <h5 :style="`font-size: ${headingsStyle.fontSize}; font-weight: ${headingsStyle.fontWeight}; letter-spacing: ${headingsStyle.letterSpacing}; color: ${headingsStyle.color};`"
                            class="job-section-heading">
                            <template v-if="!jobsLoading">
                                {{ $t('latest_jobs') }}
                                <span class="text-muted" :style="`font-size: ${headingsStyle.fontSize * .8};`">
                                    ({{ totalJobs }})
                                </span>
                            </template>
                        </h5>

                        <!-- <hr/> -->
                        <div class="job-openings">
                            <app-pre-loader v-if="jobsLoading" />
                            <div class="row" v-else>
                                <div v-for="(job, index) in jobList"
                                    :class="{ 'col-md-6 col-xl-4': (activePreview === 'desktop') }"
                                    class="col-12 mb-primary">
                                    <JobOpeningCard :job="job" />
                                </div>
                                <div class="col-12" v-if="newJobsLoading">
                                    <app-pre-loader />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="pageBlocks[pageView].footer" class="text-center py-4">
                        {{ companyText }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { axiosGet, urlGenerator } from "../../../Helpers/AxiosHelper";
import { PUBLIC_JOB_POST, JOB_LIST } from "../../../Config/ApiUrl";
import Hero from "../../Helpers/Editor/Hero";
import JobOpeningCard from "../../Helpers/Editor/JobOpeningCard";
import Search from "../../Helpers/Editor/Search";
import { textTruncate } from "../../../Helpers/TextHelper";
import HeroDropdown from "./HeroDropdown.vue";
import { TENANT_BASE_URL } from "../../../Config/UrlHelper";

export default {
    name: 'CandidateCareerPage',
    components: { Hero, JobOpeningCard, Search, HeroDropdown },
    props: {
        careerPage: {},
        // jobPosts: {},
        loggedIn: {
            type: Boolean,
            default: false
        },
        isCandidate: {
            type: Boolean,
            required: true
        }
    },
    filters: {
        truncate(value) {
            const limit = 40;
            if (value.length < limit) return value;
            return value.substring(0, limit) + '...'
        }
    },
    data() {
        return {
            urlGenerator,
            searchTerm: '',
            textTruncate,
            viewType: 'desktop',
            activePreview: 'desktop',
            showAboutUsModal: false,
            year: moment(moment.now()).format("YYYY"),
            jobsLoading: false,
            jobPosts: [],
            nextPageUrl: '',
            totalJobs: null,
            newJobsLoading: false
        }
    },
    computed: {
        jobList() {
            return this.jobPosts.map(item => {
                return {
                    ...item,
                    title: item.name,
                    type: item['job_type'].name,
                    location: item['location'].address,
                    department: item['department'] ? item['department'].name : '',
                    url: urlGenerator(`${TENANT_BASE_URL}/${item.slug}`),
                }
            })
            // .filter(item =>
            //     item.title.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
            //     item.type.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
            //     item.salary.toString().toLowerCase().includes(this.searchTerm.toLowerCase()) ||
            //     item.location.toLowerCase().includes(this.searchTerm.toLowerCase())
            // );
        },
        careerPageData() {
            let data = (typeof this.careerPage === 'string') ? JSON.parse(this.careerPage) : this.careerPage;
            return data.job_post_settings ? data.job_post_settings : data;
        },
        content() {
            return this.careerPageData.content;
        },
        pageStyle() {
            return this.careerPageData.pageStyle;
        },
        pageBlocks() {
            if (this.careerPageData.pageBlocks) return this.careerPageData.pageBlocks;
            return null;
        },
        pageView() {
            return this.activePreview === 'desktop' ? 'defaultView' : 'mobileView'
        },
        titleStyle() {
            return this.pageStyle[this.pageView].find(item => item.key === 'title')
        },
        subTitleStyle() {
            return this.pageStyle[this.pageView].find(item => item.key === 'sub-title')
        },
        detailsStyle() {
            return this.pageStyle[this.pageView].find(item => item.key === 'details')
        },
        headingsStyle() {
            return this.pageStyle[this.pageView].find(item => item.key === 'headings')
        },
        descriptionStyle() {
            return this.pageStyle[this.pageView].find(item => item.key === 'description')
        },
        icon() {
            return this.careerPageData.icon ? this.careerPageData.icon : urlGenerator(window.settings.company_icon)
        },
        logo() {
            return urlGenerator(window.settings.company_logo);
        },
        jobPostCover() {
            return urlGenerator(window.settings.job_post_cover);
        },
        companyText() {
            return `${this.$t('copyright_text') + ' ' + this.year + ' ' + this.$t('by') + ' '} ${window.settings?.company_name ? window.settings.company_name : ''}`;
        }
    },
    watch: {
        viewType: {
            handler: function (type) {
                this.toggleResponsivePreview(type)
            },
            immediate: true
        }
    },
    mounted() {
        this.checkViewType();
        window.onresize = () => {
            this.checkViewType();
        }
        this.fetchJobs()
        document.body.addEventListener('scroll', this.handleScroll)
    },
    unmounted() {
        document.body.removeEventListener('scroll', this.handleScroll)
    },
    methods: {
        fetchJobs() {
            this.jobsLoading = true
            axiosGet(JOB_LIST + '?per_page=' + 10)
                .then(response => {
                    this.jobPosts = response?.data?.data 
                    this.nextPageUrl = response.data.next_page_url
                    this.totalJobs = response?.data?.total
                })
                .catch(e => console.dir(e))
                .finally(() => this.jobsLoading = false)
        },
        searchJobs(searchTerm) {
            this.jobsLoading = true
            axiosGet(JOB_LIST + '?search=' + searchTerm)
                .then(response => {
                    this.jobPosts = response?.data?.data 
                    this.totalJobs = response?.data?.total
                })
                .catch(e => console.dir(e))
                .finally(() => this.jobsLoading = false)
        },
        fetchNewJobs() {
            this.newJobsLoading = true
            axiosGet(this.nextPageUrl + '&per_page=' + 10)
                .then(response => {
                    this.jobPosts = [...this.jobPosts, ...response?.data?.data]
                    this.nextPageUrl = response?.data?.next_page_url;
                })
                .catch(e => console.dir(e))
                .finally(e => this.newJobsLoading = false)
        },
        toggleModal() {
            this.$refs.hero.toggleModal();
        },
        handleReadMoreBtnClick() {
            this.showAboutUsModal = true;
        },
        checkCondition(careerPage) {
            return careerPage?.job_post_settings?.content?.hero;
        },
        checkViewType() {
            this.viewType = window.innerWidth > 575 ? 'desktop' : 'mobile';
        },
        toggleResponsivePreview(previewType) {
            let preview = $('.preview-content .preview')

            if (previewType === 'desktop') {
                this.activePreview = 'desktop';
                preview.removeClass('mobile-preview');
                preview.addClass('desktop-preview');
            } else {
                this.activePreview = 'mobile';
                preview.removeClass('desktop-preview');
                preview.addClass('mobile-preview');
            }
        },
        handleScroll(e) {
            const box = e.target;
            const scrollableHeight = box.scrollHeight - box.clientHeight

            if (box.scrollTop >= scrollableHeight) return this.handleScrollToBottom()
        },
        handleEnterPress(searchTerm) {
            if (!searchTerm) return this.fetchJobs()
            this.searchJobs(searchTerm);
        },
        handleScrollToBottom() {
            if (!this.nextPageUrl) return;
            this.fetchNewJobs();
        }
    }
}
</script>

<style scoped lang="scss">
.hero {
    padding-bottom: 5rem;
}

.font-20 {
    font-size: 20px;
}

.editor-content {
    margin-top: -4rem;
}

.search-wraper- {}
</style>
