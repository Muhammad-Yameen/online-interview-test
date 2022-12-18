<?php

namespace App\Contracts;

interface UserRepositoryInterface
{
    public function all();
    public function getAllWithOrderCounts();
    public function find($id);
    public function delete($id);
}
