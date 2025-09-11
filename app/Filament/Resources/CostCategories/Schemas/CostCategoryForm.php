<?php

namespace App\Filament\Resources\CostCategories\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CostCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(function () {
                        // dd(currentCompanyId());
                        if (function_exists('currentCompanyId')) {
                            return currentCompanyId();
                        }
                        return null;
                    })
                    ->dehydrated(),
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
            ]);
    }
}
