/***
 **  App permission string will be handled in front end from here **
 ***/

// Dashboard Permissions
export const PERMISSION_VIEW_DASHBOARD = 'view_dashboard';
export const PERMISSION_VIEW_JOB_POST = 'can_view_job_post';
export const PERMISSION_CREATE_JOB_POST = 'can_create_job_post';
export const PERMISSION_EDIT_JOB = 'can_update_job_post';
export const PERMISSION_DELETE_JOB_POST = 'can_delete_job_post';
export const PERMISSION_VIEW_JOB_OVERVIEW = 'can_view_job_overview';
export const PERMISSION_VIEW_CANDIDATE_LIST_FROM_JOB_OVERVIEW = 'can_view_applicants_job_overview';
export const PERMISSION_SHARABLE_LINK_OF_JOB_POST = 'can_sharable_link_job_post';
export const PERMISSION_EDIT_JOB_POST = 'can_update_job_post_setting_job_post';
export const PERMISSION_VIEW_JOB_POST_SETTING = 'can_view_job_post_setting';
export const PERMISSION_MANAGE_JOB_POST_APPLICATION_FORM = 'can_manage_job_post_application_form';
export const PERMISSION_CHANGE_JOB_POST_STATUS = 'can_change_job_post_status';

export const PERMISSION_VIEW_JOB_POST_STAGE = 'can_view_job_stage';
export const PERMISSION_CREATE_JOB_POST_STAGE = 'can_create_job_stage';
export const PERMISSION_UPDATE_JOB_POST_STAGE = 'can_update_job_stage';
export const PERMISSION_DELETE_JOB_POST_STAGE = 'can_delete_job_stage';
export const PERMISSION_VIEW_HIRING_TEAM = 'can_view_hiring_team';
export const PERMISSION_CREATE_HIRING_TEAM = 'can_create_hiring_team';
export const PERMISSION_UPDATE_HIRING_TEAM = 'can_update_hiring_team';
export const PERMISSION_DELETE_HIRING_TEAM = 'can_delete_hiring_team';
export const PERMISSION_MANAGE_SHARABLE_THUMBNAIL = 'can_manage_sharable_thumbnail';

// Candidate Permissions
export const PERMISSION_VIEW_CANDIDATE = 'can_view_applicant';
export const PERMISSION_CREATE_CANDIDATE = 'can_create_applicant';
export const PERMISSION_EXPORT_CANDIDATE = 'can_export_applicants';
export const PERMISSION_UPDATE_CANDIDATE = 'can_update_applicant';
export const PERMISSION_DELETE_CANDIDATE = 'can_delete_applicant';
export const PERMISSION_VIEW_EVENT = 'can_view_event';
export const PERMISSION_CREATE_EVENT = 'can_create_event';
export const PERMISSION_UPDATE_EVENT = 'can_update_event';
export const PERMISSION_DELETE_EVENT = 'can_delete_event';
export const PERMISSION_VIEW_TIMELINE = 'can_view_job_applicant_timeline';
export const PERMISSION_VIEW_CANDIDATE_DETAILS = 'can_view_applicant_details'; // Not use
export const PERMISSION_CHANGE_STAGE = 'can_change_stage_job_applicant';
export const PERMISSION_SEND_EMAIL_TO_CANDIDATE = 'can_send_email_job_applicant';
export const PERMISSION_CHANGE_REVIEW = 'can_change_review_job_applicant';
export const PERMISSION_DISQUALIFY_CANDIDATE = 'can_disqualify_job_applicant';
export const PERMISSION_VIEW_NOTES_OWN_CREATED = 'can_view_note_by_recruiters';
export const PERMISSION_VIEW_JOB_APPLICANT = 'can_view_job_applicant';
export const PERMISSION_ASSIGN_JOB = 'can_create_job_applicant';
export const PERMISSION_UN_ASSIGN_JOB = 'can_delete_job_applicant';
export const PERMISSION_VIEW_EVENTS_FOR_JOB_APPLICANT = 'can_view_events_for_job_applicant';
export const PERMISSION_VIEW_NOTE = 'can_view_note';
export const PERMISSION_CREATE_NOTE = 'can_create_note';
export const PERMISSION_UPDATE_NOTE = 'can_update_note';
export const PERMISSION_DELETE_NOTE = 'can_delete_note';

// Career Page Permissions
export const PERMISSION_VIEW_CAREER_PAGE = 'can_view_career_page'; // Used But Unmarked ???
export const PERMISSION_UPDATE_CAREER_PAGE  = 'can_update_career_page'; // Used But Unmarked ???

