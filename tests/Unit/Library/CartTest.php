<?php

namespace Tests\Unit\Library;

use App\Models\Cart;
use App\Models\Product;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;
    public function test_if_tree_products_added(): void
    {
        $products = Product::factory()->count(3)->create();

        $cart = new Cart();
        $cart->add($products[0]);
        $cart->add($products[1]);
        $cart->add($products[2]);

        $this->assertCount(expectedCount:3, haystack:$cart->cart());
    }
}
