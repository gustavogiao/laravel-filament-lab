<?php

declare(strict_types=1);

namespace App\Modules\User\Actions;

use App\Modules\User\Models\User;
use Illuminate\Support\Facades\DB;

final class BlockUserAction
{
    public function execute(User $user): void
    {
        /** @var User $actor */
        $actor = auth()->user();

        $user->update([
            'is_blocked' => true,
            'blocked_at' => now(),
            'blocked_by' => $actor->id,
        ]);

        DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete();
    }
}
