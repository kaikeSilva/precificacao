<?php

namespace App\Filament\Resources\RecipeLaborRoles\Pages;

use App\Filament\Resources\RecipeLaborRoles\RecipeLaborRoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRecipeLaborRoles extends ListRecords
{
    protected static string $resource = RecipeLaborRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
