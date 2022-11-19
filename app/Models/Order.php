<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;
    protected $appends = ['order_total'];

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getOrdertotalAttribute()
    {
        return $this->order_items()->sum(DB::raw('order_items.price * order_items.quantity'));
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function transactions()
    {
        return $this->hasOne(Transaction::class);
    }

}
