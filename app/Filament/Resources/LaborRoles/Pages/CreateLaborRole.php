<?php

namespace App\Filament\Resources\LaborRoles\Pages;

use App\Filament\Resources\LaborRoles\LaborRoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLaborRole extends CreateRecord
{
    protected static string $resource = LaborRoleResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
