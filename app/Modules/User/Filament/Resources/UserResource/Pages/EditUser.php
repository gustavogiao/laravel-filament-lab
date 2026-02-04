<?php

namespace App\Modules\User\Filament\Resources\UserResource\Pages;

use App\Modules\Shared\Filament\Concerns\RedirectsToIndex;
use App\Modules\User\Actions\BlockUserAction;
use App\Modules\User\Actions\ResetUserTwoFactorAction;
use App\Modules\User\Actions\UnblockUserAction;
use App\Modules\User\Actions\UpdateUserAction;
use App\Modules\User\DTOs\UserData;
use App\Modules\User\Filament\Resources\UserResource;
use App\Modules\User\Models\User;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

final class EditUser extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = UserResource::class;

    protected function handleRecordUpdate(Model $record, array $data): User
    {
        /** @var User $record */
        return app(UpdateUserAction::class)
            ->execute($record, UserData::fromArray($data));
    }

    public function getHeaderActions(): array
    {
        /** @var User $user */
        $user = $this->record;

        return [
            Action::make('block')
                ->label('Block User')
                ->color('danger')
                ->icon('heroicon-o-lock-closed')
                ->requiresConfirmation()
                ->visible(fn () => ! $user->is_blocked)
                ->authorize('block', $user)
                ->action(fn () => app(BlockUserAction::class)->execute($user)),

            Action::make('unblock')
                ->label('Unblock User')
                ->color('success')
                ->icon('heroicon-o-lock-open')
                ->visible(fn () => $user->is_blocked)
                ->authorize('block', $user)
                ->action(fn () => app(UnblockUserAction::class)->execute($user)),

            Action::make('reset_2fa')
                ->label('Reset 2FA')
                ->icon('heroicon-o-key')
                ->requiresConfirmation()
                ->authorize('update', $user)
                ->action(fn () => app(ResetUserTwoFactorAction::class)->execute($user)),
        ];
    }
}
