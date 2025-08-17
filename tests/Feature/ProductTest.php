<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    public function test_if_product_inserted_is_instance_of_Model(): void
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(Product::class, actual:$product);
    }

    public function test_inserted_10_products(): void
    {
        Product::factory()->count(10)->create();

        $this->assertEquals(expected:10, actual:Product::count());
    }
}
