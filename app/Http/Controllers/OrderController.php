<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function addToCart(Product $product, Request $request)
    {
        $user_id = Auth::id();
        $product_id = $product->id;

        $existing_cart = Cart::where('product_id', $product_id)
            ->where('user_id', $user_id)
            ->first();
        
        // jika cart kosong(null)
        if ($existing_cart == null) {
            // amount harus lebih besar dari 1
            // dan amount harus lebih kecil dari jumlah stok produk
            $request->validate([
                'amount' => 'required|gte:1|lte:' . $product->stock, 
            ]);

            Cart::create([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'amount' => $request->amount,
            ]);
        } else {
            // jika cart sudah ada
            $request->validate([
                'amount' => 'required|gte:1|lte:' . ($product->stock - $existing_cart->amount),
            ]);

            $existing_cart->update([
                'amount' => $existing_cart->amount + $request->amount,
            ]);
        }

        return response()->json(['message' => 'Berhasil memasukan ke keranjang'], 201);
    }

    public function checkout($id)
    {
        $cartId = Cart::find($id);
        $user_id = Auth::id();
        $carts = Cart::where('user_id', $user_id)->get();

        // jika keranjang kosong
        if ($carts == null) {
            return response()->json([
                'message' => 'Keranjang kosong'
            ]);
        }

        $order = Order::create([
            'user_id' => $user_id
        ]);

        // untuk setiap produk di keranjang
        foreach ($carts as $cart) {
            $product = Product::find($cart->product_id);
            $product->update([
                'stock' => $product->stock - $cart->amount
            ]);

            Transaction::create([
                'amount' => $cart->amount,
                'product_id' => $cart->product_id,
                'order_id' => $order->id
            ]);

            $cart->delete();
        }

        return response()->json([
            'message' => 'Checkout Berhasil'
        ], 200);
    }

    public function submitReceipPayment(Order $order, Request $request)
    {
        $file = $request->file('payment_receipt');
        // menentukan path/nama file beserta extensionnya
        $path = time() . '_' . $order->id . '.' . $file->getClientOriginalExtension();

        // menentukan lokasi penyimpanan file
        Storage::disk('local')->put('public/' . $path, file_get_contents($file));

        $order->update([
            'payment_receipt' => $path
        ]);

        return response()->json([
            'message' => 'Bukti Pembayaran berhasil di upload'
        ], 200);
    }

    public function confirm_payment(Order $order)
    {
        $order->update([
            'is_paid' => true,
        ]);

        return response()->json([
            'message' => 'Pembayaran berhasil disetujui'
        ]);
    }
}
