<?php

namespace App\Models;

use App\Traits\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory,Currency;
    protected $appends = ['order_total'];

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getOrdertotalAttribute()
    {
        $amount = $this->order_items()->sum(DB::raw('order_items.price * order_items.quantity'));
        return $this->currency($amount);
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function transactions()
    {
        return $this->hasOne(Transaction::class);
    }

}
