import Vue from 'vue';

// Helper components
import './Components/Helpers/HelperComponent';

// Layouts
Vue.component('app-top-bar', require('./Components/Views/Layouts/Nabar').default);
Vue.component('app-sidebar', require('./Components/Views/Layouts/Sidebar').default);

// Auth
Vue.component('login', require('./Components/Views/Auth/Login').default);
Vue.component('password-reset', require('./Components/Views/Auth/PasswordReset').default);
Vue.component('reset-password', require('./Components/Views/Auth/ResetPassword').default);
Vue.component('registration', require('./Components/Views/Auth/Registration').default);
Vue.component('sign-up', require('./Components/Views/Auth/SignUp').default);
Vue.component('re-captcha', require('./Components/Views/Auth/ReCaptcha').default);

// Notifications
Vue.component('all-notification', require('./Components/Views/Auth/Notification').default);

// Profile
Vue.component('personal-information', require('./Components/Views/Auth/PersonalInformation').default);
Vue.component('password-change', require('./Components/Views/Auth/PasswordChange').default);
Vue.component('my-profile', require('./Components/Views/Auth/Profile').default);

// User and Roles
Vue.component('user-roles', require('./Components/Views/UserRoles/Index').default);
Vue.component('group-of-users', require('./Components/Views/UserRoles/Roles/GroupOfUsers').default);

// App Settings
Vue.component('app-setting', require('./Components/Views/Settings/AppSettings/Index').default);
Vue.component('general-setting', require('./Components/Views/Settings/AppSettings/GeneralSetting').default);
Vue.component('email-setting', require('./Components/Views/Settings/AppSettings/EmailSetting').default);
Vue.component('sms-setting', require('./Components/Views/Settings/AppSettings/SmsSetting').default);
Vue.component('google-re-captcha-setting', require('./Components/Views/Settings/AppSettings/GoogleRecaptchaSetting').default);
Vue.component('notification-settings', require('./Components/Views/Settings/AppSettings/Notification/Settings/Index').default);
Vue.component('database-template', require('./Components/Views/Settings/AppSettings/Notification/Template/DatabaseTemplate').default);
Vue.component('mail-template', require('./Components/Views/Settings/AppSettings/Notification/Template/MailTemplate').default);
Vue.component('app-test-mail-modal', require('./Components/Views/Settings/AppSettings/TestMailModal').default)
Vue.component('storage-setting', require('./Components/Views/Settings/AppSettings/Storage/StorageIndex.vue').default);

/**
 * all component of Job point App
 */

// Job Settings - Global
Vue.component('job-setting', require('./Components/Views/Settings/JobSettings/Index').default);
Vue.component('job-type-setting', require('./Components/Views/Settings/JobSettings/Type/JobTypes').default);
Vue.component('job-type-add-edit-modal', require('./Components/Views/Settings/JobSettings/Type/JobTypeAddEditModal').default);
Vue.component('department-setting', require('./Components/Views/Settings/JobSettings/Department/Departments').default);
Vue.component('department-add-edit-modal', require('./Components/Views/Settings/JobSettings/Department/DepartmentAddEditModal').default);
Vue.component('event-type-setting', require('./Components/Views/Settings/JobSettings/EventType/Event').default);
Vue.component('event-add-edit-modal', require('./Components/Views/Settings/JobSettings/EventType/EventTypeAddEditModal').default);
Vue.component('location-setting', require('./Components/Views/Settings/JobSettings/Location/Locations').default);
Vue.component('location-add-edit-modal', require('./Components/Views/Settings/JobSettings/Location/LocationAddEditModal').default);
Vue.component('hiring-stage-setting', require('./Components/Views/Settings/JobSettings/HiringStage/HiringStage').default);
Vue.component('hiring-stage-add-edit-modal', require('./Components/Views/Settings/JobSettings/HiringStage/StageAddEditModal').default);
Vue.component('application-form-setting', require('./Components/Views/Settings/JobSettings/ApplyForm/ApplyForm').default);
Vue.component('experience-level-setting', require('./Components/Views/Settings/JobSettings/ExperienceLevel/ExperienceLevels.vue').default);
Vue.component('experience-level-add-edit-modal', require('./Components/Views/Settings/JobSettings/ExperienceLevel/ExperienceLevelAddEditModal.vue').default);

// Integration
Vue.component('integrations', require('./Components/Views/Settings/Integrations/Index').default);
Vue.component('zoom-settings', require('./Components/Views/Settings/Integrations/ZoomSettings/ZoomSettings').default);
Vue.component('imap-settings', require('./Components/Views/Settings/Integrations/ImapSetting/ImapSettings').default);
Vue.component('google-search-integration', require('./Components/Views/Settings/Integrations/GoogleSearchIntegration/GoogleSearchIntegration').default);

