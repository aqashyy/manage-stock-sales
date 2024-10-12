<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockUpdate extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'quantity_added'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
