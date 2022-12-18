<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function all()
    {
        return $this->product->all();
    }

    public function find($id)
    {
        return $this->product->find($id);
    }

    public function delete($id)
    {
        return $this->product->destroy($id);
    }
}
