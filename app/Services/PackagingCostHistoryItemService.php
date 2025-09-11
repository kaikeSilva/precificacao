<?php

namespace App\Services;

use App\Models\PackagingCostHistoryItem;
use App\Models\Packaging;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PackagingCostHistoryItemService
{
    /**
     * Cria um histórico de custo de embalagem a partir de um PurchaseItem
     * (somente quando o item é do tipo Packaging).
     */
    public static function createFromPurchaseItem(PurchaseItem $purchaseItem): PackagingCostHistoryItem
    {
        // Garantir que é uma compra de Embalagem
        if ($purchaseItem->item_type !== Packaging::class) {
            throw new InvalidArgumentException('PurchaseItem não é de tipo Embalagem. item_type=' . (string) $purchaseItem->item_type);
        }

        $packaging = $purchaseItem->item; // morphTo -> Packaging
        if (! $packaging instanceof Packaging) {
            throw new InvalidArgumentException('O item do PurchaseItem não pôde ser resolvido como Packaging.');
        }

        $purchase = $purchaseItem->purchase; // fornecedor, data, company

        return DB::transaction(function () use ($purchaseItem, $packaging, $purchase) {
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

            $history = PackagingCostHistoryItem::create([
                'company_id'    => $purchase->company_id,
                'packaging_id'  => $packaging->getKey(),
                'supplier_id'   => $purchase->supplier_id,
                'date'          => $purchase->invoice_date ?? now()->toDateString(),
                'pack_price'    => $packPrice,
                'current_unit_price' => $currentUnitPrice,
                'source'        => 'purchase_item:' . $purchaseItem->getKey(),
                'notes'         => null,
            ]);

            // Se futuramente houver um campo de preço atual em Packaging, podemos atualizar aqui
            $packaging->forceFill(['current_price' => $currentUnitPrice])->save();

            return $history;
        });
    }
}
