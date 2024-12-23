<?php

namespace App\Filament\Resources\StockUpdateResource\Pages;

use App\Filament\Resources\StockUpdateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockUpdates extends ListRecords
{
    protected static string $resource = StockUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
