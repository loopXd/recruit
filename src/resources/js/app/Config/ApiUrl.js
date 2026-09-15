import {TENANT_BASE_URL} from './UrlHelper';

//Notification
export const NOTIFICATIONS = `${TENANT_BASE_URL}admin/user/notifications`;
export const NOTIFICATION_EVENTS = `${TENANT_BASE_URL}admin/app/notification-events`;
export const NOTIFICATION_TEMPLATES = `${TENANT_BASE_URL}admin/app/notification-templates`;

// Profile
export const MY_PROFILE = `${TENANT_BASE_URL}my-profile`;
export const All_NOTIFICATION = `${TENANT_BASE_URL}all-notifications`;
export const LOGOUT = `${TENANT_BASE_URL}admin/log-out`;

// app setting view
export const APP_SETTINGS = `${TENANT_BASE_URL}app-setting`;
export const GENERAL_SETTINGS = `${TENANT_BASE_URL}general-settings`;

// reCAPTCHA
export const GET_RECAPTCHA_SETTINGS = `${TENANT_BASE_URL}get-re-captcha-setting`;

// Sms settings
export const GET_SMS_SETTINGS_INFO = `${TENANT_BASE_URL}get-sms-setting-info`;

// Email settings
export const GET_EMAIL_SETTINGS = `${TENANT_BASE_URL}admin/app/settings/delivery-settings`;

// User and Roles
export const ROLES = `${TENANT_BASE_URL}admin/auth/roles`;
export const USERS = `${TENANT_BASE_URL}admin/auth/users`;
export const USERS_LIST = `${TENANT_BASE_URL}user-list`;
export const ALL_USERS = `${TENANT_BASE_URL}all-users`;
export const GET_USERS = `${TENANT_BASE_URL}get/users`;
export const GET_STATUSES = `${TENANT_BASE_URL}admin/app/statuses`;
export const INVITE_USER = `${TENANT_BASE_URL}admin/auth/users/invite-user`;
export const DETACH_ROLES = `${TENANT_BASE_URL}admin/auth/users/detach-roles/`;
export const UPDATE_USER_NAME = `${TENANT_BASE_URL}update-user-name/`;
export const LOGIN_USER = `${TENANT_BASE_URL}login-user`;

// Language
export const LANGUAGE = `${TENANT_BASE_URL}languages`;
export const BASIC_SETTINGS = `${TENANT_BASE_URL}get-basic-setting-data`;
export const LANG = `${TENANT_BASE_URL}lang`;

/**
 * Job Point All Routes
 */

// Job Setting - Global
export const JOB_TYPE = `${TENANT_BASE_URL}app/job-type`;

export const EXPERIENCE_LEVEL = `${TENANT_BASE_URL}app/experience-level`;
export const SET_JOB_ALERT = `${TENANT_BASE_URL}app/set-job-alert`;
export const GET_JOB_ALERT = `${TENANT_BASE_URL}app/get-job-alert`;

export const DEPARTMENT = `${TENANT_BASE_URL}app/department`;
export const COMPANY_LOCATION = `${TENANT_BASE_URL}app/company-location`;
export const STAGE = `${TENANT_BASE_URL}app/stage`;
export const GLOBAL_APPLICATION_FORM = `${TENANT_BASE_URL}app/global/application-form`;
export const CAREER_PAGE = `${TENANT_BASE_URL}app/career-page`;

// Selectable Data - Global
export const SELECTABLE = `${TENANT_BASE_URL}app/selectable`;
export const SELECTABLE_JOBS = `${TENANT_BASE_URL}app/selectable/job-posts`;
export const SELECTABLE_DEPARTMENTS = `${TENANT_BASE_URL}app/selectable/departments`;
export const SELECTABLE_STAGES = `${TENANT_BASE_URL}app/selectable/stages`;
export const SELECTABLE_JOB_TYPES = `${TENANT_BASE_URL}app/selectable/job-types`;
export const SELECTABLE_JOB_EXPERIENCE_LEVELS = `${TENANT_BASE_URL}app/selectable/job-experience-levels`;
export const SELECTABLE_COMPANY_LOCATIONS = `${TENANT_BASE_URL}app/selectable/company-locations`;
export const SELECTABLE_EVENT_TYPES = `${TENANT_BASE_URL}app/selectable/event-types`;

// searchable data
export const SELECTABLE_DEPARTMENTS_BY_SEARCH = `${TENANT_BASE_URL}app/selectable/departments/search`;
export const SELECTABLE_COMPANY_LOCATIONS_BY_SEARCH = `${TENANT_BASE_URL}app/selectable/company-locations/search`;

// Candidate or Applicant - Global
export const CANDIDATE = `${TENANT_BASE_URL}app/applicant`;
export const MY_STORY = `${TENANT_BASE_URL}app/my-story`;
export const VERIFY_EMAIL = `${TENANT_BASE_URL}app/applicant/check-email`;

// Job Applicant - Applicant after applied job
export const JOB_APPLICANT = `${TENANT_BASE_URL}app/job-applicant`;

