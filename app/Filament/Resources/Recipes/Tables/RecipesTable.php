<?php

namespace App\Filament\Resources\Recipes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class RecipesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('unit.name')
                    ->label('Unidade')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('production_qty')
                    ->label('Qtd produzida')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('preparation_min')
                    ->label('Preparo (min)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('resting_min')
                    ->label('Descanso (min)')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('active_time_only')
                    ->label('Somente tempo ativo')
                    ->boolean(),
                TextColumn::make('loss_pct')
                    ->label('Perda (%)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Excluído em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
