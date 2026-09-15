<template>
    <div class="content-wrapper">
        <div class="editor-content">
            <div class="preview-content" style="padding: 2rem 14rem;">
                <div class="preview">
                    <div class="mb-5 cursor-pointer">
                        <a class="text-primary lead align-self-start" @click="redirectHomePage">
                            <app-icon name="chevron-left" />
                            {{ $t('back') }}
                        </a>
                    </div>
                    <div v-if="pageBlocks[pageView].logo" class="d-flex flex-column align-items-center mb-5">
                        <img :src="icon"
                             alt=""
                             class="candidate-viewable-icon img-fluid"
                        />
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
                        <DetailsCard
                            :content="content"
                            :detailsStyle="detailsStyle"
                            :data="data"
                            :activePreview="activePreview"
                        />
                    </div>

                    <div v-if="pageBlocks[pageView].body" class="editor-body">
                        <div v-for="(section,index) in content.bodySection"
                             class="mb-5">
                            <h5 :style="`font-size: ${headingsStyle.fontSize}; font-weight: ${headingsStyle.fontWeight}; letter-spacing: ${headingsStyle.letterSpacing}; color: ${headingsStyle.color};`">
                                {{ section.headings }}
                            </h5>
                            <p :style="`font-size: ${descriptionStyle.fontSize}; font-weight: ${descriptionStyle.fontWeight}; letter-spacing: ${descriptionStyle.letterSpacing}; color: ${descriptionStyle.color};`"
                               v-html="purify(decode(section.description))">
                            </p>
                        </div>
                    </div>

                    <a :href="applyLink" class="btn btn-primary rounded-sm mb-4 text-white">
                            {{ $t('apply_now') }}
                    </a>

                    <div v-if="pageBlocks[pageView].logo" class="text-center py-4">
                        <img :src="logo" alt="" class="candidate-viewable-logo img-fluid d-block mx-auto">
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
import {urlGenerator} from "../../../Helpers/AxiosHelper";
import DetailsCard from './DetailsCard';
import {decode} from 'html-entities';
import {purify} from '../../Helpers/HTMLPurifyHelper.js'

export default {
    name: "CandidateJobPost",
    props: ['data', 'applyLink'],
    components: {
        DetailsCard
    },
    data() {
        return {
            year: moment(moment.now()).format("YYYY"),
            viewType: 'desktop',
            activePreview: 'desktop',
            decode,
            purify
        }
    },
    computed: {
        jobPostSetting() {
            if (typeof this.data['job_post_settings'] === 'string')
                return JSON.parse(this.data['job_post_settings']);
            return this.data['job_post_settings'];
        },
        content() {
            return this.jobPostSetting.content;
        },
        pageStyle() {
            return this.jobPostSetting.pageStyle;
        },
        pageBlocks() {
            return this.jobPostSetting.pageBlocks;
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
            return this.jobPostSetting.icon ? this.jobPostSetting.icon : urlGenerator(window.settings.company_icon)
        },
        logo() {
            return urlGenerator(window.settings.company_logo);
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
    },
    methods: {
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
        redirectHomePage() {
            window.location.replace(urlGenerator('/'));
        },
    }
}
</script>

