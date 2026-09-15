<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class AdministrationPermissions
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            [
                'name' => __t('users_roles'),
                'url' => $this->userUrl(),
                'permission' => authorize_any(['view_roles'])
            ],
        ];
    }

    public function canVisit()
    {
        return authorize_any(['view_users', 'view_roles']);
    }

    public function userUrl()
    {
        return route(
            'app.feature.applicants_modules',
            //optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
            ''
        );
    }
}
