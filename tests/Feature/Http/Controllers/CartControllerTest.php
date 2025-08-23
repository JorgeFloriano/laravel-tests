<?php

namespace Tests\Feature;

use App\Exceptions\CartExeption;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Exceptions;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    // public function setUp(): void
    // {
    //     parent::setUp();
    //     dump('setup');  
    // }

    public function test_if_product_added_to_cart()
    {
        $product = Product::factory()->create();

        $response = $this->withSession(data:['test' => 'test'])->post(uri: '/cart/' . $product->id);

        $response->assertStatus(200);
        $response->assertSessionHas('cart');
        $this->assertCount(expectedCount:1, haystack:session('cart'));
    }

    public function test_if_handle_exception_cart_is_full()
    {
        Exceptions::fake();

        $products = Product::factory()->count(2)->create();

        $this->withSession(data:['cart' => $products])->post(uri: '/cart/' . $products[0]->id);

        Exceptions::assertReported(CartExeption::class);
        Exceptions::assertReported(function (CartExeption $exception) {
            return $exception->getMessage() === 'Cart is full';
        });
    }
}