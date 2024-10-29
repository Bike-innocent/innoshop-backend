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
            UsersTableSeeder::class,
            AddressesTableSeeder::class,
            UserAccountsTableSeeder::class,
            UserAddressesTableSeeder::class,
            ProductCategoriesTableSeeder::class,
            RolesAndPermissionsSeeder::class,
            AdminSeeder::class,
           


        ]);
    }
}
