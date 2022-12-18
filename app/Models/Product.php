<?php

namespace App\Models;

use App\Traits\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, Currency, SoftDeletes;

    protected $fillable = ['name', 'sku', 'slug', 'price'];

    protected $appends = ['formatted_price'];

    public function getFormattedPriceAttribute()
    {
        return $this->currency($this->price);
    }
}
