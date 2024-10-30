<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Supplier;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupplierOrder>
 */
class SupplierOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            'order_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['pending', 'received', 'cancelled']),
        ];
    }
}