// JOB Settings Permissions - Global
export const PERMISSION_VIEW_JOB_SETTINGS  = 'can_view_job_setting';
export const PERMISSION_MANAGE_GLOBAL_APPLICATION_FORM  = 'can_manage_global_application_form';
export const PERMISSION_VIEW_STAGE  = 'can_view_stage';
export const PERMISSION_CREATE_STAGE  = 'can_create_stage';
export const PERMISSION_UPDATE_STAGE  = 'can_update_stage';
export const PERMISSION_DELETE_STAGE  = 'can_delete_stage';
export const PERMISSION_VIEW_EVENT_TYPE  = 'can_view_event_type';
export const PERMISSION_CREATE_EVENT_TYPE   = 'can_create_event_type';
export const PERMISSION_UPDATE_EVENT_TYPE   = 'can_update_event_type';
export const PERMISSION_DELETE_EVENT_TYPE   = 'can_delete_event_type';
export const PERMISSION_VIEW_JOB_TYPE  = 'can_view_job_type';
export const PERMISSION_CREATE_JOB_TYPE  = 'can_create_job_type';
export const PERMISSION_UPDATE_JOB_TYPE  = 'can_update_job_type';
export const PERMISSION_DELETE_JOB_TYPE  = 'can_delete_job_type';
export const PERMISSION_VIEW_DEPARTMENT  = 'can_view_department';
export const PERMISSION_CREATE_DEPARTMENT  = 'can_create_department';
export const PERMISSION_UPDATE_DEPARTMENT  = 'can_update_department';
export const PERMISSION_DELETE_DEPARTMENT  = 'can_delete_department';
export const PERMISSION_VIEW_COMPANY_LOCATION  = 'can_view_company_location';
export const PERMISSION_CREATE_COMPANY_LOCATION = 'can_create_company_location';
export const PERMISSION_UPDATE_COMPANY_LOCATION  = 'can_update_company_location';
export const PERMISSION_DELETE_COMPANY_LOCATION  = 'can_delete_company_location';
export const PERMISSION_VIEW_EXPERIENCE_LEVEL  = 'can_view_experience_level';
export const PERMISSION_CREATE_EXPERIENCE_LEVEL  = 'can_create_experience_level';
export const PERMISSION_UPDATE_EXPERIENCE_LEVEL  = 'can_update_experience_level';
export const PERMISSION_DELETE_EXPERIENCE_LEVEL  = 'can_delete_experience_level';

/***
 * Core
 */

// App Settings

export const PERMISSION_VIEW_SETTINGS = 'view_settings';
export const PERMISSION_UPDATE_SETTINGS  = 'update_settings';
export const PERMISSION_VIEW_DELIVERY_SETTINGS = 'view_delivery_settings';
export const PERMISSION_UPDATE_DELIVERY_SETTINGS = 'update_delivery_settings';
export const PERMISSION_VIEW_NOTIFICATION_SETTINGS = 'view_notification_settings';
export const PERMISSION_UPDATE_NOTIFICATION_SETTINGS  = 'update_notification_settings';
export const PERMISSION_VIEW_CRON_JOB_SETTINGS  = 'view_cron_job_settings';
export const PERMISSION_VIEW_UPDATE_SETTINGS  = 'view_update_settings';
export const PERMISSION_VIEW_IMAP_SETTINGS  = 'view_imap_settings';
export const PERMISSION_VIEW_STORAGE_SETTINGS  = 'view_storage_settings';


// Users
export const PERMISSION_VIEW_USERS  = 'view_users';
export const PERMISSION_CREATE_USERS  = 'create_users';
export const PERMISSION_UPDATE_USERS  = 'update_users';
export const PERMISSION_DELETE_USERS  = 'delete_users';
export const PERMISSION_INVITE_USER  = 'invite_user';
export const PERMISSION_CANCEL_USER_INVITATION  = 'cancel_user_invitation';
export const PERMISSION_ATTACH_ROLES_TO_USERS  = 'attach_roles_users';
export const PERMISSION_DETACH_ROLES_TO_USERS  = 'detach_roles_users';
export const PERMISSION_CHANGE_SETTING_USERS  = 'change_settings_users';
export const PERMISSION_SETTING_LIST_USER  = 'settings_list_users';
// Roles
export const PERMISSION_VIEW_ROLES  = 'view_roles';
export const PERMISSION_CREATE_ROLES  = 'create_roles';
export const PERMISSION_UPDATE_ROLES  = 'update_roles';
export const PERMISSION_DELETE_ROLES  = 'delete_roles';
export const PERMISSION_ATTACH_USERS_TO_ROLES  = 'attach_users_to_roles';

// Permissions
export const PERMISSION_ATTACH_PERMISSION_OF_ROLES  = 'attach_permissions_roles';
export const PERMISSION_DETACH_PERMISSION_OF_ROLES  = 'detach_permissions_roles';

// Template
export const PERMISSION_VIEW_NOTIFICATION_TEMPLATE  = 'view_notification_templates';
export const PERMISSION_CREATE_NOTIFICATION_TEMPLATE  = 'create_notification_templates';
export const PERMISSION_UPDATE_NOTIFICATION_TEMPLATE  = 'update_notification_templates';
export const PERMISSION_DELETE_NOTIFICATION_TEMPLATE  = 'delete_notification_templates';
export const PERMISSION_VIEW_TEMPLATES = 'view_templates';
export const PERMISSION_UPDATE_TEMPLATES  = 'update_templates';
export const PERMISSION_DELETE_TEMPLATES  = 'delete_templates';
