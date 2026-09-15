import Vue from 'vue';

Vue.component('app-editor', require('./Editor/Editor').default);
Vue.component('modal', require('./Modal/Modal').default);
Vue.component('email-verification-modal', require('./Modal/EmailVerificationModal').default);
Vue.component('app-delete-modal', require('./Modal/DeleteModal').default);
Vue.component('app-custom-field-modal', require('./Modal/CustomFieldModal').default);
Vue.component('app-table-media', require('./TableMedia').default);
Vue.component('star-rating', require('./StarRating').default);
Vue.component('app-note-editor', require('./Note/AppNoteEditor').default)
Vue.component('event-view-modal', require('./Modal/EventViewModal').default)
