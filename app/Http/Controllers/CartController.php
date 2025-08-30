<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Product $product, Cart $cart): void
    {
        if (!$cart->isFull()) {
            $cart->add($product);
        }
    }
}
