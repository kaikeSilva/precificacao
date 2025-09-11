<?php

namespace App\Filament\Resources\RecipeLaborRoles\Pages;

use App\Filament\Resources\RecipeLaborRoles\RecipeLaborRoleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditRecipeLaborRole extends EditRecord
{
    protected static string $resource = RecipeLaborRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
