<?php

namespace App\Filament\Resources\IngredientCostHistoryItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IngredientCostHistoryItemsTable
{

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ingredient.name')
                    ->label('Ingrediente')
                    ->searchable(),
                TextColumn::make('supplier.name')
                    ->label('Fornecedor')
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('pack_price')
                    ->label('Preço da embalagem')
                    ->money('BRL', true)
                    ->sortable(),
                TextColumn::make('current_unit_price')
                    ->label('Preço unitário')
                    ->money('BRL', true)
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Fonte')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

