<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProduct;
use App\Http\Requests\AddProductToCartRequest;
use App\Http\Requests\DecreaseProductFromCartRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;

class CartController
{
    private CartService $cartService;
    public function __construct()
    {
        $this->cartService = new CartService();
    }
    public function getCart()
    {
        /** @var User $user */
        $user = Auth::user();
        $userProducts = $user->userProducts()->get();
        return view('cart', compact('userProducts'));
    }
    public function addProductToCart(AddProductToCartRequest $request)
    {
        $amount = $this->cartService->addProduct($request);
        $result = [
            'amount' => $amount
        ];
        echo json_encode($result);
    }
    public function decreaseProductFromCart(DecreaseProductFromCartRequest $request)
    {
        $amount = $this->cartService->decreaseProduct($request);
        $result = [
            'amount' => $amount
        ];
        echo json_encode($result);
    }

}
