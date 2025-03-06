<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\URL;
use App\Models\Tax;

class CartController extends Controller
{

    //GET, CARTS MAIN PAGE
    public function cartIndex()
    {
        // Check user auth. must be a middleware for bigger software
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Fetch user's cart
        $cart = Cart::where('user_id', $user->id)
                    ->with('product')
                    ->get()
                    ->filter(fn($item) => $item->product !== null);

        // Fetch the active tax
        $tax = Tax::where('is_active', true)->first();

        return view('cart.index', compact('cart', 'tax'));
    }


    // Add to Cart
    public function addCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Custom validation
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0'
        ]);

        $product = Product::find($validated['product_id']);
        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $validated['product_id'])->first();

        // If product exists in cart
        if ($cartItem) {

            $newQuantity = $cartItem->quantity + $validated['quantity'];

            // Prevent exceeding stock qty
            if ($newQuantity > $product->stock) {
                return response()->json(['error' => 'Not enough stock available'], 400);
            }

            // update existing cart
            $cartItem->update(['quantity' => $newQuantity]);

        } else {

            // Add a new cart entry
            if ($validated['quantity'] > $product->stock) {
                return response()->json(['error' => 'Not enough stock'], 400);
            }

            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price' => $validated['price']
            ]);
        }

        // Fetch updated cart
        $updatedCart = Cart::where('user_id', Auth::id())
                            ->with('product')
                            ->get()
                            ->map(function ($item) {
                                $item->product->image_url = asset($item->product->image_url);
                                return $item;
                            });

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'cart' => $updatedCart
        ]);
    }


    // Remove Item From Cart
    public function removeCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $validated = $request->validate([
            'product_id' => 'required|integer|exists:carts,product_id'
        ]);

        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $validated['product_id'])
                    ->first();

        if (!$cart) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        $cart->delete();

        // Fetch the updated cart
        $updatedCart = Cart::where('user_id', Auth::id())
                            ->with('product') // Load product details
                            ->get()
                            ->map(function ($item) {
                                $item->product->image_url = asset($item->product->image_url);
                                return $item;
                            });

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart' => $updatedCart
        ]);
    }

    // Update the existing item in the cart
    public function updateCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $validated = $request->validate([
            'product_id' => 'required|integer|exists:carts,product_id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $validated['product_id'])
                    ->first();

        if (!$cart) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        // Get product
        $product = Product::find($validated['product_id']);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Ensure new qty does not exceed stock
        if ($validated['quantity'] > $product->stock) {
            return response()->json(['error' => 'Not enough stock'], 400);
        }

        // Update the cart with the new qty
        $cart->update(['quantity' => $validated['quantity']]);

        // Fetch updated cart
        $updatedCart = Cart::where('user_id', Auth::id())
                            ->with('product')
                            ->get()
                            ->map(function ($item) {
                                $item->product->image_url = asset($item->product->image_url);
                                return $item;
                            });

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'cart' => $updatedCart
        ]);
    }


}
