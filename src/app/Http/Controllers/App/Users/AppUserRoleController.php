<?php

namespace App\Http\Controllers\App\Users;

use App\Exceptions\GeneralException;
use App\Hooks\User\AfterInvitationCanceled;
use App\Hooks\User\BeforeInvitationCanceled;
use App\Http\Controllers\Controller;
use App\Mail\Core\User\UserInvitationCancelMail;
use App\Models\Core\Auth\User;
use App\Services\App\SystemUserService;
use App\Services\Core\Auth\UserInvitationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AppUserRoleController extends Controller
{
    public function __construct(UserInvitationService $service)
    {
        $this->service = $service;
    }

    public function destroy(User $user)
    {
        /*if ($user->isAppAdmin() && !$user->isInvited()) {
            throw new GeneralException(trans('default.action_not_allowed'));
        }

        $user->roles()->detach();
        $user->delete();
        return deleted_responses('user');*/
    }

    public function cancelInvitation(User $user)
    {
        if ($user->isAppAdmin() && !$user->isInvited()) {
            throw new GeneralException(trans('default.action_not_allowed'));
        }

        DB::transaction(function () use ($user) {
            BeforeInvitationCanceled::new(true)
                ->setModel($user)
                ->handle();

            $this->service->setModel($user)->detachRoles()->delete();

            /*Mail::to($user->email)
                ->send(new UserInvitationCancelMail((object)$user->toArray()));*/

            AfterInvitationCanceled::new(true)
                ->setModel($user)
                ->handle();
        });

        return deleted_responses('user');
    }
}
