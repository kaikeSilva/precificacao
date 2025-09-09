<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Filament\Resources\Companies\CompanyResource;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use App\Services\CompanyService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

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
    //     $company = Company::find(1);
    //     dd($company->owner->user);
    //     return app(CompanyService::class)->createCompany($data);
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
