<?php

return [

    /*
    |--------------------------------------------------------------------------
    | JobPoint
    |--------------------------------------------------------------------------
    |
    */

    'work_arrangements' => [
        'on_site' => 'on_site',
        'hybrid' => 'hybrid',
        'remote' => 'remote',
    ],
    'job_alert_job_post' => '
        <div style="width: 100%;  background-color: white; cursor: pointer; font-weight: 600; transition: 250ms;">
       
        <a href="{job_link}" style="color: inherit; text-decoration: none; cursor:pointer; padding: 22px; display: block; ">
            <div style="gap: 0.65rem; display: flex; align-items: center;">
                <div style=" border-radius: 9px; background-color: #4466F2 !important; height: 44px; width: 44px; margin-right: 10px; padding-top: 4px;">
                    <span style=" color: white; font-size: 32.55px; line-height: 39px; margin:16px 5px  0 12px">{name_first_letter}</span>
                </div>
                <div>
                    <p style="font-size: 20px; color: #585858; font-weight: 300; margin: 0;">{name}</p>
                    <small style="color: #87877d;">{location}</small>
                </div>
            </div>
            <div>
                {job_post_card_badges}
                <div style="display: flex; align-items: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-user"
                        style=" width: 17px !important; height: 17px !important; color: #4466F2 !important; margin-right: 8px; display: inline-block; overflow: hidden; vertical-align: middle;">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <p style="color: #888888; font-size: 12px; margin: 4px 0;"><span style="color: #636363;">Number of
                            vacancies: </span> {vacancy_count} </p>
                </div>
                <div style="display: flex; align-items: center;"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"
                        style=" width: 17px !important; height: 17px !important; color: #4466F2 !important; margin-right: 8px; display: inline-block; overflow: hidden; vertical-align: middle;">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <p style="color: #888888; font-size: 12px; margin: 4px 0; "><span style="color: #636363;">Last
                            submission
                            date: </span> {last_submission_date}
                    </p>
                </div>
            </div>
            
        </a>
        <div style="height: 1px; background-color: #eee; width: 400px; max-width: 100%;"></div>
    </div>
    ',

];
