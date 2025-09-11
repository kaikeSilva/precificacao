<?php

namespace App\Filament\Resources\RecipeLaborRoles\Pages;

use App\Filament\Resources\RecipeLaborRoles\RecipeLaborRoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRecipeLaborRole extends CreateRecord
{
    protected static string $resource = RecipeLaborRoleResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
