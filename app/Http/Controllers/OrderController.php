<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Product $product, Request $request)
    {
        $user_id = Auth::id();
        $product_id = $product->id;

        $existing_cart = Cart::where('product_id', $product_id)
            ->where('user_id', $user_id)
            ->first();
        
        if ($existing_cart == null) {
            $request->validate([
                'amount' => 'required|gte:1|lte:' . $product->stock, 
            ]);

            Cart::create([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'amount' => $request->amount,
            ]);
        } else {
            $request->validate([
                'amount' => 'required|gte:1|lte:' . ($product->stock - $existing_cart->amount),
            ]);

            $existing_cart->update([
                'amount' => $existing_cart->amount + $request->amount,
            ]);
        }

        return response()->json(['message' => 'Berhasil memasukan ke keranjang'], 201);
    }
}
