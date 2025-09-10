<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Filament\Resources\Companies\CompanyResource;
use App\Filament\Resources\CompanySettings\Schemas\CompanySettingsForm;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Company;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;

class ViewCompany extends ViewRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
             // Edit/Create Settings (hasOne) in a modal from the View page
            Action::make('editSettings')
            ->label('Configurações')
            ->icon('heroicon-m-cog-6-tooth')
            ->modalHeading('Configurações da Empresa')
            ->slideOver() // optional, use ->modal() if you prefer
            ->schema(fn (Schema $schema) => CompanySettingsForm::configure($schema)->getComponents())
            ->fillForm(fn($record) => $record->settings?->attributesToArray() ?? [])
            ->action(function (array $data, Company $record) {
                // Create or update the hasOne relation atomically
                $record->settings()->updateOrCreate([], $data);

                // Optional: refresh the page record so infolists/relations update
                $this->refreshFormData(['settings']);
                Notification::make()
                ->title('Configurações salvas com sucesso.')
                ->success()
                ->send();
            }),
        ];
    }
}
