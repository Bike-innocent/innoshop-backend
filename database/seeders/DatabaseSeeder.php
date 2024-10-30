<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([

            RolesAndPermissionsSeeder::class,
            AdminSeeder::class,


            UsersTableSeeder::class,
            AddressesTableSeeder::class,
            UserAccountsTableSeeder::class,
            UserAddressesTableSeeder::class,
            ProductCategoriesTableSeeder::class,
            BrandsTableSeeder::class,
            ColoursTableSeeder::class,
            SizesTableSeeder::class,
            SuppliersTableSeeder::class,
            ProductsTableSeeder::class,
            CustomerOrdersTableSeeder::class,
            CustomerOrderLinesTableSeeder::class,
            SupplierOrdersTableSeeder::class,
            SupplierOrderLinesTableSeeder::class,








        ]);
    }
}
