<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminHierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Test admin users for hierarchy testing
        $testAdmins = [
            [
                'name' => 'National Admin',
                'email' => 'national@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'admin_type' => 'national',
                'status' => 'active',
            ],
            [
                'name' => 'Dhaka Division Admin',
                'email' => 'dhaka.division@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'admin_type' => 'divisional',
                'division_id' => 1, // Assuming Dhaka division has ID 1
                'status' => 'active',
            ],
            [
                'name' => 'Dhaka District Admin',
                'email' => 'dhaka.district@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'admin_type' => 'district',
                'division_id' => 1,
                'district_id' => 1, // Assuming Dhaka district has ID 1
                'status' => 'active',
            ],
            [
                'name' => 'Dhanmondi Upazila Admin',
                'email' => 'dhanmondi.upazila@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'admin_type' => 'upazila',
                'division_id' => 1,
                'district_id' => 1,
                'upazila_id' => 1, // Assuming Dhanmondi upazila has ID 1
                'status' => 'active',
            ],
        ];

        foreach ($testAdmins as $adminData) {
            if (!User::where('email', $adminData['email'])->exists()) {
                User::create($adminData);
                echo "Created admin: {$adminData['name']} ({$adminData['email']})\n";
            } else {
                echo "Admin already exists: {$adminData['email']}\n";
            }
        }

        echo "\nTest admin credentials:\n";
        echo "SuperAdmin: superadmin@example.com / superadmin123\n";
        echo "National: national@example.com / admin123\n";
        echo "Divisional: dhaka.division@example.com / admin123\n";
        echo "District: dhaka.district@example.com / admin123\n";
        echo "Upazila: dhanmondi.upazila@example.com / admin123\n";
    }
} 