<template>
    <div class="min-height-250">
        <app-overlay-loader v-if="preloader"/>
        <div>
            <div class="d-flex align-items-center gap-2">
                <app-avatar title="sample title"
                            avatar-class="avatars-w-40 align-self-start"
                            :img="''"
                            alt-text="image" style="opacity: 0;"/>
                <app-note
                        v-if="!imapEnabled"
                        class="mb-4 w-100"
                        title="info"
                        :show-title="false"
                        content-type="html"
                        :notes="`
                     ${$t('to_get_candidate_conversation_please_set_up_imap_settings',{
                      imap_setting: `<a href='${urlGenerator(IMAP_SETTING)}' target='_blank'>${$t('imap_setting')}</a>`
                  })}`"
                />
            </div>
        </div>

        <template v-if="Boolean(!conversationData.length)">
            <div>
                <div class="d-flex align-items-center gap-2 py-2">
                    <app-avatar title="sample title"
                                avatar-class="avatars-w-40 align-self-start"
                                :img="''"
                                alt-text="image" style="opacity: 0;"/>
                    <div class="rounded-lg w-100">
                        <app-overlay-loader v-if="isSubmitted"/>
                        <form>
                            <input class="d-none" ref="attachments" type="file" @change="change" multiple>
                        </form>
                        <div v-if="isClicked">
                            <div>
                                <table class="w-100 mb-4">
                                    <tr>
                                        <td style="width: 10%" class="">{{ $t('from') }}:</td>
                                        <td class="p-1">
                                            {{ user.full_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> {{ $t('to') }}:</td>
                                        <td class="p-1">
                                            {{ applicant.full_name }}
                                            <small v-if="applicant.email">({{ applicant.email }})</small>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <hr v-if="isClicked">
                        <div v-if="isClicked">
                            <table class="w-100 mb-4">
                                <tr>
                                    <td style="width: 10%" class="">{{ $t('subject') }}:</td>
                                    <app-input
                                            :type="'text'"
                                            :placeholder="$t('write_a_message_here')"
                                            v-model="formData.subject"
                                            :required="true"
                                            :error-message="$errorMessage(errors, 'subject')"
                                    />
                                </tr>
                            </table>
                        </div>

                        <hr v-if="isClicked">
                        <div v-if="!isCandidate" class="">
                            <div class="form-group">
                                <app-input
                                        :type="isClicked ? 'text-editor': 'text'"
                                        :placeholder="$t('write_a_message_here')"
                                        v-model="formData.text_html"
                                        :required="true"
                                        :error-message="$errorMessage(errors, 'text_html')"
                                        @click="!isClicked ? isClicked = true: ''"
                                />
                            </div>
                        </div>
                        <div v-if="isClicked">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex" style="gap: 10px;">
                                    <button
                                            type="button" class="fab fab-brand fab-transparent fab-3 my-1"
                                            @click="addAttachment">
                                        <app-icon name="paperclip" :size="18"/>
                                    </button>
                                    <div class="d-flex flex-wrap">
                                        <div v-for="(attachment, index) in formData.attachments"
                                             :key="`chat-item-attachment-${index}`"
                                             class="d-inline-block align-items-center d-flex px-1 ml-1 mb-1 rounded-pill"
                                             style="background: rgba(205, 245, 212, 0.1)">
                                            <small class="m-2">{{ attachment.name }}</small>
                                            <button
                                                    type="button" class="fab fab-red fab-transparent fab-2"
                                                    @click="removeAttachments(index)">
                                                <app-icon name="x" :size="16"/>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex" style="gap: 10px;">
                                    <button
                                            type="button" class="btn btn-light" @click="discard">{{ $t('discard') }}
                                    </button>
                                    <button
                                            type="button" class="btn btn-primary" @click="submit">
                                        {{ $t('send') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <div v-for="(item, index) in conversationData">
            <div class="d-flex align-items-center gap-2 py-2">
                <app-avatar :title="item.from"
                            avatar-class="avatars-w-40 align-self-start"
                            :img="''"
                            alt-text="image"/>
                <div class="w-100">
                    <b class="font-weight-bold"> {{ item.from }} </b>
                    <span class="text-muted">{{ $t('to') }}</span> <span class="font-weight-bold">{{ item.to }}</span>
                    <small v-if="item.to_email" class="text-muted">({{ item.to_email }})</small><br>
                    <span class="ml-1 text-muted">{{ formatDateTimeDateTime(item.date) }}</span>
                    <div
                            :class="`mt-3 border rounded ${!darkMode ? item.sender === 'user' ? 'light-mode-col1' : 'light-mode-col2' : item.sender === 'user' ? 'dark-mode-col1' : 'dark-mode-col2'}`">
                        <div :class="`rounded-lg chat-body`" v-html="item.text_html"></div>
                        <div class="d-flex flex-wrap attachment-pills">
                            <div v-for="(attachment, index) in item.attachments" :key="`chat-item-attachment-${index}`"
                                 class="d-inline-block align-items-center d-flex p-1 px-2 mr-2 rounded-pill file-name-pill"
                            >
                                <a target="#" download :href="attachment.full_url"
                                   style="text-decoration: none; color: inherit;">
                                    <app-icon name="paperclip" class="size-12" style="color: inherit;"/>
                                    <small class="m-2">{{ getName(attachment.full_url, item.sender) }}</small>
                                </a>
                            </div>
                        </div>
                    </div>
                    <template v-if="index === conversationData.length - 1">
                        <div :class="`rounded-lg ${isSubmitted && 'loading-opacity'}`"
                             style="position: relative; margin-top: 2rem;">
                            <app-overlay-loader v-if="isSubmitted"/>
                            <form>
                                <input class="d-none" ref="attachments" type="file" @change="change" multiple>
                            </form>
                            <div v-if="isClicked">
                                <div>
                                    <table class="w-100 mb-4">
                                        <tr>
                                            <td style="width: 10%" class="">{{ $t('from') }}:</td>
                                            <td class="p-1">
                                                {{ user.full_name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> {{ $t('to') }}:</td>
                                            <td class="p-1">
                                                {{ applicant.full_name }}
                                                <small v-if="applicant.email">({{ applicant.email }})</small>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr v-if="isClicked">
                            <div v-if="isClicked">
                                <table class="w-100 mb-4">
                                    <tr>
                                        <td style="width: 10%" class="">{{ $t('subject') }}:</td>
                                        <app-input
                                                :type="'text'"
                                                :placeholder="$t('write_a_message_here')"
                                                v-model="formData.subject"
                                                :required="true"
                                                :error-message="$errorMessage(errors, 'subject')"
                                        />
                                    </tr>
                                </table>
                            </div>

                            <hr v-if="isClicked">
                            <div class="">
                                <div class="form-group">
                                    <app-input
                                            :type="isClicked ? 'text-editor': 'text'"
                                            :placeholder="$t('write_a_message_here')"
                                            v-model="formData.text_html"
                                            :required="true"
                                            :error-message="$errorMessage(errors, 'text_html')"
                                            @click="trigger"
                                            :autofocus="true"
                                    />
                                </div>
                            </div>
                            <div v-if="isClicked">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex" style="gap: 10px;">
                                        <button
                                                type="button" class="fab fab-brand fab-transparent fab-3 my-1"
                                                @click="addAttachment">
                                            <app-icon name="paperclip" :size="18"/>
                                        </button>
                                        <div class="d-flex flex-wrap">
                                            <div v-for="(attachment, index) in formData.attachments"
                                                 :key="`chat-item-attachment-${index}`"
                                                 class="d-inline-block align-items-center d-flex px-1 ml-1 mb-1 rounded-pill"
                                                 style="background: rgba(205, 245, 212, 0.1)">
                                                <small class="m-2">{{ attachment.name }}</small>
                                                <button
                                                        type="button" class="fab fab-red fab-transparent fab-2"
                                                        @click="removeAttachments(index)">
                                                    <app-icon name="x" :size="16"/>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex" style="gap: 10px;">
                                        <button
                                                type="button" class="btn btn-light" @click="discard">{{ $t('discard') }}
                                        </button>
                                        <button
                                                type="button" class="btn btn-primary" @click="submit">
                                            {{ $t('send') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {axiosGet, axiosPost, urlGenerator} from "../../../../Helpers/AxiosHelper";
import {IMAP_SETTING, JOB_APPLICANT} from "../../../../Config/ApiUrl";
import {formatDateTimeDateTime, getDateFromNow} from "../../../../Helpers/DateTimeHelper";
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";

export default {
    name: "CandidateConversation",
    mixins: [FormMixin],
    props: {
        jobApplicantId: {},
        applicant: {},
        user: {},
        imapEnabled: {},
        isCandidate: false

    },
    data() {
        return {
            getDateFromNow,
            formatDateTimeDateTime,
            urlGenerator,
            IMAP_SETTING,
            errors: {},
            conversationData: [],
            lightModeActive: true,
            preloader: false,
            isSubmitted: false,
            formData: {
                subject: '',
                text_html: '',
                user_id: 1,
                attachments: []
            },
            maxFileSize: (5 * 1024 * 1024),
            imageExt: ['jpeg', 'jpg', 'png', 'webp', 'gif', 'pdf', 'doc', 'docx', 'zip'],
            isClicked: false,

        }
    },
    created() {
        this.getConversation();
    },
    computed: {
        darkMode() {
            return this.$store.state.theme.darkMode;
        }
    },
    mounted() {
        this.$hub.$on('getSelectedJobApplicantTimelineData', () => {
            this.getConversation();
        })
    },
    methods: {
        trigger() {
            this.isClicked = !this.isClicked
            setTimeout(() => {
                this.scrollTop()
            }, 500)
        },
        scrollTop() {
            let elementRoot = document.querySelector('.candidate-details-wrapper')
            let main = document.querySelector('.modal-body')
            main.scrollTo(0, elementRoot.clientHeight)
        },
        submit() {
            let formData = new FormData();

            for (let key in this.formData) {
                if (key === 'attachments') {
                    for (const attachment of this.formData[key]) {
                        formData.append('attachments[]', attachment);
                    }
                } else {
                    formData.append(key, this.formData[key])
                }
            }
            this.submitFromFixin('post', `app/job-applicant/${this.jobApplicantId}/sent-reply`, formData);
            if (this.isValidForm()) {
                this.isSubmitted = true;
            }
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.formData = {};
            this.isClicked = false;
            this.isSubmitted = false;
            this.getConversation()
        },
        afterError(response) {
            this.isClicked = false;
            this.isSubmitted = false;
            this.$toastr.e(response.data.message);
        },
        getName(params, sender) {
            let arr = params.split('/')
            if (sender !== 'applicant') return arr[arr.length - 1];
            let name = arr[arr.length - 1].split('_')
            name.shift()
            name = name.join('_')
            return name;
        },
        discard() {
            this.formData.text_html = '';
            this.isClicked = false
        },
        change(event) {
            let files = event.target.files
            for (let file of files) {
                if (file.size < this.maxFileSize) {
                    this.formData.attachments.push(file)
                } else {
                    this.$toastr.e(this.$t('file_size_exceeded'))
                }
            }
        },
        addAttachment() {
            let attachments = this.$refs.attachments
            if (this.conversationData.length) return attachments[0].click();
            attachments.click();
        },
        removeAttachments(index) {
            this.formData.attachments.splice(index, 1);
        },
        getConversation() {
            this.preloader = true;
            let url = `${JOB_APPLICANT}/${this.jobApplicantId}/get-conversation`;
            axiosGet(url).then(res => {
                this.conversationData = res.data.data.map(i => {
                    i.text_html = i.text_html.split('<br><div class="gmail_quote">')[0]
                    return i
                });
                this.formData.subject = this.conversationData.length > 0 ? `Re: ${this.applicant.full_name} (${this.applicant.email})` : '';
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
            }).finally(() => {
                this.preloader = false;
                this.scrollTop()
            })
        }
    },
}
</script>

<style scoped lang="scss">
.gap-2 {
  gap: 10px;
}

.chat-body {
  min-height: 3rem;
  padding: 1rem;
}

.attachment-pills {
  padding: 1rem;
}

$foreground-color: #7a7a7a;
.file-name-pill {
  color: $foreground-color !important;
  border: 1px solid;
  border-radius: 5px !important;
}

.file-name-pill:hover > a {
  color: #4466f2 !important;
}

.dark-mode-col1 {
  background-color: #14181b;
}

//interview team
.dark-mode-col2 {
  background-color: #3b3b3b;
}

//candidate

//.light-mode-col1 {background-color: rgb(236 254 255);}
.light-mode-col1 {
  background-color: #f9f9f9
}

//interview team
.light-mode-col2 {
  background-color: #F3F9FF;
}

//candidate

.bg-cyan-50 {
  background-color: rgb(236 254 255);
}
</style>
