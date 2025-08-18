<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    //protected $seed = true;
    public function test_if_product_inserted_is_instance_of_Model(): void
    {
        $product = Product::factory()->create([
            'name' => 'product 01',
            'description' => 'Product 01 Description',
            'price' => 100,
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'product 01',
        ]);

        $this->assertInstanceOf(Product::class, actual: $product);
    }

    public function test_inserted_10_products(): void
    {
        Product::factory()->count(10)->create();

        //$this->seed();

        //$this->assertEquals(expected:10, actual:Product::count());

        //$this->assertDatabaseEmpty(table:'products');

        $this->assertDatabaseCount(table:'products', count:10);

    }
}
