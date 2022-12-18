<?php

namespace App\Contracts;

interface TransactionRepositoryInterface
{
    public function all();
    public function getAllWithOrders();
}
