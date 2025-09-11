<?php

namespace App\Filament\Resources\Recipes\RelationManagers;

use App\Filament\Resources\RecipeLaborRoles\RecipeLaborRoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class RecipeLaborRolesRelationManager extends RelationManager
{
    protected static string $relationship = 'recipeLaborRoles';

    protected static ?string $relatedResource = RecipeLaborRoleResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
