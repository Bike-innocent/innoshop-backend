<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerOrder;

class CustomerOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 30 customer orders
        CustomerOrder::factory()->count(30)->create();
    }
}
