<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data               = new User();
        $data->name         = "admin";
        $data->user_id      = "Admin001";
        $data->email        = "admin@gmail.com";
        $data->is_active    = "1";
        $data->role         = "admin";
        $data->password      = Hash::make('admin');
        $data->save();
    }
}
