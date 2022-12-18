<?php

namespace App\Http\Controllers;

use App\Contracts\ProductRepositoryInterface;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public ProductRepositoryInterface $product;

    public function __construct(ProductRepositoryInterface $product)
    {
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->product->all();
        $title = 'Products';
        return Inertia::render(
            'products/index',
            [
                'products' => $products,
                'title' => $title,
                'singular_title' => Str::singular($title),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        Product::create($request->all());
    }
    /**
     * Show resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product  = $this->product->find($id);
        $title = 'Show Product';
        return Inertia::render(
            'products/show',
            [
                'product' => $product,
                'title' => $title,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->product->delete($id);
    }
}
