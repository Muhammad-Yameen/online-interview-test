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

    public function find($id)
    {
        return $this->order->find($id);
    }

    public function create(array $data)
    {
        return $this->order->create($data);
    }

    public function update($id, array $data)
    {
        $order = $this->order->find($id);
        $order->update($data);
        return $order;
    }

    public function delete($id)
    {
        return $this->order->destroy($id);
    }
}
