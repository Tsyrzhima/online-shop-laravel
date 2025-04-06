<?php

namespace App\Http\Controllers;

use App\Http\Requests\HandleCheckoutOrderRequest;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController
{
    private $cartService;
    private $orderService;
    public function __construct()
    {
        $this->cartService = new CartService();
        $this->orderService = new OrderService();
    }
    public function getCheckoutForm()
    {
        /** @var User $user */
        $user = Auth::user();
        $userProducts = $this->cartService->getUserProducts();
        if(empty($userProducts))
        {
            return response()->redirectTo('/catalog');
        }
        $total = $this->cartService->getSum();
        return view('orderForm', compact('user', 'userProducts', 'total'));
    }
    public function handleCheckout(HandleCheckoutOrderRequest $request)
    {
        $this->orderService->create($request);
        return response()->redirectTo('/user-orders');
    }

    public function getAll()
    {
        $userOrders = $this->orderService->getAll();
        return view('userOrders', compact('userOrders'));
    }
}
