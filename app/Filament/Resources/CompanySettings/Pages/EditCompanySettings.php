<?php

namespace App\Filament\Resources\CompanySettings\Pages;

use App\Filament\Resources\CompanySettings\CompanySettingsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompanySettings extends EditRecord
{
    protected static string $resource = CompanySettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
