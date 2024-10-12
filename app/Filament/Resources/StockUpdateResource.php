<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockUpdateResource\Pages;
use App\Filament\Resources\StockUpdateResource\RelationManagers;
use App\Models\StockUpdate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockUpdateResource extends Resource
{
    protected static ?string $model = StockUpdate::class;

    protected static ?string $navigationIcon = 'heroicon-c-square-3-stack-3d';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('product.name')->label('Product Name')->sortable()->searchable(),
                TextColumn::make('quantity_added')->label('Quantity Added'),
                TextColumn::make('created_at')->label('Added Date')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockUpdates::route('/'),
            // 'create' => Pages\CreateStockUpdate::route('/create'),
            // 'edit' => Pages\EditStockUpdate::route('/{record}/edit'),
        ];
    }
}
