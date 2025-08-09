<?php

namespace App\library;

class Cart
{
    private array $products = [];

    public function add(Product $product):void
    {
        $this->products[] = $product;
    }

    public function cart():array
    {
        return $this->products;
    }
}