// Job
export const JOB = `${TENANT_BASE_URL}app/job-post`;
export const ALL_JOBS = `${TENANT_BASE_URL}app/get/job-posts`;
export const BULK_ASSIGN_JOBS = `${TENANT_BASE_URL}app/bulk-assign/jobs`;
export const BULK_RETRACT_JOBS = `${TENANT_BASE_URL}app/bulk-retract/jobs`;
export const JOB_POST = `${TENANT_BASE_URL}app/job-post`;
export const JOB_STAGE = `${TENANT_BASE_URL}app/job-stage`;

// Dashboard
export const DASHBOARD = `${TENANT_BASE_URL}app/dashboard`;

// Events
export const EVENT = `${TENANT_BASE_URL}app/event`;
export const EVENT_TYPE = `app/event-type`;

// Todo
export const TODO = `${TENANT_BASE_URL}app/todo`;

// Notes
export const NOTE = `${TENANT_BASE_URL}app/note`;

// Hiring Team
export const HIRING_TEAM_LIST = `${TENANT_BASE_URL}app/get-teams-by-job`;
export const HIRING_TEAM = `${TENANT_BASE_URL}app/hiring-team/`;

//Social Sharable
export const SOCIAL_SHARABLE_THUMBNAIL = `${TENANT_BASE_URL}app/sharable_thumbnail`;

// Public Url
export const PUBLIC_JOB_POST = `${TENANT_BASE_URL}job-post`;
export const PUBLIC_VERIFY_EMAIL = `${TENANT_BASE_URL}candidate/check-email`;
export const PUBLIC_CAREER_PAGE = `${TENANT_BASE_URL}career`;

//Update
export const APP_UPDATE = `${TENANT_BASE_URL}app/updates`;
export const APP_UPDATE_INSTALL = `${TENANT_BASE_URL}app/updates/install`;

//Install
export const APP_LOG_IN = `${TENANT_BASE_URL}admin/users/login`;
export const APP_INSTALL_ADMIN_INFO = `${TENANT_BASE_URL}setup/admin-info`;
export const GENERATE_PURCHASE_CODE_URL = `${TENANT_BASE_URL}setup/generate-url`;
export const GET_DATABASE_HOSTNAME = `${TENANT_BASE_URL}setup/get-database-hostname`;
export const GET_UPDATE_URL = `${TENANT_BASE_URL}app/generated-update-url-purchase-code`;
export const SET_UP_EMAIL = `${TENANT_BASE_URL}setup/email-setup`;
export const SET_UP_BROADCAST = `${TENANT_BASE_URL}setup/broadcast-setup`;
export const BROADCAST_SETTING_UPDATE = `${TENANT_BASE_URL}setup/broadcast-setting-update`;
export const BROADCAST_SKIP = `${TENANT_BASE_URL}setup/broadcast-skip`;
export const EMAIL_SETUP_SKIP = `${TENANT_BASE_URL}setup/email-setup-skip`;
export const ADDITIONAL_REQUIREMENTS = `${TENANT_BASE_URL}setup/additional-requirements`;
export const ADDITIONAL_REQUIREMENT = `${TENANT_BASE_URL}setup/additional-requirement`;
export const DATABASE_CONFIGURATION = `${TENANT_BASE_URL}setup/database`;
export const PURCHASE_CODE= `${TENANT_BASE_URL}setup/purchase-code`;
export const PURCHASE_CODE_STORE= `${TENANT_BASE_URL}setup/purchase-code-store`;
export const INSTALL= `${TENANT_BASE_URL}install`;
export const EMAIL_SETTING_UPDATE= `${TENANT_BASE_URL}setup/email-setting-update-delivery`;
export const TEST_MAIL= `${TENANT_BASE_URL}app/test-mail/send`;
export const CLEAR_CACHE= `${TENANT_BASE_URL}app/clear-cache`;
export const CRON_JOB_SETTING= `${TENANT_BASE_URL}admin/app/settings/cronjob`;
export const MAIL_SETUP_CHECK_URL = `${TENANT_BASE_URL}check-mail-settings`;
export const JOB_POINT_EMAIL_SETUP_SETTING = `${TENANT_BASE_URL}app-setting?tab=Email%20Setup`;
export const NOTIFICATION_SETTINGS_FRONT_END = `${TENANT_BASE_URL}app-setting?tab=Notification`;
export const CANCEL_INVITATION = `${TENANT_BASE_URL}cancel-invitation`;
export const GET_ZOOM_SETTINGS = `${TENANT_BASE_URL}admin/app/meeting/zoom-setting`;
export const GET_IMAP_SETTINGS = `${TENANT_BASE_URL}admin/app/imap/imap-setting`;
export const IMAP_SETTING = `${TENANT_BASE_URL}app-setting?tab=Imap%20settings`;
export const COVER_IMAGE_UPDATE = `${TENANT_BASE_URL}app/cover-image`;
//export
export const EXPORT = `${TENANT_BASE_URL}app/applicant/export`;
export const GET_LAST_APPLIED_JOBS = `${TENANT_BASE_URL}app/applicant/last-applied-job`;
//import
export const IMPORT_CANDIDATE = `${TENANT_BASE_URL}import/candidates`;

export const JOB_LIST = `${TENANT_BASE_URL}/job-post-list`;
export const DETACH_USER = `${TENANT_BASE_URL}/admin/auth/roles`;

export const STORAGE_SETTING = '/storage-configuration';

