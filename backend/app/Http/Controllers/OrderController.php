<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;
use Inertia\Ssr\Response;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function show(Order $order): Response
    {
        $this->authorize('view', $order);

        $order->load('items.product');

        return Inertia::render('orders/show', [
            'order' => $order,
        ]);
    }
}
