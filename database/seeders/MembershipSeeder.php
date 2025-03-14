<?php

namespace Database\Seeders;

use App\Models\Membership;
use Carbon\Month;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data                = new Membership();
        $data->membership_id = "MEMZ001";
        $data->title         = "Basic";
        $data->amount        = 399;
        $data->days          = "30 times";
        $data->benefit       = "Enjoy premium benefits for a month with full access to exclusive features.";
        $data->is_active     = "1";
        $data->remark        = "Membership";
        $data->save();

        $data                = new Membership();
        $data->membership_id = "MEMZ002";
        $data->title         = "Popular";
        $data->amount        = 899;
        $data->days          = "60 times";
        $data->benefit       = "A cost-effective option for extended access and a seamless experience.";
        $data->is_active     = "1";
        $data->remark        = "Membership";
        $data->save();

        $data                = new Membership();
        $data->membership_id = "MEMZ003";
        $data->title         = "Premium";
        $data->amount        = 1999;
        $data->days          = "90 times";
        $data->benefit       = "The best value plan for uninterrupted access and maximum savings.";
        $data->is_active     = "1";
        $data->remark        = "Membership";
        $data->save();

        
    }
}
