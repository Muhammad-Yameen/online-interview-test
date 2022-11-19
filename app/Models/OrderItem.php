<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $appends = ['sub_total'];
    public function product()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function getSubTotalAttribute()
    {
        return $this->price * $this->quantity;
    }


}
