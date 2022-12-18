<?php

namespace App\Http\Controllers;

use App\Contracts\ProductRepositoryInterface;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        $this->authorize('viewAny',Product::class);

        $products = $this->product->all();
        $title = 'Products';
        return Inertia::render(
            'products/index',
            [
                'products' => $products,
                'title' => $title,
                'singular_title' => Str::singular($title),
                'CanCreate' => Gate::allows('create',Product::class),
                'CanEdit' => Gate::allows('modify',Product::class),
                'CanDelete' => Gate::allows('delete',Product::class)
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
        $this->authorize('create',Product::class);

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

        $this->authorize('viewAny',$product);

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
    public function create()
    {
        $title = 'Create Product';
        $this->authorize('create',Product::class);
        return Inertia::render(
            'products/create',
            [
                'title' => $title,
            ]
        );
        return "Create";
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update',$product);

        $product->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('update',$user);

        $this->product->delete($user->id);

    }
}
