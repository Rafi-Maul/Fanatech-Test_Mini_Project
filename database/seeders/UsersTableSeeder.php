<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Super Admin', 'password' => Hash::make('superadmin123'), 'role' => 'SuperAdmin'],
            ['name' => 'Sales', 'password' => Hash::make('sales123'), 'role' => 'Sales'],
            ['name' => 'Purchase', 'password' => Hash::make('purchase123'), 'role' => 'Purchase'],
            ['name' => 'Manager', 'password' => Hash::make('manager123'), 'role' => 'Manager'],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
