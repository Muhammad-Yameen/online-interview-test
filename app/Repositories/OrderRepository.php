<?php

namespace App\Repositories;

use App\Contracts\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function all()
    {
        return $this->order->with(['user', 'order_items.product'])->get();
    }

    public function getUnPaidOrders()
    {
        return $this->order->where('status', 'unpaid')->orderBy('id', 'desc')->get();
    }

    public function find($id)
    {
        return $this->order->find($id);
    }

    public function delete($id)
    {
        return $this->order->destroy($id);
    }
}
