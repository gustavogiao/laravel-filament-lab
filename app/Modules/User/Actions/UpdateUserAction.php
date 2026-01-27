<?php

namespace App\Modules\User\Actions;

use App\Modules\User\DTOs\UserData;
use App\Modules\User\Models\User;

final class UpdateUserAction
{
    public function execute(User $user, UserData $data): User
    {
        $user->name = $data->name;
        $user->email = $data->email;

        $user->save();

        return $user;
    }

}
