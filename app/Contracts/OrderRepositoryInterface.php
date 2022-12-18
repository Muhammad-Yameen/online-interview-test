<?php

namespace App\Contracts;

interface OrderRepositoryInterface
{
    public function all();
    public function getUnPaidOrders();
    public function find($id);
    public function delete($id);
}
