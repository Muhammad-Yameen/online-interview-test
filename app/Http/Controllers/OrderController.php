<?php

namespace App\Http\Controllers;

use App\Contracts\OrderRepositoryInterface;
use App\Contracts\ProductRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Events\CreateOrderEvent;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public OrderRepositoryInterface $order;
    public UserRepositoryInterface $user;
    public ProductRepositoryInterface $product;

    public function __construct(OrderRepositoryInterface $order, UserRepositoryInterface $user, ProductRepositoryInterface $product)
    {
        $this->order = $order;
        $this->user = $user;
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Orders';
        $orders = $this->order->all();
        $users = $this->user->all();
        $products = $this->product->all();
        return Inertia::render('orders/index', [
            'orders' => $orders,
            'title' => $title,
            'users' => $users,
            'products' => $products,
            'singular_title' => Str::singular($title),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = $this->order->find($id);
        $title = 'Show Order';

        return Inertia::render(
            'orders/show',
            [
                'order' => $order,
                'transactions' => $order->transactions()->get(),
                'title' => $title,
            ]
        );
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        $id = $request->user_id;
        DB::beginTransaction();
        try {
            $user = User::find($id);
            $order = $user->orders()->create([
                'shipping_address' => $request->shipping_address,
                'status' => $request->status,
                'details' => Str::random(16),
            ]);
            $products =  $request->products;

            foreach ($products as $key => $product) {
                $order->order_items()->create([
                    'product_id' => $product['id'],
                    'quantity' => 1,
                    'price' => $product['price'],
                ]);
            }
            if ($order && $request->status == 'paid') {
                $order->transactions()->create([
                    'transaction_number' => Str::random(16),
                    'payment_by' => 'Paypal',
                    'amount' => $order->order_total
                ]);
            }
            event(new CreateOrderEvent($order->load('user')));

            Db::commit();
        } catch (\Throwable $th) {
            throw $th;
            Db::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {

        Order::where('id', $id)->update([
            'status' => $request->status
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->all());
        DB::beginTransaction();
        try {
            $products =  $request->products;
            $order->order_items()->delete();
            foreach ($products as $product) {
                $order->order_items()->create([
                    'product_id' => $product['id'],
                    'quantity' => 1,
                    'price' => $product['price'],
                ]);
            }
            if ($order && $request->status == 'paid') {
                $order->transactions()->updateOrCreate([
                    'order_id' => $order->id
                ], [
                    'transaction_number' => Str::random(16),
                    'payment_by' => 'Paypal',
                    'amount' => $order->order_total
                ]);
            }
            event(new CreateOrderEvent($order->load('user')));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->order->delete($id);
    }
}
