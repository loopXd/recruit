import Vue from 'vue';
import { DatePicker } from "v-calendar";

import VueToastr from "vue-toastr";

Vue.use(VueToastr, {
    /* OverWrite Plugin Options if you need */
    defaultTimeout: 3000,
    defaultPosition: "toast-top-right",
    defaultClassNames: ["vueToaster"]
    //defaultClassNames: ["animate__animated", "animate__slideInRight", "vueToaster"]
});


// Candidate Public Module
Vue.component('candidate-career-page', require('./Components/Views/CareerPage/CandidateCareerPage').default);
Vue.component('candidate-application-form', require('./Components/Views/Candidates/CandidateApplicationForm').default);
Vue.component('candidate-application-form-cus', require('./Components/Views/Candidates/FormApplication/CandidateApplicationForm').default);
Vue.component('candidate-job-post', require('./Components/Views/Dashboard/CandidateJobPost').default);
Vue.component('candidate-email-verification-modal', require('./Components/Views/Candidates/Helpers/CandidateEmailVerificationModal').default);

Vue.component('app-modal', require('../core/components/modal/Modal').default);
Vue.component('app-input', require('../core/components/input/Index').default);
Vue.component('app-icon', require('../core/components/icon/Icon').default);
Vue.component('app-pre-loader', require('../core/components/preloders/Preloader').default);
Vue.component('app-overlay-loader', require('../core/components/preloders/OverlayLoader').default);
Vue.component("app-confirmation-modal", require('../core/components/confirmation-modal/Index').default);
Vue.component("app-submit-button", require('../core/components/buttons/SubmitButton').default);


Vue.component("modal", require('../app/Components/Helpers/Modal/Modal.vue').default);


Vue.component('v-date-picker', DatePicker);