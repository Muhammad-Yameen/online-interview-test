<?php

namespace App\Http\Controllers;

use App\Events\TransactionStatusUpdateEvent;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Order;
use App\Models\Transaction;
use Inertia\Inertia;
use Illuminate\Support\Str;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::with('order')->orderBy('id','desc')->get();
        $orders = Order::where('status','unpaid')->orderBy('id','desc')->get();

        $title = 'Transactions';
        return Inertia::render(
            'transactions/index',
            [
                'transactions' => $transactions,
                'title' => $title,
                'orders' => $orders,
                'singular_title' => Str::singular($title),
            ]
            );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request)
    {
        $order = Order::find($request->order_id);
        Order::where('id',$order->id)->update(['status'=>'paid']);
        if ($order && $request->status == 'paid') {
            $order->transactions()->updateOrCreate([
                'order_id' => $order->id
            ],[
                'transaction_number' => Str::random(16),
                'payment_by' => $request->payment_by,
                'amount' => $order->order_total
            ]);
        }
        event(new TransactionStatusUpdateEvent($order->load(['user','transactions'])));
    }
}
