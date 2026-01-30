<?php

namespace App\Modules\User\Filament\Resources\UserResource\Pages;

use App\Modules\Shared\Filament\Concerns\RedirectsToIndex;
use App\Modules\User\Actions\CreateUserAction;
use App\Modules\User\DTOs\UserData;
use App\Modules\User\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

final class CreateAdmin extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateUserAction::class)
            ->execute(UserData::fromArray($data));
    }
}
