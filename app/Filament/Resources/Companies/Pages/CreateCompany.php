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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd("mutating form data before create", $data);
               // Now this will exist because we used statePath('owner') instead of relationship('owner')
               $this->ownerPayload = $data['owner'] ?? [];
               unset($data['owner']); // keep Company clean
               // dd('mutating form data before create', $data, $this->ownerPayload);
               return $data;
    }

    protected function afterCreate(): void
    {
        // dd("after create ownerPayload", $this->ownerPayload);
        $ownerRole = $this->ownerPayload['role'] ?? CompanyUser::ROLE_OWNER;
        $userData  = Arr::get($this->ownerPayload, 'user', []);

        // Basic guard (nice errors instead of NOT NULL later)
        validator($userData, [
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        $user = User::create([
            'name'     => $userData['name'],
            'email'    => $userData['email'],
            'password' => $userData['password'], // hashed via casts()
        ]);

        $this->record->companyUsers()->create([
            'user_id' => $user->id,
            'role'    => $ownerRole,
        ]);
    }
}
