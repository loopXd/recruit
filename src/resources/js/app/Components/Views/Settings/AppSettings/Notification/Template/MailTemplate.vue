<template>
    <form ref="form" v-if="loaded">
        <div class="position-relative" :class="{'loading-opacity': preloader}">
            <app-overlay-loader v-if="preloader"/>
            <div class="form-group">
                <label class="d-block">{{ $t('mail_subject') }}</label>
                <span
                        class="text-muted font-size-90">{{
                        $t('this_field_will_be_used_as_the_email_subject_and_identify_the_template')
                    }}</span>

                <app-input v-model="template.subject"
                           :required="true"/>
            </div>
            <div class="form-group">
                <label>{{ $t('contents') }}</label>
                <app-input
                        type="text-editor"
                        v-model="template.content"
                        v-if="loaded"
                        :required="true"
                        :text-editor-hints="textEditorHints(Object.keys(tags))"
                        row="5"
                        id="text-editor-for-template"/>
            </div>

            <div class="form-group text-center">
                <button
                        type="button"
                        class="btn btn-sm btn-primary px-3 py-1 margin-left-2 mt-2"
                        data-toggle="tooltip"
                        data-placement="top"
                        v-for="tag in all_tags"
                        :title="tag.description"
                        @click="addTag(tag.tag)"
                >{{ tag.tag }}
                </button>
            </div>
        </div>
    </form>
    <div v-else>
        <app-pre-loader/>
    </div>
</template>

<script>
import {TemplateMixins} from './Mixins/TemplateMixins';
import {textEditorHints} from '../../../../../../Helpers/Helpers';
import {FormMixin} from '../../../../../../../core/mixins/form/FormMixin';

