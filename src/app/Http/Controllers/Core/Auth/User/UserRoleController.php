<?php

namespace App\Http\Controllers\Core\Auth\User;

use App\Exceptions\GeneralException;
use App\Hooks\User\BeforeUserAttachedToRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Auth\User\UserRoleRequest;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\User;
use App\Services\Core\Auth\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class UserRoleController extends Controller
{
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }


    public function store(User $user, UserRoleRequest $request)
    {
        $user = $this->service
            ->setModel($user)
            ->beforeAttachingRole()
            ->attachRole();

        return attached_response('roles', ['user' => $user]);
    }


    public function update(User $user, UserRoleRequest $request)
    {

        $user = $this->service
            ->setModel($user)
            ->beforeDetachingRole()
            ->detachRole();

        return detached_response('user', ['user' => $user]);
    }

    public function attachUsers(Role $role, Request $request)
    {
        $request->validate([
            'users' => 'required|array'
        ]);
        $users = $request->all()['users'];

        $adminRole = Role::query()->withCount('users')->where('name', 'App Admin')->first();

        $request_admin_count = User::query()
            ->whereHas('roles', function ($q) {
                $q->where('name', 'App Admin');
            })
            ->whereIn('id', $users)
            ->count();

        if ($request_admin_count >= $adminRole->users_count) {
            return response()->json([
                'message' => trans('default.system_must_have_at_least_one_admin_user'),
                'errors' => [
                    'users' => [ trans('default.system_must_have_at_least_one_admin_user')]
                ]
            ], 422);
        }

        DB::transaction(function () use ($users, $role, $request) {

            foreach ($users as $user) {
                $roleId = User::with('roles')
                    ->where('id', $user)
                    ->first();

                cache()->forget('app-admin-' . $user);

                if ($roleId['roles']->first()) {
                    $roleId->roles()->detach($roleId['roles']->first()->id);
                }
            }

            BeforeUserAttachedToRole::new()
                ->setModel($role)
                ->handle();

            $role->users()->detach($request->get('users'));
            $role->users()->attach($request->get('users'));
        });

        return attached_response('users');
    }

    public function detachUsers(Role $role, Request $request)
    {
        $role->users()->detach([$request->get('id')]);
        return detached_response('user');
    }
}