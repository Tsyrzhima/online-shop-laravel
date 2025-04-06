<?php

namespace App\Services;

use App\Http\Requests\HandleCheckoutOrderRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\UserProduct;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;

class OrderService
{
    private CartService $cartService;
    public function __construct()
    {
        $this->cartService = new CartService();
    }
    public function create(HandleCheckoutOrderRequest $request)
    {
        $data = $request->all();
        $sum = $this->cartService->getSum();
        /** @var User $user */
        $user = Auth::user();
        $userProducts = $user->userProducts()->get();
        $order = Order::create([
            'user_id' => $user->id,
            'contact_name' => $data['contact_name'],
            'contact_phone' => $data['contact_phone'],
            'comment' => $data['comment'],
            'address' => $data['address'],
        ]);
        $orderId = $order->id;
        foreach ($userProducts as $userProduct) {
            OrderProduct::create([
                'order_id' => $orderId,
                'product_id' => $userProduct->product_id,
                'amount' => $userProduct->amount,
            ]);
        }
        UserProduct::query()->where('user_id', $user->id)->delete();
    }

    public function getAll()
    {
        $user = Auth::user();

        $orders = $user->orders()->with('products')->get();

        //$orders = Order::with(['orderProducts.product'])->where('user_id', $user->id)->get();

        foreach ($orders as $order) {
            $totalSum = 0;
            foreach ($order->orderProducts as $orderProduct) {
                $orderProduct->setAttribute('Product', $orderProduct->product);
                $sum = $orderProduct->amount * $orderProduct->product->price;
                $orderProduct->setAttribute('sum', $sum);
                $totalSum += $sum;
            }
            $order->sum = $totalSum;
        }

        return $orders;
    }
}
