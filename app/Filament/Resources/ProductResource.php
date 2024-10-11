<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-m-squares-2x2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->label('Product Name')->maxLength(255),
                Select::make('category_id')
                    ->relationship('category', 'name')  // Dropdown from categories
                    ->required()
                    ->label('Category'),
                TextInput::make('quantity')->numeric()->required()->label('Stock Quantity'),
                TextInput::make('price')->numeric()->required()->label('Price (₹)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Category Name'),
                TextColumn::make('quantity'),
                TextColumn::make('price')
                    ->money('INR')
            ])
            ->filters([
                //
            ])
            ->actions([

                Action::make('Saled')
                ->label('Sale On')
                ->icon('heroicon-o-currency-dollar')
                ->form([
                    TextInput::make('quantity_sold')
                        ->numeric()
                        ->required()
                        ->label('Quantity Sold'), // Input field to get quantity sold
                ])
                ->action(function (Product $record, array $data) {
                    $quantity_sold = $data['quantity_sold'];
                    $product = $record;

                    if($record->quantity >= $quantity_sold)
                    {
                        $record->quantity = $record->quantity - $quantity_sold;
                        $record->save();

                        // create new style entry

                        Sale::create([
                            'product_id' => $record->id,
                            'quantity' => $quantity_sold,
                            'total_price' => $record->price * $quantity_sold
                        ]);
                        Notification::make()
                            ->title('Successfully Sold')
                            ->success()
                            ->send();
                    }
                    else{
                        Notification::make()
                            ->title('Quantity must less or equal current quantity!')
                            ->danger()
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-currency-dollar')
                ->modalHeading('Record Sale') // Heading of the modal
                ->modalSubmitActionLabel('Submit Sale') // Button text on the modal
                ->color('success')
                ->hidden(fn (Product $record) => $record->quantity <= 0),

                // if Out of stock
                Action::make('out_of_stock')
                    ->label('Out of Stock')
                    ->color('danger')
                    ->icon('heroicon-o-exclamation-circle')
                    ->disabled()  // Make it non-clickable
                    ->visible(fn (Product $record) => $record->quantity <= 0),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListProducts::route('/'),
            // 'create' => Pages\CreateProduct::route('/create'),
            // 'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
