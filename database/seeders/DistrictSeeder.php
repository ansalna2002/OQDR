<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            ['name' => 'Ariyalur'],
            ['name' => 'Chengalpattu'],
            ['name' => 'Chennai'],
            ['name' => 'Coimbatore'],
            ['name' => 'Cuddalore'],
            ['name' => 'Dharmapuri'],
            ['name' => 'Dindigul'],
            ['name' => 'Erode'],
            ['name' => 'Kallakurichi'],
            ['name' => 'Kancheepuram'],
            ['name' => 'Karur'],
            ['name' => 'Krishnagiri'],
            ['name' => 'Madurai'],
            ['name' => 'Mayiladuthurai'],
            ['name' => 'Nagapattinam'],
            ['name' => 'Kanniyakumari'],
            ['name' => 'Namakkal'],
            ['name' => 'Perambalur'],
            ['name' => 'Pudukkottai'],
            ['name' => 'Ramanathapuram'],
            ['name' => 'Ranipet'],
            ['name' => 'Salem'],
            ['name' => 'Sivaganga'],
            ['name' => 'Tenkasi'],
            ['name' => 'Thanjavur'],
            ['name' => 'Theni'],
            ['name' => 'Thoothukudi'],
            ['name' => 'Tiruchirappalli'],
            ['name' => 'Tirunelveli'],
            ['name' => 'Tirupattur'],
            ['name' => 'Tiruppur'],
            ['name' => 'Tiruvallur'],
            ['name' => 'Tiruvannamalai'],
            ['name' => 'Tiruvarur'],
            ['name' => 'Vellore'],
            ['name' => 'Viluppuram'],
            ['name' => 'Virudhunagar']
        ];

        DB::table('districts')->insert($districts);
    }
}