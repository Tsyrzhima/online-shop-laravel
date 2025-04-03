<?php

namespace App\Services;

use App\Http\Requests\AddProductToCartRequest;
use App\Http\Requests\DecreaseProductFromCartRequest;
use App\Models\UserProduct;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function addProduct(AddProductToCartRequest $request): int
    {
        $userId = Auth::id();
        $product = UserProduct::where([
            'user_id' => $userId,
            'product_id' => $request->get('product_id')
        ])->first();
        if ($product) {
            $amount = $product->amount + $request->get('amount');
            $product->amount = $amount;
            $product->save();
        }else{
            $amount = $request->get('amount');
            UserProduct::query()->create([
                'user_id' => $userId,
                'product_id' => $request->get('product_id'),
                'amount' => $amount
            ]);
        }
        return $amount;
    }
    public function decreaseProduct(DecreaseProductFromCartRequest $request): int
    {
        $userId = Auth::id();
        $product = UserProduct::where([
            'user_id' => $userId,
            'product_id' => $request->get('product_id')
            ])->first();
        if ($product) {
            if($product->amount > 1){
                $amount = $product->amount - 1;
                $product->amount = $amount;
                $product->save();
            }elseif($product->amount === 1){
                $amount = 0;
                $product->delete();
            }
        }else{
            $amount = 0;
        }
        return $amount;
    }

}
