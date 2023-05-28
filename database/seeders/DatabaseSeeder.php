<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\LoanStatuses;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // seed roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // seed admin user
        $adminUser = User::create([
            "name" => "Prakalp",
            "email" => "aspireadmin@gmail.com",
            "password" => Hash::make("AspireAdmin@2023"),
            "role_id" => $adminRole->id
        ]);

        // seed statuses
        LoanStatuses::create(['name' => "pending"]);
        LoanStatuses::create(['name' => "approved"]);
        LoanStatuses::create(['name' => "paid"]);
    }
}