// Dashboard Module
Vue.component('dashboard', require('./Components/Views/Dashboard/DashboardJobList').default);
Vue.component('events', require('./Components/Views/Dashboard/Events/Events').default);
Vue.component('all-events', require('./Components/Views/Dashboard/Events/AllEvents').default);
Vue.component('to-do', require('./Components/Views/Dashboard/Todo/Todo').default);
Vue.component('recent-jobs', require('./Components/Views/Dashboard/RecentJobs/RecentJobs.vue').default);
Vue.component('job-alerts', require('./Components/Views/Dashboard/JobAlerts/JobAlerts.vue').default);
Vue.component('job-alert-add-edit-modal', require('./Components/Views/Dashboard/JobAlerts/JobAlertAddEditModal.vue').default);
Vue.component('job-grid-view', require('./Components/Views/Dashboard/JobGridView').default);
Vue.component('job-card', require('./Components/Views/Dashboard/JobCard').default);
Vue.component('job-add-edit-modal', require('./Components/Views/Dashboard/JobAddEditModal').default);
Vue.component('shareable-link-modal', require('./Components/Views/Dashboard/ShareableLinkModal').default);
Vue.component('job-settings', require('./Components/Views/Dashboard/JobSettings/JobSettings').default);
Vue.component('apply-form-settings', require('./Components/Helpers/ApplyForm/ApplyForm').default);
Vue.component('personal-info-modal', require('./Components/Helpers/ApplyForm/PersonalInfoModal').default);
Vue.component('question-add-edit-modal', require('./Components/Helpers/ApplyForm/QuestionAddEditModal').default);
Vue.component('assignment-add-edit-modal', require('./Components/Helpers/ApplyForm/AssignmentAddEditModal').default);
Vue.component('job-application-form', require('./Components/Views/Dashboard/JobSettings/ApplyFormSetting/ApplicationForm').default);
Vue.component('job-stages-setting', require('./Components/Views/Dashboard/JobSettings/JobStagesSetting/JobStages').default);
Vue.component('delete-stage-confirmation', require('./Components/Views/Dashboard/JobSettings/JobStagesSetting/DeleteStageConfirmationModal').default);
Vue.component('thumbnail-image-setting', require('./Components/Views/Dashboard/JobSettings/ThumbnailImage/JobThumbnail').default);
Vue.component('hiring-team-setting', require('./Components/Views/Dashboard/JobSettings/HiringTeamSetting/HiringTeamSetting').default);
Vue.component('hiring-team-add-edit-modal', require('./Components/Views/Dashboard/JobSettings/HiringTeamSetting/HiringTeamAddEditModal').default);
Vue.component('job-editor', require('./Components/Views/Dashboard/JobEditor').default);
Vue.component('job-overview', require('./Components/Views/Dashboard/Overview/JobOverview').default);
Vue.component('overview', require('./Components/Views/Dashboard/Overview/Tabs/Overview').default);
Vue.component('job-candidates', require('./Components/Views/Dashboard/Overview/Tabs/Candidates').default);

// Candidate Public Module
//Vue.component('candidate-career-page', require('./Components/Views/CareerPage/CandidateCareerPage').default);
Vue.component('candidate-email-verification-modal', require('./Components/Views/Candidates/Helpers/CandidateEmailVerificationModal').default);
Vue.component('candidate-application-form', require('./Components/Views/Candidates/CandidateApplicationForm').default);
Vue.component('candidate-application-form-cus', require('./Components/Views/Candidates/FormApplication/CandidateApplicationForm').default);
Vue.component('candidate-job-post', require('./Components/Views/Dashboard/CandidateJobPost').default);

// Candidate App Module
Vue.component('candidates', require('./Components/Views/Candidates/Index').default);
Vue.component('my-story', require('./Components/Views/Candidates/MyStory.vue').default);
Vue.component('candidate-jobs-expandable-view', require('./Components/Views/Candidates/Helpers/CandidateJobsExpandableView').default);
Vue.component('candidate-table-star-review', require('./Components/Views/Candidates/Helpers/StarReview').default);
Vue.component('candidate-details-modal', require('./Components/Views/Candidates/CandidateDetailsModal').default);
Vue.component('candidate-assign-job-modal', require('./Components/Views/Candidates/CandidateActionsModal/AssignJobModal').default);
Vue.component('candidate-add-edit-modal', require('./Components/Views/Candidates/CandidateActionsModal/CandidateAddEditModal').default);
Vue.component('candidate-disqualify-modal', require('./Components/Views/Candidates/CandidateActionsModal/DisqualifyModal').default);
Vue.component('candidate-mailing-modal', require('./Components/Views/Candidates/CandidateActionsModal/MailingModal').default);
Vue.component('candidate-event-modal', require('./Components/Views/Candidates/CandidateActionsModal/EventAddEditModal').default);
Vue.component('career-page', require('./Components/Views/CareerPage/CareerPage').default);
Vue.component('candidate-status', require('./Components/Views/Candidates/Helpers/CandidateStatus').default);
Vue.component('candidate-import', require('./Components/Views/Candidates/ImportCandidate').default);

// Candidate Details Modal Tabs
Vue.component('candidate-notes', require('./Components/Views/Candidates/Tabs/CandidateNotes').default);
Vue.component('candidate-details', require('./Components/Views/Candidates/Tabs/CandidateDetails').default);
Vue.component('candidate-events', require('./Components/Views/Candidates/Tabs/CandidateEvents').default);
Vue.component('candidate-logs', require('./Components/Views/Candidates/Tabs/CandidateLogs').default);
Vue.component('candidate-conversation', require('./Components/Views/Candidates/Tabs/CandidateConversation').default);
Vue.component('candidate-attachments', require('./Components/Views/Candidates/Tabs/CandidateAttachments').default);
Vue.component('question-answer', require('./Components/Views/Candidates/Tabs/QuestionAnswer').default);
Vue.component('app-cron-job-settings', require('./Components/Views/Settings/AppSettings/CronJobSetting').default);

//Update
Vue.component('app-update', require('./Components/Views/Update/template/Update').default);
Vue.component('app-manual-updater', require('./Components/Views/Update/template/ManualUpdater').default);
Vue.component('app-update-confirmation-modal', require('./Components/Views/Update/template/UpdateConfirmationModal').default);

//Support
Vue.component('app-default-button', require('./Components/Helpers/Button/DefaultButton').default);
