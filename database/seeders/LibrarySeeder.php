<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Mouza;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Divisions
        $dhaka = Division::create([
            'name' => 'Dhaka',
            'name_bn' => 'ঢাকা',
            'code' => 'DH',
            'description' => 'Dhaka Division is the most populous division of Bangladesh'
        ]);

        $chittagong = Division::create([
            'name' => 'Chittagong',
            'name_bn' => 'চট্টগ্রাম',
            'code' => 'CH',
            'description' => 'Chittagong Division is the largest division by area'
        ]);

        $rajshahi = Division::create([
            'name' => 'Rajshahi',
            'name_bn' => 'রাজশাহী',
            'code' => 'RJ',
            'description' => 'Rajshahi Division is located in the north-western part of Bangladesh'
        ]);

        $khulna = Division::create([
            'name' => 'Khulna',
            'name_bn' => 'খুলনা',
            'code' => 'KH',
            'description' => 'Khulna Division is located in the south-western part of Bangladesh'
        ]);

        // Create Districts for Dhaka Division
        $dhakaDistrict = District::create([
            'division_id' => $dhaka->id,
            'name' => 'Dhaka',
            'name_bn' => 'ঢাকা',
            'code' => 'DH-26',
            'description' => 'Capital district of Bangladesh'
        ]);

        $gazipur = District::create([
            'division_id' => $dhaka->id,
            'name' => 'Gazipur',
            'name_bn' => 'গাজীপুর',
            'code' => 'DH-33',
            'description' => 'Industrial district near Dhaka'
        ]);

        $narayanganj = District::create([
            'division_id' => $dhaka->id,
            'name' => 'Narayanganj',
            'name_bn' => 'নারায়ণগঞ্জ',
            'code' => 'DH-67',
            'description' => 'Industrial district known for textiles'
        ]);

        // Create Districts for Chittagong Division
        $chittagongDistrict = District::create([
            'division_id' => $chittagong->id,
            'name' => 'Chittagong',
            'name_bn' => 'চট্টগ্রাম',
            'code' => 'CH-15',
            'description' => 'Port city and commercial capital'
        ]);

        $coxsBazar = District::create([
            'division_id' => $chittagong->id,
            'name' => "Cox's Bazar",
            'name_bn' => 'কক্সবাজার',
            'code' => 'CH-22',
            'description' => 'Tourist destination with longest sea beach'
        ]);

        // Create Upazilas for Dhaka District
        $dhanmondi = Upazila::create([
            'district_id' => $dhakaDistrict->id,
            'name' => 'Dhanmondi',
            'name_bn' => 'ধানমন্ডি',
            'code' => 'DH26-10',
            'description' => 'Upscale residential area in Dhaka'
        ]);

        $gulshan = Upazila::create([
            'district_id' => $dhakaDistrict->id,
            'name' => 'Gulshan',
            'name_bn' => 'গুলশান',
            'code' => 'DH26-20',
            'description' => 'Diplomatic zone and business district'
        ]);

        $uttara = Upazila::create([
            'district_id' => $dhakaDistrict->id,
            'name' => 'Uttara',
            'name_bn' => 'উত্তরা',
            'code' => 'DH26-30',
            'description' => 'Planned residential area in northern Dhaka'
        ]);

        // Create Upazilas for Gazipur District
        $savar = Upazila::create([
            'district_id' => $gazipur->id,
            'name' => 'Savar',
            'name_bn' => 'সাভার',
            'code' => 'DH33-10',
            'description' => 'Industrial area with many garment factories'
        ]);

        $gazipurSadar = Upazila::create([
            'district_id' => $gazipur->id,
            'name' => 'Gazipur Sadar',
            'name_bn' => 'গাজীপুর সদর',
            'code' => 'DH33-20',
            'description' => 'Main administrative center of Gazipur'
        ]);

        // Create Mouzas for Dhanmondi
        Mouza::create([
            'upazila_id' => $dhanmondi->id,
            'name' => 'Dhanmondi Residential Area',
            'name_bn' => 'ধানমন্ডি আবাসিক এলাকা',
            'code' => 'DH2610-01',
            'description' => 'Main residential blocks of Dhanmondi'
        ]);

        Mouza::create([
            'upazila_id' => $dhanmondi->id,
            'name' => 'Dhanmondi Lake Area',
            'name_bn' => 'ধানমন্ডি লেক এলাকা',
            'code' => 'DH2610-02',
            'description' => 'Area around Dhanmondi Lake'
        ]);

        Mouza::create([
            'upazila_id' => $dhanmondi->id,
            'name' => 'Shankar',
            'name_bn' => 'শংকর',
            'code' => 'DH2610-03',
            'description' => 'Commercial area in Dhanmondi'
        ]);

        // Create Mouzas for Gulshan
        Mouza::create([
            'upazila_id' => $gulshan->id,
            'name' => 'Gulshan 1',
            'name_bn' => 'গুলশান ১',
            'code' => 'DH2620-01',
            'description' => 'Diplomatic zone with embassies'
        ]);

        Mouza::create([
            'upazila_id' => $gulshan->id,
            'name' => 'Gulshan 2',
            'name_bn' => 'গুলশান ২',
            'code' => 'DH2620-02',
            'description' => 'Commercial and business area'
        ]);

        Mouza::create([
            'upazila_id' => $gulshan->id,
            'name' => 'Banani',
            'name_bn' => 'বনানী',
            'code' => 'DH2620-03',
            'description' => 'Upscale residential and commercial area'
        ]);

        // Create Mouzas for Uttara
        Mouza::create([
            'upazila_id' => $uttara->id,
            'name' => 'Uttara Sector 1',
            'name_bn' => 'উত্তরা সেক্টর ১',
            'code' => 'DH2630-01',
            'description' => 'Planned residential sector'
        ]);

        Mouza::create([
            'upazila_id' => $uttara->id,
            'name' => 'Uttara Sector 7',
            'name_bn' => 'উত্তরা সেক্টর ৭',
            'code' => 'DH2630-02',
            'description' => 'Commercial and residential sector'
        ]);

        Mouza::create([
            'upazila_id' => $uttara->id,
            'name' => 'Airport Area',
            'name_bn' => 'বিমানবন্দর এলাকা',
            'code' => 'DH2630-03',
            'description' => 'Area near Hazrat Shahjalal International Airport'
        ]);

        // Create Mouzas for Savar
        Mouza::create([
            'upazila_id' => $savar->id,
            'name' => 'Savar Cantonment',
            'name_bn' => 'সাভার সেনানিবাস',
            'code' => 'DH3310-01',
            'description' => 'Military cantonment area'
        ]);

        Mouza::create([
            'upazila_id' => $savar->id,
            'name' => 'EPZ Area',
            'name_bn' => 'ইপিজেড এলাকা',
            'code' => 'DH3310-02',
            'description' => 'Export Processing Zone'
        ]);

        echo "Library data seeded successfully!\n";
        echo "Created:\n";
        echo "- " . Division::count() . " Divisions\n";
        echo "- " . District::count() . " Districts\n";
        echo "- " . Upazila::count() . " Upazilas\n";
        echo "- " . Mouza::count() . " Mouzas\n";
    }
}
