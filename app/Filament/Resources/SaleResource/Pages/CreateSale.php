<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $product = \App\Models\Product::find($data['product_id']);
        $data['total_price'] = $product->price * $data['quantity'];  // Calculate total price

        // Update product stock
        if ($product->quantity >= $data['quantity']) {
            $product->quantity = $product->quantity - $data['quantity'];
            $product->save();
        } else {
            throw new \Exception('Not enough stock available.');
        }

        return $data;
    }
}
