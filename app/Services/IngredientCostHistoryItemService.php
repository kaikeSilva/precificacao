<?php

namespace App\Services;

use App\Models\IngredientCostHistoryItem;
use App\Models\Ingredient;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class IngredientCostHistoryItemService
{
    /**
     * Cria um item de histórico de custo a partir de um PurchaseItem (apenas quando o item é um Ingrediente)
     * e atualiza o campo current_price do ingrediente.
     */
    public static function createFromPurchaseItem(PurchaseItem $purchaseItem): IngredientCostHistoryItem
    {
        // O histórico de ingrediente só faz sentido quando o item comprado é um Ingrediente
        if ($purchaseItem->item_type !== Ingredient::class) {
            throw new InvalidArgumentException('PurchaseItem não é de tipo Ingrediente. item_type=' . (string) $purchaseItem->item_type);
        }

        $ingredient = $purchaseItem->item; // morphTo -> Ingredient
        if (! $ingredient instanceof Ingredient) {
            throw new InvalidArgumentException('O item do PurchaseItem não pôde ser resolvido como Ingredient.');
        }

        $purchase = $purchaseItem->purchase; // fornecedor, data, company

        return DB::transaction(function () use ($purchaseItem, $ingredient, $purchase) {
            // Preço do "pacote" (unidade de compra) vem de unit_price do item comprado
            // Atualiza o preço atual do ingrediente, ou seja o valor de custo de uma unidade da unidade base do ingrediente
            //   "unit_price" e o preco pago por uma unidade de compra da nota fiscal
            //   "quantity_item_unity" é a quantidade contida em unidade da unidade base do ingrediente em uma unidade de compra da nota fiscal
            //   Exemplo: 
            //   "unit_price" = 30.0
            //   "quantity_item_unity" = 500.0
            //   "current_unit_price" = 30.0 / 500.0 = 0.06
            $packPrice = (float) $purchaseItem->unit_price;

            $qtyPerUnit = (float) ($purchaseItem->quantity_item_unity ?? 0);
            $currentUnitPrice = $qtyPerUnit > 0 ? round($packPrice / $qtyPerUnit, 2) : 0;
            // Cria o histórico
            $history = IngredientCostHistoryItem::create([
                'ingredient_id'=> $ingredient->getKey(),
                'supplier_id'  => $purchase->supplier_id,
                'date'         => $purchase->invoice_date ?? now()->toDateString(),
                'pack_price'   => $packPrice,
                'current_unit_price' => $currentUnitPrice,
                'source'       => 'purchase_item:' . $purchaseItem->getKey(),
                'notes'        => null,
            ]);

            // Atualiza o preço atual do Ingredient
            $ingredient->forceFill([
                'current_price' => $currentUnitPrice,
            ])->save();

            return $history;
        });
    }
}