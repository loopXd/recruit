<template>
    <div class="content-wrapper p-0">
        <div class="editor-wrapper">
            <div class="editor-navbar">
                <ul class="nav nav-left">
                    <li class="nav-item">
                        <a :class="{ 'active': activePreview === 'desktop' }" class="nav-link" href="#"
                            @click="toggleResponsivePreview('desktop')">
                            <app-icon class="size-20 mr-2" name="monitor" />
                            {{ $t('desktop') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a :class="{ 'active': activePreview === 'mobile' }" class="nav-link" href="#"
                            @click="toggleResponsivePreview('mobile')">
                            <app-icon class="size-20 mr-2" name="smartphone" />
                            {{ $t('mobile') }}
                        </a>
                    </li>
                </ul>
                <nav class="navbar-expand-md position-relative">
                    <ul id="navbarToggle" class="nav nav-right collapse navbar-collapse">
                        <li class="nav-item mr-md-1">
                            <a class="nav-link change-toggler" href="#" @click.prevent="toggleEditor">
                                <app-icon class="size-20 mr-2 mr-md-0" name="maximize-2" />
                                <span class="d-md-none">{{ $t('toggle_editor') }}</span>
                            </a>
                        </li>
                        <li class="nav-item mr-md-2">
                            <a class="nav-link view-section" href="#" @click.prevent="viewPreview">
                                <app-icon class="size-20 mr-2 mr-md-0" name="eye" />
                                <span class="d-md-none">{{ $t('view_job') }}</span>
                            </a>
                        </li>
                        <li v-if="saveBtn" :class="{ 'mr-md-2': publishBtn }" class="nav-item">
                            <a class="nav-link save-changes" href="#" @click.prevent="saveChanges">
                                {{ $t('save_changes') }}
                            </a>
                        </li>
                        <li v-if="publishBtn" class="nav-item">
                            <a class="nav-link publish-job" href="#" @click.prevent="publishChanges">
                                {{ $t('publish_job') }}
                            </a>
                        </li>
                    </ul>
                    <button aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation"
                        class="navbar-toggler" data-target="#navbarToggle" data-toggle="collapse" type="button">
                        <app-icon name="more-vertical" />
                    </button>
                </nav>
            </div>
            <div class="editor-content">
                <div class="preview-content">
                    <div class="preview">
                        <hero v-if="isShowHero" :cover="coverImageURL" :icon="icon" readonly :data="calculatedData"
                            :activePreview="activePreview" :showHeader="pageBlocks[pageView].header"
                            :body-section="bodySection" :career-page="careerPage" @modal-trigger="handleModalTrigger"
                            @new-row-add="handleNewRowAdd" @item-delete="handleItemDeleteEvent">
                            <template #content>
                                <div v-if="pageBlocks[pageView].header && isShowHero" class="text-center mb-5">
                                    <div class="text-center text-white">
                                        <div>
                                            <button v-if="!title && !editTitle" class="btn btn-primary mb-4"
                                                @click.prevent="editTitle = true">
                                                {{ $addLabel('title') }}
                                            </button>
                                            <h1 v-if="!editTitle"
                                                :style="`font-size: ${titleStyle.fontSize}; font-weight: ${titleStyle.fontWeight}; letter-spacing: ${titleStyle.letterSpacing}; color: ${titleStyle.color};`"
                                                class="mb-4" @click.prevent="editTitle = true">
                                                {{ title }}
                                            </h1>
                                            <div v-else class="time-picker-input mb-4">
                                                <div class="input-group">
                                                    <input v-model="title" class="form-control" type="text" />
                                                    <div class="input-group-append" @click.prevent="editTitle = false">
                                                        <span class="input-group-text">
                                                            <app-icon name="save" />
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <button v-if="!subtitle && !editSubTitle" class="btn btn-primary mb-4"
                                                @click.prevent="editSubTitle = true">
                                                {{ $addLabel('subtitle') }}
                                            </button>
                                            <p v-if="!editSubTitle"
                                                :style="`font-size: ${subTitleStyle.fontSize}; font-weight: ${subTitleStyle.fontWeight}; letter-spacing: ${subTitleStyle.letterSpacing}; color: ${subTitleStyle.color};`"
                                                class="mb-4" @click.prevent="editSubTitle = true">
                                                {{ subtitle }}
                                            </p>
                                            <div v-else class="time-picker-input mb-4">
                                                <div class="input-group">
                                                    <input v-model="subtitle" class="form-control" type="text" />
                                                    <div class="input-group-append"
                                                        @click.prevent="editSubTitle = false">
                                                        <span class="input-group-text">
                                                            <app-icon name="save" />
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </hero>
                        <!-- Keeping this hidden for now -->
                        <template v-if="isShowHero && careerPage">
                            <div class=" d-flex justify-content-center">
                                <Search :class="activePreview === 'desktop' ? 'w-50' : 'w-75'" :readonly="true"
                                    :enable-custom-positioning="Boolean(isShowHero)" v-if="isShowSearch"
                                    v-model="searchTerm" />
                            </div>
                        </template>

                        <!-- preview-container-->
                        <div
                            :class="`preview-container preview-container--desktop ${pageView === 'mobileView' ? 'mt-4' : ''}`">
                            <template v-if="!isShowHero">
                                <div v-if="pageBlocks[pageView].logo"
                                    class="d-flex flex-column align-items-center mb-5">
                                    <img :src="icon" alt="" class="candidate-viewable-icon img-fluid" />
                                </div>
                            </template>

                            <div v-if="pageBlocks[pageView].header && !isShowHero" class="text-center mb-5">
                                <button v-if="!title && !editTitle" class="btn btn-primary mb-4"
                                    @click.prevent="editTitle = true">
                                    {{ $addLabel('title') }}
                                </button>
                                <h1 v-if="!editTitle"
                                    :style="`font-size: ${titleStyle.fontSize}; font-weight: ${titleStyle.fontWeight}; letter-spacing: ${titleStyle.letterSpacing}; color: ${titleStyle.color};`"
                                    class="mb-4" @click.prevent="editTitle = true">
                                    {{ title }}
                                </h1>
                                <div v-else class="time-picker-input mb-4">
                                    <div class="input-group">
                                        <input v-model="title" class="form-control" type="text" />
                                        <div class="input-group-append" @click.prevent="editTitle = false">
                                            <span class="input-group-text">
                                                <app-icon name="save" />
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <button v-if="!subtitle && !editSubTitle" class="btn btn-primary mb-4"
                                    @click.prevent="editSubTitle = true">
                                    {{ $addLabel('subtitle') }}
                                </button>
                                <p v-if="!editSubTitle"
                                    :style="`font-size: ${subTitleStyle.fontSize}; font-weight: ${subTitleStyle.fontWeight}; letter-spacing: ${subTitleStyle.letterSpacing}; color: ${subTitleStyle.color};`"
                                    class="mb-4" @click.prevent="editSubTitle = true">
                                    {{ subtitle }}
                                </p>
                                <div v-else class="time-picker-input mb-4">
                                    <div class="input-group">
                                        <input v-model="subtitle" class="form-control" type="text" />
                                        <div class="input-group-append" @click.prevent="editSubTitle = false">
                                            <span class="input-group-text">
                                                <app-icon name="save" />
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- search input here -->
                                <template v-if="isShowHero || true">
                                    <Search :readonly="true" :enable-custom-positioning="Boolean(isShowHero)"
                                        v-if="isShowSearch" v-model="searchTerm" class="w-75" />
                                </template>

                                <template v-if="!minimumShow">
                                    <p
                                        :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`">
                                        {{ details }}
                                    </p>

                                    <button v-if="!vacancyCount && !editVacancyCount" class="btn btn-primary mb-4"
                                        @click.prevent="editVacancyCount = true">
                                        {{ $addLabel('details') }}
                                    </button>
                                    <p v-if="!editVacancyCount"
                                        :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`"
                                        @click.prevent="editVacancyCount = true">
                                        <span v-if="vacancyCount">{{ $t('vacancy') }} - {{ vacancyCount }}</span>
                                    </p>
                                    <div v-else class="time-picker-input">
                                        <div class="input-group">
                                            <input v-model="vacancyCount" class="form-control" type="text" />
                                            <div class="input-group-append" @click.prevent="editVacancyCount = false">
                                                <span class="input-group-text">
                                                    <app-icon name="save" />
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                            </div>

                            <template v-if="!careerPage">
                                <div v-if="pageBlocks[pageView].body" class="editor-body">
                                    <div class="editor-group-action mb-2">
                                        <button v-if="editBody" class="btn" type="button" @click.prevent="addMoreRow">
                                            <app-icon name="plus" />
                                        </button>
                                        <button class="btn" type="button" @click.prevent="editBodyTrigger">
                                            <app-icon name="edit" />
                                        </button>
                                        <button class="btn" type="button" @click.prevent="editDone">
                                            <app-icon name="check" />
                                        </button>
                                    </div>

                                    <template v-if="!editBody">
                                        <div v-for="(section, index) in bodySection" :key='`section-${index}`'
                                            class="mb-5">
                                            <h5
                                                :style="`font-size: ${headingsStyle.fontSize}; font-weight: ${headingsStyle.fontWeight}; letter-spacing: ${headingsStyle.letterSpacing}; color: ${headingsStyle.color};`">
                                                {{ section.headings }}
                                            </h5>
                                            <div :style="`font-size: ${descriptionStyle.fontSize}; font-weight: ${descriptionStyle.fontWeight}; letter-spacing: ${descriptionStyle.letterSpacing}; color: ${descriptionStyle.color};`"
                                                v-html="section.description"></div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div v-for="(section, index) in bodySection"
                                            :key="`section-edit-${index}-${section.id}`" class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <input v-model="section.headings" class="editor-body-input form-control"
                                                    type="text" />
                                                <div class="width-30 height-30 text-white bg-success rounded d-inline-flex align-items-center justify-content-center cursor-pointer"
                                                    @click.prevent="deleteSection(section)">
                                                    <app-icon class="size-14" name="trash-2" />
                                                </div>
                                            </div>
                                            <div class="editor">
                                                <textarea :id="`summernote-${index}`" v-model="section.description"
                                                    class="custom-scrollbar" rows="8">
                                                    {{ section.description }}
                                                </textarea>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <div v-if="isCareer" class="mb-5 job-section-heading">
                                <h5 class="job-section-heading"
                                    :style="`font-size: ${headingsStyle.fontSize}; font-weight: ${headingsStyle.fontWeight}; letter-spacing: ${headingsStyle.letterSpacing}; color: ${headingsStyle.color};`">
                                    <!-- {{ $t('job_openings') }} -->
                                    {{ $t('latest_jobs') }}
                                </h5>
                                <!-- <hr/> -->
                                <div class="job-openings">
                                    <div class="row">
                                        <div v-for="(job, index) in jobList"
                                            :class="{ 'col-md-6 col-xl-4': (activePreview === 'desktop') }"
                                            class="col-12 mb-primary">
                                            <JobOpeningCard :job="job" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a v-else href="#" class="btn btn-primary rounded-sm mb-4 text-white">
                                {{ $t('apply_now') }}
                            </a>
                        </div>

                        <div v-if="pageBlocks[pageView].footer" class="text-center py-4">
                            {{ companyText }}
                        </div>
                    </div>
                </div>

                <!-- right panel -->
                <div class="preview-content-editor custom-scrollbar">
                    <div class="editing-options">
                        <div class="mb-4">
                            <h6 class="mb-3">{{ $t('page_styling') }}</h6>
                            <div id="accordionExample" class="accordion">
                                <div v-for="(item, index) in pageStyle[pageView]" class="accordion-item">
                                    <div id="headingOne" class="accordion-header">
                                        <button :aria-controls="`collapse-${index}`" :data-target="`#collapse-${index}`"
                                            aria-expanded="true" class="btn btn-block text-left" data-toggle="collapse"
                                            type="button">
                                            {{ item.name }}
                                        </button>
                                    </div>
                                    <div :id="`collapse-${index}`" aria-labelledby="headingOne" class="collapse"
                                        data-parent="#accordionExample">
                                        <div class="accordion-content">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label for="titleFontSize">
                                                    Font size
                                                </label>
                                                <input id="titleFontSize" v-model="item.fontSize" class="form-control"
                                                    type="number" />
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label for="titleFontWeight">
                                                    Font weight
                                                </label>
                                                <input id="titleFontWeight" v-model="item.fontWeight"
                                                    class="form-control" type="number" />
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label for="titleLetterSpacing">
                                                    Letter spacing
                                                </label>
                                                <input id="titleLetterSpacing" v-model="item.letterSpacing"
                                                    class="form-control" type="number" />
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label for="titleColor">
                                                    Color
                                                </label>
                                                <input id="titleColor" v-model="item.color" class="form-control"
                                                    type="color" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- hero right panel -->
                                <div v-if="isShowHero && careerPage" class="accordion-item">
                                    <div id="headingHero" class="accordion-header">
                                        <button aria-controls="collapse-hero" aria-expanded="true"
                                            class="btn btn-block text-left" data-target="#collapse-hero"
                                            data-toggle="collapse" type="button">
                                            {{ $t('hero') }}
                                        </button>
                                    </div>
                                    <div id="collapse-hero" aria-labelledby="headingHero" class="collapse"
                                        data-parent="#accordionExample">
                                        <div class="accordion-content">
                                            <div class=" mb-2">
                                                <input id="job_post_cover" class="form-control mb-2"
                                                    style="width: 100%;" type="file" @change="changeCoverImage" />
                                                <button class="btn btn-sm btn-success w-100" @click="uploadCoverImage">
                                                    {{ $t('upload_image') }}
                                                </button>
                                                <p class="text-muted mb-1 pb-0 mt-2">{{ $t('image_dimension_msg') }}</p>
                                                <p class="text-muted mb-0 pb-0 mt-2">{{ $t('size_limit') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h6 class="mb-3">{{ $t('page_blocks') }}</h6>
                            <div class="page-blocks">

                                <div v-if="careerPage" class="block-item">
                                    <div class="d-flex align-items-center">
                                        <label class="custom-control d-inline border-switch mb-0 mr-3 disabled-input">
                                            <input :id="`isShowHero`" v-model="isShowHero"
                                                class="border-switch-control-input" :disabled="true" type="checkbox">
                                            <span class="border-switch-control-indicator"></span>
                                        </label>
                                        <label :for="`isShowHero`" class="text-capitalize mb-0">
                                            {{ $t('hero') }}
                                        </label>
                                    </div>
                                </div>

                                <div v-if="careerPage" class="block-item">
                                    <div class="d-flex align-items-center">
                                        <label class="custom-control d-inline border-switch mb-0 mr-3 disabled-input">
                                            <input :id="`isShowSearch`" v-model="isShowSearch"
                                                class="border-switch-control-input" :disabled="true" type="checkbox">
                                            <span class="border-switch-control-indicator"></span>
                                        </label>
                                        <label :for="`isShowSearch`" class="text-capitalize mb-0">
                                            {{ $t('search') }}
                                        </label>
                                    </div>
                                </div>

                                <div v-for="(key, index) in Object.keys(pageBlocks[pageView])" class="block-item">
                                    <div class="d-flex align-items-center">
                                        <label class="custom-control d-inline border-switch mb-0 mr-3">
                                            <input :id="`headerSwitch-${index}`" v-model="pageBlocks[pageView][key]"
                                                class="border-switch-control-input" type="checkbox">
                                            <span class="border-switch-control-indicator"></span>
                                        </label>
                                        <label :for="`headerSwitch-${index}`" class="text-capitalize mb-0">
                                            {{ key }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <template v-if="showModal">
                <modal modal-id="about-us-modal" :title="itemToShowInModal.headings" :modal-scroll="true"
                    :hideFooter="true" :modal-size="'large'" @closeModal="closeModal">
                    <template slot="body">
                        <div class="editor-body">
                            <div class="mb-2 d-flex justify-content-center">
                                <img :src="editorLogo" alt="" class="icon">
                            </div>

                            <!-- Editor toggle buttons -->
                            <div class="d-flex editor-group-action align-items-center justify-content-end mb-2">
                                <button class="btn text-primary" type="button"
                                    @click.prevent="handleModalEditorToggleBtnClick">
                                    <app-icon class="size-18" name="edit" />
                                </button>
                                <button class="btn text-primary" type="button" @click.prevent="handleModalEditorSave">
                                    <app-icon class="size-18" name="check" />
                                </button>
                            </div>

                            <!-- Content display -->
                            <div class="p-2 p-md-3" v-if="!toggleEditViewInModal">

                                <div v-for="(section, index) in bodySection"
                                    v-show="itemToShowInModal.id.toString() === section.id.toString()"
                                    :key='`section-${index}`' class="mb-5">
                                    <h5
                                        :style="`font-size: ${headingsStyle.fontSize}; font-weight: ${headingsStyle.fontWeight}; letter-spacing: ${headingsStyle.letterSpacing}; color: ${headingsStyle.color};`">
                                        {{ section.headings }}
                                    </h5>
                                    <div :style="`font-size: ${descriptionStyle.fontSize}; font-weight: ${descriptionStyle.fontWeight}; letter-spacing: ${descriptionStyle.letterSpacing}; color: ${descriptionStyle.color};`"
                                        v-html="section.description"></div>
                                </div>
                            </div>

                            <!-- Editor view -->
                            <template v-else>
                                <div v-for="(section, index) in bodySectionBeforeEdit"
                                    v-show="itemToShowInModal.id.toString() === section.id.toString()"
                                    :key="`section-edit-${index}-${section.id}`" class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <input v-model="section.headings" maxlength="18" class="form-control"
                                            type="text" />
                                    </div>
                                    <div class="editor">
                                        <textarea :id="`summernote-${index}`" v-model="section.description"
                                            class="custom-scrollbar" rows="8">
                                            {{ section.description }}
                                        </textarea>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </modal>
            </template>
        </div>
    </div>
</template>

<script>
import { FormMixin } from '../../../../core/mixins/form/FormMixin';
import Hero from './Hero'
import JobOpeningCard from './JobOpeningCard'
import Search from './Search'
import { COVER_IMAGE_UPDATE } from "../../../Config/ApiUrl";
import { axiosPost } from "../../../Helpers/AxiosHelper";

export default {
    name: 'Editor',
    mixins: [FormMixin],
    props: {
        data: {
            type: Object,
        },
        editorLogo: {
            type: String
        },
        editorIcon: {
            type: String
        },
        editorContent: {
            type: Object,
            require: true
        },
        editorJobPostCover: {
            type: String
        },
        editorIsCareer: {
            type: Boolean,
            default: false
        },
        editorApplyLink: {
            type: String
        },
        editorJobList: {
            type: Array
        },
        editorPageStyle: {
            type: Object,
        },
        editorPageBlocks: {
            type: Object,
        },
        saveBtn: {
            type: Boolean,
            default: true
        },
        publishBtn: {
            type: Boolean,
            default: true
        },
        minimumShow: {
            type: Boolean,
            default: false
        },
        careerPage: {
            type: Boolean,
            default: false
        }
    },
    components: { Hero, JobOpeningCard, Search },
    data() {
        return {
            showModal: false,
            itemToShowInModal: null,
            toggleEditViewInModal: false,
            bodySectionBeforeEdit: '',
            activePreview: 'desktop',
            searchTerm: '',
            year: moment(moment.now()).format("YYYY"),

            // Common
            logo: this.editorLogo,
            icon: this.editorIcon,
            title: this.editorContent.title,
            subtitle: this.editorContent.subtitle,
            details: this.editorContent.details,
            bodySection: this.editorContent.bodySection.map(item => ({ id: this.guidGenerator(), ...item })),
            vacancyCount: this.editorContent.vacancy_count,

            // Specific
            isCareer: this.editorIsCareer,
            applyLink: this.editorApplyLink,
            jobList: this.editorJobList,

            // Styles
            pageStyle: this.editorPageStyle,
            pageBlocks: this.editorPageBlocks,

            // Edit
            editJobLogo: false,
            editTitle: true,
            editSubTitle: true,
            editDetails: false,
            editBody: false,
            editVacancyCount: false,
            // isShowHero: this.editorContent.hero,
            isShowHero: true,
            isShowSearch: true,
            coverImage: null,
            coverImageURL: this.editorJobPostCover
        }
    },
    mounted() {
        this.bodySectionBeforeEdit = JSON.parse(JSON.stringify(this.bodySection));
        // let navbarRight = document.querySelector('.navbar-nav-right'),
        //     navbarButtons = navbarRight.querySelectorAll('.nav-item');
        // navbarButtons[0].classList.add('d-none');
        setTimeout(() => {
            document.documentElement.setAttribute('theme', 'light')
        })
    },
    computed: {
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
        companyText() {
            return `${this.$t('copyright_text') + ' ' + this.year + ' ' + this.$t('by') + ' '} ${window.settings?.company_name ? window.settings.company_name : ''}`;
        },
        calculatedData() {
            if (!this?.data?.job_post_settings) return this.data
            let jobPostSettingsParsed = typeof this.data.job_post_settings === 'string' ? JSON.parse(this.data.job_post_settings) : this.data.job_post_settings
            jobPostSettingsParsed = { ...jobPostSettingsParsed, pageStyle: this.pageStyle }
            return {
                ...this.data,
                job_post_settings: JSON.stringify(jobPostSettingsParsed)
            }
        }
    },
    methods: {
        handleModalEditorToggleBtnClick() {
            this.toggleEditViewInModal = true;
            this.$nextTick(() => {
                for (let index = 0; index < this.bodySection.length; index++) {
                    this.initSummerNoteComponent(`summernote-${index}`);
                }
            })
        },
        handleNewRowAdd() {
            this.addMoreRow();
            this.bodySectionBeforeEdit = JSON.parse(JSON.stringify(this.bodySection));
        },
        handleItemDeleteEvent(item) {
            this.bodySection = this.bodySection.filter(section => section.id.toString() !== item.id.toString())
            this.bodySectionBeforeEdit = JSON.parse(JSON.stringify(this.bodySection));
        },
        handleModalEditorSave() {
            this.toggleEditViewInModal = false;
            this.bodySection = JSON.parse(JSON.stringify(this.bodySectionBeforeEdit));
        },
        handleModalTrigger(item) {
            this.itemToShowInModal = item;
            this.showModal = true;
        },
        closeModal() {
            this.$nextTick(() => {
                for (let index = 0; index < this.bodySection.length; index++) {
                    this.initSummerNoteComponent(`summernote-${index}`);
                }
            })
            this.toggleEditViewInModal = false;
            this.showModal = false;
            this.itemToShowInModal = null;
            this.bodySectionBeforeEdit = JSON.parse(JSON.stringify(this.bodySection));
        },
        guidGenerator() {
            let S4 = function () {
                return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
            };
            return (S4() + S4() + "-" + S4() + "-" + S4() + "-" + S4() + "-" + S4() + S4() + S4());
        },
        viewPreview() {
            this.$emit('viewPreview');
        },
        changeSummernote(id) {
            let markupStr = $('#' + id).summernote('code');
        },
        editBodyTrigger() {
            this.editBody = true
            this.$nextTick(() => {
                for (let index = 0; index < this.bodySection.length; index++) {
                    this.initSummerNoteComponent(`summernote-${index}`);
                }
            })
        },
        preparedChangedData() {
            let content = {}, data = {}, outerData = {};
            content.title = this.title;
            content.subtitle = this.subtitle;
            content.details = this.details;
            content.bodySection = this.bodySection;
            content.hero = this.isShowHero;
            content.job_post_cover = this.coverImageURL;
            data.content = content;
            data.pageStyle = this.pageStyle;
            data.pageBlocks = this.pageBlocks;

            outerData.name = this.title;
            outerData.description = this.subtitle;
            // outerData.salary = this.salary;
            outerData.vacancy_count = this.vacancyCount;

            return { job_post_settings: data, ...outerData };
        },
        saveChanges() {
            this.$emit('changed', this.preparedChangedData());
        },
        publishChanges() {
            this.$emit('published', this.preparedChangedData());
        },
        addMoreRow() {
            this.bodySection.push({
                id: this.guidGenerator(),
                headings: 'new heading',
                description: 'sample description',
            })
            this.$nextTick(() => {
                this.initSummerNoteComponent(`summernote-${this.bodySection.length - 1}`);
            })
        },
        deleteSection(section) {
            this.bodySection.splice(this.bodySection.indexOf(section), 1);
            this.editBodyTrigger()
        },
        editDone() {
            this.bodySection = this.bodySection.filter(item => item.headings !== '');
            this.editBody = false
        },
        toggleEditor() {
            $('.editor-content').toggleClass('hide-editor');
        },
        toggleJobLogoUploader() {
            if (!this.editorIsCareer) {
                this.editJobLogo = true;
            }
        },
        toggleResponsivePreview(previewType) {
            let preview = $('.preview-content .preview')
            let previewContainer = $('.preview-container')

            if (previewType === 'desktop') {
                this.activePreview = 'desktop';
                preview.removeClass('mobile-preview');
                preview.addClass('desktop-preview');
                previewContainer.removeClass('preview-container--mobile')
                previewContainer.addClass('preview-container--desktop')
            } else {
                this.activePreview = 'mobile';
                preview.removeClass('desktop-preview');
                preview.addClass('mobile-preview');
                previewContainer.addClass('preview-container--mobile')
                previewContainer.removeClass('preview-container--desktop')
            }
        },
        changeCoverImage(event) {
            let file = event.target.files[0]
            this.coverImage = file
            this.coverImageURL = URL.createObjectURL(file)
        },
        uploadCoverImage() {
            let formData = new FormData()
            formData.append('job_post_cover', this.coverImage)

            axiosPost(COVER_IMAGE_UPDATE, formData).then(res => {
                this.$toastr.s(res.data.message)
                this.$emit('cover-changed', true);
            }).catch(({ response }) => {
                this.$toastr.e(response.data.message);

            })
        },
        initSummerNoteComponent(id) {
            let config = {
                placeholder: 'place holder',
                dialogsInBody: true,
                fontSizes: ['8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48'],
                toolbar: [
                    ['style', ['style']],
                    ['fontsize', ['fontsize', 'height', 'color']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['para', ['ul', 'ol', 'codeview', 'paragraph']],
                    ['fontname', ['fontname']],
                    ['view', ['undo', 'redo']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ]
            };

            let _this = this

            $("#" + id).on('summernote.change', function (we, contents, $editable) {
                (_.debounce(function () {
                    let index = Number(id.split('-').pop())
                    _this.bodySection[index].description = contents
                    _this.bodySectionBeforeEdit[index].description = contents
                }, 500))()
            })

            $("#" + id).summernote(config);
            // $('.note-editable').css('font-size','10');
        },
    },
}
</script>

<style lang="scss">
.editor-body-input {
    width: calc(100% - 35px) !important;
}

.disabled-input {
    opacity: 50% !important;

}
</style>
