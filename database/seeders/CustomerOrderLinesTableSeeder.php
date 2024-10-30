<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerOrderLine;

class CustomerOrderLinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate 100 order lines
        CustomerOrderLine::factory()->count(100)->create();
    }
}
