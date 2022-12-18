<?php

namespace App\Repositories;

use App\Contracts\TransactionRepositoryInterface;
use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    protected Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function all()
    {
        return $this->transaction->all();
    }

    public function getAllWithOrders()
    {
        return $this->transaction->with('order')->orderBy('id', 'desc')->get();
    }
}
