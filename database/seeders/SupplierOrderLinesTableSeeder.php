<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupplierOrderLine;

class SupplierOrderLinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate 100 supplier order lines
        SupplierOrderLine::factory()->count(100)->create();
    }
}
