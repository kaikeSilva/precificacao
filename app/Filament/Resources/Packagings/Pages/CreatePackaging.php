<?php

namespace App\Filament\Resources\Packagings\Pages;

use App\Filament\Resources\Packagings\PackagingResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePackaging extends CreateRecord
{
    protected static string $resource = PackagingResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
