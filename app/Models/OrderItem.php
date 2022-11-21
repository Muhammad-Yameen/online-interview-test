<?php

namespace App\Models;

use App\Traits\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory,Currency;
    protected $appends = ['sub_total','formatted_price'];
    protected $fillable = ['product_id','quantity','price'];

    public function product()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function getFormattedPriceAttribute()
    {
        return $this->currency($this->price);
    }

    public function getSubTotalAttribute()
    {
        return $this->currency($this->price * $this->quantity);
    }


}
