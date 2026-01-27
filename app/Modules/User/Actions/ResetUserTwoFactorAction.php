<?php

declare(strict_types=1);

namespace App\Modules\User\Actions;

use App\Modules\User\Models\User;

final class ResetUserTwoFactorAction
{
    public function execute(User $user): void
    {
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }
}

