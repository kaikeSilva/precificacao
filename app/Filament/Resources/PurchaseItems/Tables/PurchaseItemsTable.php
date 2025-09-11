<?php

namespace App\Filament\Resources\PurchaseItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use App\Models\Ingredient;
use App\Models\Packaging;

class PurchaseItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('purchase.invoice_number')
                    ->label('Compra (NF)')
                    ->searchable(),
                TextColumn::make('item_type')
                    ->label('Tipo de item')
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            Ingredient::class => 'Ingrediente',
                            Packaging::class => 'Embalagem',
                            default => $state,
                        };
                    })
                    ->sortable(),
                TextColumn::make('item.name')
                    ->label('Item')
                    ->searchable(),
                TextColumn::make('qty')
                    ->label('Quantidade')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label('Preço unitário')
                    ->money('BRL', true)
                    ->sortable(),
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('BRL', true)
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
