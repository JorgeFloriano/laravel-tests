<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    public function test_if_product_added_to_cart_2()
    {
        dump(session()->all());
        $product = Product::factory()->create();

        $response = $this->withSession(data:['test' => 'test'])->post(uri: '/cart/' . $product->id);

        $response->assertStatus(200);
        $response->assertSessionHas('cart');
        $this->assertCount(expectedCount:1, haystack:session('cart'));
    }
}