<?php

declare(strict_types=1);

namespace App\Modules\Shared\Filament\Concerns;

trait RedirectsToIndex
{
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
