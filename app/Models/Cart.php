<?php

namespace App\Models;

use App\Exceptions\CartExeption;

class Cart
{
    private array $products = [];

    public function add(Product $product):void
    {
        if (session()->has('cart') && count(session('cart')) >= 2) {
            throw new CartExeption('Cart is full');
        }

        //$this->products[] = $product;
        session()->push('cart', $product);
    }

    public function cart():array
    {
        //return $this->products;
        return session(key:'cart');
    }
}
