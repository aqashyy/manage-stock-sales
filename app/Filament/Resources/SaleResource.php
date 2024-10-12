<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Product;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;
class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->relationship('product', 'name')  // Dropdown from products
                    ->required()
                    ->label('Product')
                    ->live(onBlur:true),

                TextInput::make('quantity')->numeric()->required()->label('Quantity Sold')
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $price = Product::where('id',$get('product_id'))->pluck('price')->first();
                        $quantity = $get('quantity') == null ? '0' : $get('quantity');
                        $price = $price * $quantity;
                        // dd($price);
                        return $set('total_price',$price);
                    })->rules([
                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                            $current_quantity = Product::where('id',$get('product_id'))
                            ->pluck('quantity')->first();
                            if ($value > $current_quantity) {
                                if($current_quantity == '0')
                                {
                                    $fail('Out of stock!');
                                }
                                $fail('Only '.$current_quantity.' stocks available!');
                            }
                        },
                    ]),

                TextInput::make('total_price')->numeric()->label('Total Price ($)')->disabled(),  // Readonly

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('product.name')->label('Product')->sortable()->searchable(),
                TextColumn::make('quantity')->label('Quantity Sold')->sortable(),
                TextColumn::make('total_price')->label('Total Price ($)')->sortable(),
                TextColumn::make('created_at')->label('Date Sold')->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListSales::route('/'),
            // 'create' => Pages\CreateSale::route('/create'),
            // 'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
