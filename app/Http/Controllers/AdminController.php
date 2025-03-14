<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\District;
use App\Models\Folder;
use App\Models\Support;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }
    public function login_otp($email = "")
    {
        return view('admin.login_otp', compact('email'));
    }
    public function forget_password()
    {
        return view('admin.forget_password');
    }
    public function dashboard()
    {
        $totalUsers      = User::where('role', 'user')->count();
        $freeUsers       = User::where('role', 'user')->where('is_subscribed', 0)->count();
        $membershipUsers = User::where('role', 'user')->where('is_subscribed', 1)->count();
        return view('admin.dashboard', compact('totalUsers', 'freeUsers', 'membershipUsers'));
    }
    public function user_management()
    {
        $users = User::withCount([
            'files' => function ($query) {
                $query->where('type', 'file');
            }
        ])
        ->where('role', 'user')
        ->get();
    
        return view('admin.user_management', compact('users'));
    }
    
    
    public function reset_password()
    {
        return view('admin.reset_password');
    }
    public function user_approval()
    {
        $users = User::where('role', 'user')
            ->where('status', 'pending')
            ->get();
        return view('admin.user_approval', compact('users', ));
    }
    public function membership_approval()
    {
        $users = UserSubscription::with('user')
            ->where('status', 'requested')
            ->paginate(10);

        return view('admin.membership_approval', compact('users'));
    }
    public function membership_history()
    {
        $users = UserSubscription::with('user')
            ->whereIn('status', ['accepted', 'rejected'])
            ->latest()
            ->paginate(10);

        return view('admin.membership_history', compact('users'));
    }
    public function add_ad()
    {
        $districts = District::all();
        return view('admin.ad', compact('districts'));
    }
    public function ad_history()
    {
        $ads = Ad::all();
        return view('admin.ad_history', compact('ads'));
    }
    
    public function support()
{
    $supports = Support::with('user')->get();
    return view('admin.support', compact('supports'));
}


}
