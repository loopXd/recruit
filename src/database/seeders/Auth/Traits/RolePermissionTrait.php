<?php

namespace Database\Seeders\Auth\Traits;

trait RolePermissionTrait
{
    public function role($type, $group = null)
    {
        return [
            [
                'name' => 'view_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'roles'
            ],
            [
                'name' => 'create_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'roles'
            ],
            [
                'name' => 'update_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'roles'
            ],
            [
                'name' => 'delete_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'roles'
            ],
            [
                'name' => 'attach_users_to_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'roles'
            ],
            [
                'name' => 'detach_users_to_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'roles'
            ],
            [
                'name' => 'attach_permissions_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'permissions'
            ],
            [
                'name' => 'detach_permissions_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'permissions'
            ]
        ];
    }
}
