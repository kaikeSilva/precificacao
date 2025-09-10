<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use App\Filament\Resources\CompanyUsers\CompanyUserResource;
use App\Filament\Resources\CompanyUsers\Schemas\CompanyUserForm;
use App\Services\CompanyUserService;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Schema;

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
            ->modifyQueryUsing(fn ($query) => $query->with('user'))
            ->headerActions([
                CreateAction::make()
                ->using(function (array $data): Model {
                    return app(CompanyUserService::class)->createFromForm($this->ownerRecord, $data);
                })
                ->modal()
                ->createAnother(false)
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(CompanyUserForm::configure($schema)->getComponents());
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
