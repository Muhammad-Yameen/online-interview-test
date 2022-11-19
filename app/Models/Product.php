<?php

namespace App\Models;

use App\Traits\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory,Currency;

    protected $fillable = ['name', 'sku', 'slug', 'price'];

    protected $appends = ['formatted_price'];

    public function getFormattedPriceAttribute()
    {
        return $this->currency($this->price);
    }
}
