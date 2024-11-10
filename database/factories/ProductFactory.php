<?php

// namespace Database\Factories;

// use Illuminate\Database\Eloquent\Factories\Factory;
// use App\Models\ProductCategory;
// use App\Models\Brand;
// use App\Models\Colour;
// use App\Models\Size;
// use App\Models\Supplier;
// use Illuminate\Support\Str;

// /**
//  * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
//  */
// class ProductFactory extends Factory
// {
//     /**
//      * Define the model's default state.
//      *
//      * @return array<string, mixed>
//      */
//     public function definition(): array
//     {
//         return [
//             'name' => $this->faker->word,
//             'slug' => Str::random(10),
//             'category_id' => ProductCategory::factory(),
//             'brand_id' => Brand::factory(),
//             'colour_id' => Colour::factory(),
//             'size_id' => Size::factory(),
//             'supplier_id' => Supplier::factory(),
//             'description' => $this->faker->paragraph,
//             'price' => $this->faker->randomFloat(2, 10, 1000),
//             'stock_quantity' => $this->faker->numberBetween(1, 100),
//         ];
//     }
// }




namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductCategory;
use App\Models\Brand;
use App\Models\Colour;
use App\Models\Size;
use App\Models\User; // Import the User model
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Fetch a random user with the "supplier" role
        $supplier = User::role('supplier')->inRandomOrder()->first();

        return [
            'name' => $this->faker->word,
            'slug' => Str::random(10),
            'category_id' => ProductCategory::factory(),
            'brand_id' => Brand::factory(),
            'colour_id' => Colour::factory(),
            'size_id' => Size::factory(),
            'supplier_id' => $supplier ? $supplier->id : null, // Use supplier user's ID
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'stock_quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
