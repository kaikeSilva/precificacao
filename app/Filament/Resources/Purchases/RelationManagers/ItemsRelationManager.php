<?php

namespace App\Filament\Resources\Purchases\RelationManagers;

use App\Filament\Resources\PurchaseItems\Schemas\PurchaseItemForm;
use App\Filament\Resources\PurchaseItems\Tables\PurchaseItemsTable;
use App\Models\Ingredient;
use App\Models\Packaging;
use App\Models\PurchaseItem;
use App\Services\IngredientCostHistoryItemService;
use App\Services\PackagingCostHistoryItemService;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static bool $canCreateAnother = false;

    // Rótulos singular/plural usados pelas ações e mensagens
    public static function getModelLabel(): string
    {
        return 'Item da compra';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Itens da compra';
    }

    public function form(Schema $schema): Schema
    {
        return PurchaseItemForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns(PurchaseItemsTable::configure($table)->getColumns())
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                ->after(function (CreateAction $action, PurchaseItem $record) {
                    if ($record->item_type === Ingredient::class) {
                        IngredientCostHistoryItemService::createFromPurchaseItem($record);
                    }
                    if ($record->item_type === Packaging::class) {
                        PackagingCostHistoryItemService::createFromPurchaseItem($record);
                    }
                }),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                // DissociateAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
