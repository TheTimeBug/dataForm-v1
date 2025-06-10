<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DataRecord;
use App\Models\DataEditHistory;

class DataRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $selectorOptions = [
            'selector_field_1' => ['Option A', 'Option B', 'Option C'],
            'selector_field_2' => ['Option X', 'Option Y', 'Option Z'],
            'selector_field_3' => ['Type 1', 'Type 2', 'Type 3'],
            'selector_field_4' => ['Category 1', 'Category 2', 'Category 3'],
        ];

        $comments = [
            'This is a sample comment for testing purposes',
            'Data entry completed successfully',
            'Updated information as requested',
            'Verified data accuracy',
            'Initial data submission',
            'Review and confirmation needed',
            'Quality check passed',
            'Information updated per guidelines',
            'Standard data entry process',
            'Final verification complete',
            'Data validation successful',
            'Regular maintenance update',
            'Routine data refresh',
            'System integration test',
            'Performance optimization data',
            'User interface improvement',
            'Database consistency check',
            'Security audit compliance',
            'Business requirements fulfillment',
            'Customer feedback integration'
        ];

        foreach ($users as $user) {
            echo "Generating data for user: {$user->name}\n";
            
            // Generate 120 random data records for each user
            for ($i = 1; $i <= 120; $i++) {
                $dataRecord = DataRecord::create([
                    'user_id' => $user->id,
                    'is_edit_request' => false,
                    'status' => 'active',
                    'integer_field_1' => rand(1, 1000),
                    'integer_field_2' => rand(1, 1000),
                    'integer_field_3' => rand(1, 1000),
                    'integer_field_4' => rand(1, 1000),
                    'selector_field_1' => $selectorOptions['selector_field_1'][array_rand($selectorOptions['selector_field_1'])],
                    'selector_field_2' => $selectorOptions['selector_field_2'][array_rand($selectorOptions['selector_field_2'])],
                    'selector_field_3' => $selectorOptions['selector_field_3'][array_rand($selectorOptions['selector_field_3'])],
                    'selector_field_4' => $selectorOptions['selector_field_4'][array_rand($selectorOptions['selector_field_4'])],
                    'comment_field_1' => $comments[array_rand($comments)],
                    'comment_field_2' => $comments[array_rand($comments)],
                    'created_at' => now()->subDays(rand(1, 365))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                ]);

                // Create edit history for creation
                DataEditHistory::create([
                    'data_record_id' => $dataRecord->id,
                    'field_name' => 'record_created',
                    'old_value' => null,
                    'new_value' => 'Record created',
                    'changed_by' => $user->id,
                    'action_type' => 'create',
                    'created_at' => $dataRecord->created_at,
                ]);

                // Randomly create some edit requests (about 10% of records)
                if (rand(1, 10) === 1) {
                    $admin = User::where('role', 'admin')->first();
                    if ($admin) {
                        $editRecord = DataRecord::create([
                            'user_id' => $user->id,
                            'parent_id' => $dataRecord->id,
                            'is_edit_request' => true,
                            'status' => 'pending',
                            'admin_id' => $admin->id,
                            'admin_notes' => 'Please review and update the data fields as needed. ' . $comments[array_rand($comments)],
                            'integer_field_1' => $dataRecord->integer_field_1,
                            'integer_field_2' => $dataRecord->integer_field_2,
                            'integer_field_3' => $dataRecord->integer_field_3,
                            'integer_field_4' => $dataRecord->integer_field_4,
                            'selector_field_1' => $dataRecord->selector_field_1,
                            'selector_field_2' => $dataRecord->selector_field_2,
                            'selector_field_3' => $dataRecord->selector_field_3,
                            'selector_field_4' => $dataRecord->selector_field_4,
                            'comment_field_1' => $dataRecord->comment_field_1,
                            'comment_field_2' => $dataRecord->comment_field_2,
                            'created_at' => $dataRecord->created_at->addDays(rand(1, 30)),
                        ]);

                        // Create edit history for edit request
                        DataEditHistory::create([
                            'data_record_id' => $editRecord->id,
                            'field_name' => 'edit_request_created',
                            'old_value' => null,
                            'new_value' => 'Edit request created by admin',
                            'changed_by' => $admin->id,
                            'action_type' => 'edit_request',
                            'created_at' => $editRecord->created_at,
                        ]);
                    }
                }

                if ($i % 20 === 0) {
                    echo "Generated {$i} records for {$user->name}\n";
                }
            }
        }

        echo "Data seeding completed!\n";
        echo "Generated records summary:\n";
        echo "- Total data records: " . DataRecord::where('is_edit_request', false)->count() . "\n";
        echo "- Total edit requests: " . DataRecord::where('is_edit_request', true)->count() . "\n";
        echo "- Total edit history entries: " . DataEditHistory::count() . "\n";
    }
}
