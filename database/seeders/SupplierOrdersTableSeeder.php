<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupplierOrder;

class SupplierOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate 50 supplier orders
        SupplierOrder::factory()->count(50)->create();
    }
}
