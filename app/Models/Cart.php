<?php

namespace App\Models;

class Cart
{
    private array $products = [];

    public function add(Product $product):void
    {
        //$this->products[] = $product;
        session()->push('cart', $product);
    }

    public function cart():array
    {
        //return $this->products;
        return session(key:'cart');
    }
}
