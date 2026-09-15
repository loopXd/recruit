<template>
    <div :class="readonly ? 'px-5' : ''" :style="{ 'backgroundImage': `url('${cover}')`}"
        class="hero position-relative py-5">
        <div class="hero__item--content container">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class=""><img :src="logo" alt="" class="icon"></div>
                <div class="d-flex align-content-center align-items-center">
                    <a v-for="item in pageSettingContent" class="mr-2 py-3 p-1" href="javascript:void(0);" @click="toggleModal(item)">
                        {{ item.headings }}
                    </a>
                    <HeroDropdown :loggedIn="loggedIn" />
                    <div class="d-none align-items-center justify-content-between d-sm-flex">
                        <div class="d-flex align-items-center" style="gap: 1rem;">
                            <template v-if="careerPage">
                                <template v-if="pageView === 'defaultView'">
                                    <div v-for="item in bodySection" class="item-heading-wrapper">
                                        <a data-v-1c7dffde="" href="javascript:void(0);">
                                            {{ item.headings }}
                                            <app-icon
                                                name="more-vertical"
                                                class="size-10"
                                            />
                                            <div class="cus-dropdown-wrapper">
                                                <ul class="list-unstyled border bg-white cus-dropdown d-flex flex-column"> <!-- the bg-white class is subject to change -->
                                                    <li class="px-3"  @click="editItem(item)">
                                                        <app-icon class="size-15" name="edit" />
                                                        <span>{{ $t('edit') }}</span>
                                                    </li>
                                                    <li class="px-3 text-danger" @click="deleteItem(item)">
                                                        <app-icon class="size-15" name="x" />
                                                        <span>{{ $t('delete') }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>

                                    <button v-if="careerPage" class="btn btn-primary" :disabled="this.bodySection.length >= 4" style="padding: 0.5rem;" @click="handleAddNewSectionClick">
                                        <app-icon name="plus" class="size-15" />
                                    </button>
                                </template>
                                <!-- mobile view -->
                                <template v-else>
                                    <div class="nav-item dropdown">
                                        <a href="#"
                                            id="languageDropdown"
                                            class="d-flex align-items-center nav-link dropdown-toggle"
                                            data-toggle="dropdown"
                                            aria-expanded="false">
                                            <app-icon name="menu" />
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown language-dropdown"
                                            aria-labelledby="languageDropdown">
                                            <a class="dropdown-item py-3" v-for="item in bodySection" :key="index" @click.stop.prevent="">
                                                <div class="d-flex align-items-center d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-0 font-weight-normal mr-4">{{ item.headings }}</h6>
                                                    <ul class="list-unstyled cus-dropdown d-flex mb-0" style="gap: 0.75rem;">
                                                        <li class="text-primary cursor-pointer" @click.stop.prevent="editItem(item)">
                                                            <app-icon class="size-15" name="edit" />
                                                        </li>
                                                        <li class="text-danger cursor-pointer" @click.stop.prevent="deleteItem(item)">
                                                            <app-icon class="size-15" name="x" />
                                                        </li>
                                                    </ul>
                                                </div>
                                            </a>
                                            <a class="dropdown-item py-3 cursor-pointer" v-if="!(this.bodySection.length >= 4)" @click.stop.prevent="handleAddNewSectionClick">
                                                <div class="d-flex align-items-center d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-0 font-weight-normal mr-4">{{ $t('add_new_section') }}</h6>
                                                    <ul class="list-unstyled cus-dropdown d-flex mb-0" style="gap: 0.75rem;">
                                                        <li></li>
                                                        <li class="text-primary cursor-pointer">
                                                            <app-icon class="size-15" name="plus" />
                                                        </li>
                                                    </ul>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </template>
                            </template>

                            <a :href="urlGenerator(loggedIn ? 'admin/dashboard' : 'admin/users/login')"
                                class="btn btn-primary">{{ $t(loggedIn ? 'dashboard' : 'login') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <slot name="content" ></slot>
            <template v-if="Boolean(jobPostSetting)">
                <DetailsCard v-if="showHeader" :data="data" :content="content" :detailsStyle="detailsStyle"
                    :activePreview="activePreview" />
            </template>
        </div>
        <template v-if="showModal && pageSettingContent.length">
            <modal modal-id="about-us-modal" :title="itemToShow.headings" :modal-scroll="true" :hideFooter="true"
                modal-size="large" @closeModal="closeModal">
                <template slot="body">
                    <div class="p-2 p-md-3">
                        <div class="mb-2 d-flex justify-content-center"><img  :src="logo" alt="" class="icon"></div>
                        <div class="my-3" v-for="(item, index) in pageSettingContent" v-show="itemToShow.id === item.id">
                            <h5>{{ item.headings }}</h5>
                            <div v-html="item.description"></div>
                        </div>
                    </div>
                </template>
            </modal>
        </template>
    </div>
</template>

<script>

import { urlGenerator } from "../../../Helpers/AxiosHelper";
import HeroDropdown from "../../Views/CareerPage/HeroDropdown.vue";
import DetailsCard from "../../Views/Dashboard/DetailsCard";
import {v4} from "uuid";

export default {
    components: {
        HeroDropdown,
        DetailsCard
    },
    props: {
        icon: {
            type: String,
        },
        data: {
            type: Object,
        },
        showHeader: {
            type: Boolean,
            default: false
        },
        activePreview: {
            type: String,
            // required: true
        },
        logo: {
            type: String,
        },
        readonly: {
            type: Boolean,
        },
        cover: {
            type: String,
            default: ''
        },
        loggedIn: {
            type: Boolean,
            default: false
        },
        pageSettingContent: {
            type: Array,
            default: () => []
        },
        bodySection: {
            type: Array,
            default: () => []
        },
        careerPage: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            urlGenerator,
            searchText: '',
            showModal: false,
            itemToShow: ''
        }
    },
    methods: {
        search(event) {
            console.log(event)
        },
        editItem(item) {
            this.$emit('modal-trigger', item)
        },
        deleteItem(item) {
            this.$emit('item-delete', item)
        },
        init() {
            let content = document.querySelector('.hero__item--content')
            let cover = document.querySelector('.hero__item--cover')
            cover.style.height = content.clientHeight + 60
        },
        closeModal() {
            this.showModal = false
            this.itemToShow = '';
        },

        toggleModal(item) {
            this.itemToShow = item;
            this.showModal = true
        },
        handleAddNewSectionClick() {
            this.$emit('new-row-add');
        }
    },
    computed: {
        jobPostSetting() {
            if (!Boolean(this.data)) return null;
            if (typeof this.data['job_post_settings'] === 'string')
                return JSON.parse(this.data['job_post_settings']);
            return this.data['job_post_settings'];
        },
        content() {
            if (!this.jobPostSetting) return null;
            return this.jobPostSetting.content;
        },
        pageStyle() {
            if (!this.jobPostSetting) return null;
            return this.jobPostSetting.pageStyle;
        },
        pageView() {
            return this.activePreview === 'desktop' ? 'defaultView' : 'mobileView'
        },
        detailsStyle() {
            if (this.pageStyle) return this.pageStyle[this.pageView].find(item => item.key === 'details')
        },
    },
}
</script>

<style lang="scss" scoped>
.icon {
    max-width: 140px;
    width: 100%;
}

.search {
    display: flex;
    justify-content: center;
    align-items: center;

    input {
        border: 1px solid #fff;
        background-color: #ffffffdd;
        border-radius: 25px;
        max-width: 300px;
        width: 100%;
        padding: 5px 15px;
    }
}

.hero {
    //height: 300px;
    //height: 100%;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;

    &__item {
        &--content {
            position: relative;
        }
    }
}

.about-us-btn a {
    text-decoration: none !important;
    color: var(--default-font-color);

    &:hover {
        color: #4466F2;
    }
}

.item-heading-wrapper {
    position: relative;


    &:hover {
        .cus-dropdown-wrapper {
            opacity: 1;
            pointer-events: auto;
        }
    }
} 

.cus-dropdown-wrapper {
    position: absolute;
    width: 10rem;
    opacity: 0;
    z-index: 2;
    pointer-events: none;
    border-radius: 0.25rem;

    li {
        padding: 1rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    li:hover {
        background-color: #F0F2F5;
    }
}
</style>
