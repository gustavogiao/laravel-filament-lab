<?php

declare(strict_types=1);

namespace App\Modules\User\Actions;

use App\Modules\User\DTOs\UserData;
use App\Modules\User\Models\User;

final class CreateUserAction
{
    public function execute(UserData $data): User
    {
        $user = new User;

        $user->name = $data->name;
        $user->email = $data->email;
        if ($data->password !== null) {
            $user->password = bcrypt($data->password);
        }

        $user->assignRole('super_admin');

        $user->save();

        return $user;
    }

}
