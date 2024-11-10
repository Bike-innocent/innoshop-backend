<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        // Ensure the 'supplier' role exists
        $supplierRole = Role::firstOrCreate(['name' => 'supplier']);

        // Get 10 users randomly
        $users = User::inRandomOrder()->limit(10)->get();

        // Assign the 'supplier' role to each of these users
        foreach ($users as $user) {
            $user->assignRole($supplierRole);
        }

        // Output a success message
        $this->command->info('Successfully assigned the supplier role to 10 users.');
    }
}

