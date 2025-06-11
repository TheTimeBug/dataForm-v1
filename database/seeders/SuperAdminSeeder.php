<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create superadmin user if doesn't exist
        if (!User::where('email', 'superadmin@example.com')->exists()) {
            User::create([
                'name' => 'Super Administrator',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('superadmin123'),
                'role' => 'admin',
                'admin_type' => 'superadmin',
                'status' => 'active',
            ]);
            
            echo "SuperAdmin user created successfully!\n";
            echo "Email: superadmin@example.com\n";
            echo "Password: superadmin123\n";
        } else {
            echo "SuperAdmin user already exists.\n";
        }
    }
} 