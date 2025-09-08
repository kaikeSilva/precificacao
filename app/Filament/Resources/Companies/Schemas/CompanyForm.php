<?php

namespace App\Filament\Resources\Companies\Schemas;

use App\Models\User as ModelsUser;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('document')
                    ->label('Documento')
                    ->required(),
                // TextInput::make('owner_user.name')
                //     ->label('Dono')
                //     ->required(),
                Select::make('owner_user_id')
                    ->label('Dono')
                    ->options(ModelsUser::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('timezone')
                    ->label('Fuso Horário')
                    ->required(),
                TextInput::make('created_at')
                    ->label('Criado em')
                    ->hidden(),
                TextInput::make('updated_at')
                    ->label('Atualizado em')
                    ->hidden()
            ]);
    }
}
