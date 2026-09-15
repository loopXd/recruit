<template>
    <form ref="form" v-if="loaded">
        <div class="form-group" v-if="template">
            <label>{{ $t('contents') }}</label>
            <app-input v-model="template.content"
                       id="database-template-title"
                       :required="true"/>
        </div>
        <div class="form-group text-center">
            <button
                    type="button"
                    class="btn btn-sm btn-primary px-3 py-1 margin-left-2 mt-2"
                    data-toggle="tooltip"
                    data-placement="top"
                    v-for="tag in Object.keys(all_tags)"
                    @click="insertAtCaret('database-template-title', tag)"
                    :title="tags[tag]"
            >{{ tag }}
            </button>
        </div>
    </form>
    <div v-else>
        <app-pre-loader/>
    </div>
</template>

<script>
import {FormMixin} from '../../../../../../../core/mixins/form/FormMixin';
import {TemplateMixins} from './Mixins/TemplateMixins';

export default {
    data() {
        return {
            loaded: false,
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
            },
            user_joined_tags: ['{app_name}'],
            roles_created_tags: ['{name}', '{action_by}'],
            roles_updated_tags: ['{name}', '{action_by}'],
            roles_deleted_tags: ['{name}', '{action_by}'],
            candidate_disqualified_tags: ['{candidate_name}', '{action_by}', '{job_post}'],
            event_created_tags: ['{event_type}', '{candidate_name}', '{start_at}', '{end_at}'],
            note_created_tags: ['{noted_by}', '{job_post}', '{candidate_name}'],
            job_applied_tags: ['{candidate_name}', '{job_post}'],
        }
    },
    name: "DatabaseTemplate",
    mixins: [FormMixin, TemplateMixins],

    computed: {
        all_tags() {

            const allTags = Object.keys(this.tags).filter(tag => {

                if ('user_joined' === this.notificationEventName) {
                    return this.user_joined_tags.includes(tag)
                } else if ('roles_created' === this.notificationEventName) {
                    return this.roles_created_tags.includes(tag)
                } else if ('roles_updated' === this.notificationEventName) {
                    return this.roles_updated_tags.includes(tag)
                } else if ('roles_deleted' === this.notificationEventName) {
                    return this.roles_deleted_tags.includes(tag)
                } else if ('event_created' === this.notificationEventName) {
                    return this.event_created_tags.includes(tag)
                } else if ('note_created' === this.notificationEventName) {
                    return this.note_created_tags.includes(tag)
                } else if ('job_applied' === this.notificationEventName) {
                    return this.job_applied_tags.includes(tag)
                } else if ('candidate_disqualified' === this.notificationEventName) {
                    return this.candidate_disqualified_tags.includes(tag)
                } else {
                    return !['{reset_password_url}', '{invitation_url}'].includes(tag)
                }
            });

            let availableTags = {}

            allTags.forEach(tag => {
                availableTags[tag] = this.tags[tag]
            })

            return availableTags;

        }
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
    methods: {
        setType() {
            this.setTemplate('database')
        },
        insertAtCaret(areaId, text) {
            let txtarea = document.getElementById(areaId);
            if (!txtarea) {
                return;
            }

            let scrollPos = txtarea.scrollTop;
            let strPos = 0;
            let br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
                "ff" : (document.selection ? "ie" : false));
            if (br == "ie") {
                txtarea.focus();
                let range = document.selection.createRange();
                range.moveStart('character', -txtarea.value.length);
                strPos = range.text.length;
            } else if (br == "ff") {
                strPos = txtarea.selectionStart;
            }

            let front = (txtarea.value).substring(0, strPos);
            let back = (txtarea.value).substring(strPos, txtarea.value.length);
            txtarea.value = front + text + back;
            strPos = strPos + text.length;
            if (br == "ie") {
                txtarea.focus();
                let ieRange = document.selection.createRange();
                ieRange.moveStart('character', -txtarea.value.length);
                ieRange.moveStart('character', strPos);
                ieRange.moveEnd('character', 0);
                ieRange.select();
            } else if (br == "ff") {
                txtarea.selectionStart = strPos;
                txtarea.selectionEnd = strPos;
                txtarea.focus();
            }

            txtarea.scrollTop = scrollPos;
        }
    }
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
