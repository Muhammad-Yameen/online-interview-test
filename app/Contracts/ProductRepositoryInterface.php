<?php

namespace App\Contracts;

interface ProductRepositoryInterface
{
    public function all();
    public function find($id);
    public function delete($id);
}
