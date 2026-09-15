<?php

namespace App\Services\App;

use App\Exceptions\GeneralException;
use App\Models\Core\Auth\User;
use App\Services\Core\Auth\UserService;

class SystemUserService extends UserService
{

    public function delete(User $user)
    {
        if ($user->isAppAdmin() && !$user->isInvited())
            throw new GeneralException(trans('default.action_not_allowed'));

        if ($user->id == auth()->id())
            throw new GeneralException(trans('default.cant_delete_own_account'));

        return $user->forceDelete();
    }
}

