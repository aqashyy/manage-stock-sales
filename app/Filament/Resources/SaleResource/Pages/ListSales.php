<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->mutateFormDataUsing(function (array $data): array {

                $product = Product::find($data['product_id']);
                $data['total_price'] = $product->price * $data['quantity'];  // Calculate total price

                // Update product stock
                if ($product->quantity >= $data['quantity']) {
                    $product->quantity = $product->quantity - $data['quantity'];
                    $product->save();
                }
                // dd($data);
                return $data;
            }),
        ];
    }
}
