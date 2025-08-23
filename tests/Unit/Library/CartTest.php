<?php

namespace Tests\Unit\Library;

use App\Exceptions\CartExeption;
use App\Models\Cart;
use App\Models\Product;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Exceptions;

class CartTest extends TestCase
{
    use RefreshDatabase;
    public function test_if_tree_products_added(): void
    {
        $products = Product::factory()->count(3)->create();

        $cart = new Cart();
        $cart->add($products[0]);
        $cart->add($products[1]);

        $this->assertCount(expectedCount: 2, haystack: $cart->cart());
    }

    public function test_if_cart_is_full(): void
    {
        $this->expectException(CartExeption::class);
        $this->expectExceptionMessage('Cart is full');

        $products = Product::factory()->count(3)->create();

        $cart = new Cart();
        $cart->add($products[0]);
        $cart->add($products[1]);
        $cart->add($products[2]);
    }
}
