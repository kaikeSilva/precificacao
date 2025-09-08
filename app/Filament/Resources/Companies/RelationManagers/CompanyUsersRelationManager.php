<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use App\Filament\Resources\CompanyUsers\CompanyUserResource;
use App\Services\CompanyUserService;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CompanyUsersRelationManager extends RelationManager
{
    protected static string $relationship = 'companyUsers';

    protected static ?string $relatedResource = CompanyUserResource::class;

    public function isReadOnly(): bool
    {
        // Permite ações de criação/edição dentro dos Relation Managers na página de View
        return false;
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                ->modal()
                ->createAnother(false)
                ->using(function (array $data, string $model): Model {
                    // Cria o usuário e vincula ao Company atual via serviço
                    $company = $this->getOwnerRecord();
                    $companyUser = app(CompanyUserService::class)->createFromForm($company, $data);
                    return $companyUser; // retornar o registro relacionado (User) para a tabela
                })
            ]);
    }

    // Mostrar SÓ na View; esconder em Edit/Create
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        // opção 1: checar pela classe base
        return is_a($pageClass, \Filament\Resources\Pages\ViewRecord::class, true);

        // opção 2: checar pela página específica do seu resource
        // return $pageClass === \App\Filament\Resources\CompanyResource\Pages\ViewCompany::class;
    }
}