export default {
    name: 'MailTemplate',
    mixins: [FormMixin, TemplateMixins],
    data() {
        return {
            preloader: false,
            tags: {
                '{name}': this.$t('the_resource_name_of_the_event'),
                '{action_by}': this.$t('the_profile_who_performed_the_action'),
                '{app_name}': this.$t('name_of_the_application'),
                '{app_logo}': this.$t('logo_of_the_application'),
                '{receiver_name}': this.$t('the_profile_who_will_receive_the_notification'),
                '{resource_url}': this.$t('page_link_of_resource'),
                '{invitation_url}': this.$t('invitation_url_for_the_profile'),
                '{reset_password_url}': this.$t('reset_password_url_of_profile'),
                '{job_post}': this.$t('job_post'),
                '{event_type}': this.$t('medium_in_which_event_will_be_held'),
                '{description}': this.$t('event_is_about'),
                '{location}': this.$t('location_of_event'),
                '{start_at}': this.$t('starting_time'),
                '{end_at}': this.$t('ending_time'),
                '{noted_by}': this.$t('the_person_who_created_note'),
                '{candidate_name}': this.$t('the_person_who_applied'),
                '{disqualification_reason}': this.$t('the_reason_for_candidate_disqualification'),
                '{event_time}': this.$t('scheduled_time_of_the_event'),
                '{event_location}': this.$t('scheduled_location_of_the_event'),
                '{zoom_start_url}': this.$t('zoom_meeting_start_url'),
                '{zoom_meeting_id}': this.$t('zoom_meeting_id'),
                '{topic}': this.$t('zoom_meeting_topic'),
                '{duration}': this.$t('zoom_meeting_duration'),
                '{job_post_card}': this.$t('job_post_card'),
                '{career_page_link}': this.$t('the_url_to_the_career_page'),
            },
            textEditorHints,

            user_invitation_tags: ['{app_logo}', '{action_by}', '{app_name}', '{receiver_name}', '{invitation_url}'],
            password_reset_tags: ['{app_logo}', '{app_name}', '{receiver_name}', '{reset_password_url}'],
            user_joined_tags: ['{app_logo}', '{resource_url}', '{app_name}', '{receiver_name}', '{name}'],
            roles_created_tags: ['{app_logo}', '{action_by}', '{app_name}', '{receiver_name}', '{name}', '{resource_url}'],
            roles_updated_tags: ['{app_logo}', '{action_by}', '{app_name}', '{receiver_name}', '{name}', '{resource_url}'],
            roles_deleted_tags: ['{app_logo}', '{action_by}', '{app_name}', '{receiver_name}'],
            candidate_disqualified_tags: ['{app_logo}', '{app_name}', '{candidate_name}', '{job_post}', '{receiver_name}', '{action_by}', '{disqualification_reason}'],
            disqualification_mail_for_candidate_tags: ['{app_logo}', '{app_name}', '{candidate_name}', '{job_post}'],
            event_created_tags: ['{event_type}', '{receiver_name}', '{action_by}', '{candidate_name}', '{job_post}', '{start_at}', '{end_at}', '{event_time}', '{event_location}', '{app_name}', '{app_logo}', '{zoom_start_url}', '{zoom_meeting_id}', '{topic}', '{duration}'],
            create_event_mail_for_candidate_tags: ['{app_logo}', '{app_name}', '{candidate_name}', '{job_post}', '{event_type}', '{description}', '{location}', '{start_at}', '{end_at}', '{zoom_start_url}', '{zoom_meeting_id}', '{topic}', '{duration}'],
            note_created_tags: ['{app_logo}', '{app_name}', '{receiver_name}', '{job_post}', '{candidate_name}', '{noted_by}'],
            job_applied_tags: ['{app_logo}', '{app_name}', '{receiver_name}', '{job_post}', '{candidate_name}'],
            job_apply_response_for_candidate_tags: ['{app_logo}', '{app_name}', '{job_post}', '{candidate_name}'],
            job_alert: ['{app_logo}', '{app_name}', '{candidate_name}', '{job_post_card}', '{career_page_link}'],
        }
    },
    mounted() {
        this.preloader = true;
        setTimeout(() => {
            this.preloader = false;
        })
    },
    watch: {
        'notificationSettings.rowData': {
            handler: 'setType',
            immediate: true
        },
        'template': {
            handler: 'setTemplateObj',
            immediate: true,
        }
    },

    computed: {
        all_tags() {

            const tags = Object.keys(this.tags).filter(tag => {

                if ('user_invitation' === this.notificationEventName) {
                    return this.user_invitation_tags.includes(tag)
                } else if ('password_reset' === this.notificationEventName) {
                    return this.password_reset_tags.includes(tag)
                } else if ('user_joined' === this.notificationEventName) {
                    return this.user_joined_tags.includes(tag)
                } else if ('roles_created' === this.notificationEventName) {
                    return this.roles_created_tags.includes(tag)
                } else if ('roles_updated' === this.notificationEventName) {
                    return this.roles_updated_tags.includes(tag)
                } else if ('roles_deleted' === this.notificationEventName) {
                    return this.roles_deleted_tags.includes(tag)
                } else if ('event_created' === this.notificationEventName) {
                    return this.event_created_tags.includes(tag)
                } else if ('disqualification_mail_for_candidate' === this.notificationEventName) {
                    return this.disqualification_mail_for_candidate_tags.includes(tag)
                } else if ('create_event_mail_for_candidate' === this.notificationEventName) {
                    return this.create_event_mail_for_candidate_tags.includes(tag)
                } else if ('note_created' === this.notificationEventName) {
                    return this.note_created_tags.includes(tag)
                } else if ('job_applied' === this.notificationEventName) {
                    return this.job_applied_tags.includes(tag)
                } else if ('job_apply_response_for_candidate' === this.notificationEventName) {
                    return this.job_apply_response_for_candidate_tags.includes(tag)
                } else if ('job_alert' === this.notificationEventName) {
                    return this.job_alert.includes(tag)
                } else if ('candidate_disqualified' === this.notificationEventName) {
                    return this.candidate_disqualified_tags.includes(tag)
                } else {
                    return !['{reset_password_url}', '{invitation_url}'].includes(tag)
                }
            });

            return tags.map(tag => {
                return {tag, description: this.tags[tag]}
            })
        }
    },
    methods: {
        setType() {
            this.setTemplate('mail')
        },
        addTag(tag_name = '{name}') {
            $("#text-editor-for-template").summernote('editor.saveRange');
            $("#text-editor-for-template").summernote('editor.restoreRange');
            $("#text-editor-for-template").summernote('editor.focus');
            if (tag_name == '{app_logo}') $("#text-editor-for-template").summernote('pasteHTML',
                '<img src="{app_logo}" style="height: 75px"/>');
            else $("#text-editor-for-template").summernote('editor.insertText', tag_name);
        }
    },
}
</script>
<style scoped>
.margin-left-2 {
    margin-left: 4px;
}

.margin-left-2:first-child {
    margin-left: 0;
}
</style>