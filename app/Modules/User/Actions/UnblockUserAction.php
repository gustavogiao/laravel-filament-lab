<?php

declare(strict_types=1);

namespace App\Modules\User\Actions;

use App\Modules\User\DTOs\UserData;
use App\Modules\User\Models\User;

final class UnblockUserAction
{
    public function execute(User $user): void
    {
        $user->update([
            'is_blocked' => false,
            'blocked_at' => null,
            'blocked_by' => null,
        ]);
    }
}
