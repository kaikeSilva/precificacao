<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Filament\Resources\Companies\CompanyResource;
use App\Services\CompanyService;
use Filament\Resources\Pages\CreateRecord;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Empresa criada';
    }

    // protected function handleRecordCreation(array $data): Model
    // {
    //     $company = app(CompanyService::class)->createCompany($data);
    //     $this->ownerPayload = $company->owner->user;
    //     return $company;
    // }

    protected array $ownerPayload = [];

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     // dump("mutating form data before create", $data);
    //     return $data;
    // }

    // protected function afterCreate(): void
    // {
    //     // dump("after create ownerPayload", $this->ownerPayload);
    // }
}
