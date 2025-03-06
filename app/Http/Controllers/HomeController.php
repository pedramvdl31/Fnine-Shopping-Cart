<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;

class HomeController extends Controller
{

    // GET, Homepage where we can see a catalog of the items
    public function index(Request $request)
    {

        // Manually logs in the user (User id = 1) since we don't have the user auth yet
        $user = User::first(); 
        Auth::login($user, true);

        // Fetch user's cart
        $cart = Cart::where('user_id', $user->id)
                    ->with('product')
                    ->get()
                    ->filter(function ($item) {
                        return $item->product !== null; // Remove cart where product is missing
                    });

        $products = Product::paginate(9);
        
        return view('index', compact('products', 'cart'));
    }

}
