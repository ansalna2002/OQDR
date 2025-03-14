<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Loginotp;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    
    public function login_post(Request $request)
    {
    try {
        Log::info('Login Attempt:', ['email' => $request->email]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            Log::info('Validation Errors:', ['errors' => $validator->errors()->all()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = User::where('email', $request->email)
            ->where('role', 'admin')
            ->first();
        if (!$user) {
            Log::warning('Login Failed: User not found.');
            return back()->withErrors(['email' => 'Invalid email or password.']);
        }
        if (!Hash::check($request->password, $user->password)) {
            Log::warning('Login Failed: Incorrect password.');
            return back()->withErrors(['email' => 'Invalid email or password.']);
        }
        if (!isset($user->is_active) || !$user->is_active || !$user->isAdmin()) {
            Log::warning('Login Failed: Account inactive or not admin.');
            return redirect()->route('admin.login')->withErrors(['email' => 'Your account is inactive or you are not an admin.']);
        }

        // $otp = rand(100000, 999999); 
        $otp ='123456';
        $user->otp = $otp;
        $user->otp_expired_at = now()->addMinutes(1); 
        $user->save();

        Mail::to($user->email)->send(new Loginotp($otp));
        Log::info('OTP Sent Successfully:', ['email' => $user->email, 'otp' => $otp]);
        Auth::guard('admin')->login($user);
        return redirect()->route('login_otp', ['email' => base64_encode($request->email)])
                         ->with('success', 'OTP sent successfully.');
    } catch (Exception $e) {
        Log::error('Login Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return back()->withErrors(['email' => 'An unexpected error occurred. Please try again later.']);
    }
}
public function login_verifyotp(Request $request)
{
    try {
        $decodedEmail = base64_decode($request->email);

        $request->validate([
            'otp' => 'required|string|min:6|max:6',
        ]);

        $user = User::where('email', $decodedEmail)->where('role', 'admin')->first();

        if (!$user) {
            Log::error('User not found for OTP verification', ['email' => $decodedEmail]);
            return redirect()->back()->with('error', 'Invalid User');
        }

        if (!$user->otp || now()->gt($user->otp_expired_at)) {
            return redirect()->back()->with('error', 'The OTP has expired. Please request a new one.');
        }

        if ($user->otp !== $request->otp) {
            Log::warning('Invalid OTP entered', ['user_id' => $user->id, 'email' => $decodedEmail, 'entered_otp' => $request->otp]);
            return redirect()->back()->with('error', 'The verification code is incorrect.');
        }

        $user->otp = null;
        $user->otp_expired_at = null;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'OTP Verified successfully.');
    } catch (\Exception $e) {
        Log::error('OTP Verification Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return redirect()->back()->with('error', 'Something went wrong! Please try again.');
    }
}


public function login_resend_otp(Request $request)
{
    try {
        $decodedEmail = base64_decode($request->email);
        $user = User::where('email', $decodedEmail)->where('role', 'admin')->first();

        if (!$user) {
            Log::error('Resend OTP Failed: User not found', ['email' => $decodedEmail]);
            return redirect()->back()->with('error', 'Invalid User');
        }

        if ($user->otp && now()->lt($user->otp_expired_at)) {
            return redirect()->back()->with('error', 'The previous OTP is still valid. Please use it before requesting a new one.');
        }

        $otp = '123456'; 
        $user->otp = $otp;
        $user->otp_expired_at = now()->addMinutes(1);
        $user->save();

        Mail::to($user->email)->send(new Loginotp($otp));

        Log::info('OTP Resent Successfully:', ['email' => $user->email, 'otp' => $otp]);

        return redirect()->route('login_otp', ['email' => base64_encode($decodedEmail)])
                         ->with('success', 'A new OTP has been sent to your email.');
    } catch (\Exception $e) {
        Log::error('OTP Resend Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return redirect()->back()->with('error', 'Something went wrong! Please try again.');
    }
}


public function delete_user($id)
{
    try {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found!');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully!');
    } catch (\Exception $e) {
        Log::error('Error in deleting user: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Something went wrong! Please try again.');
    }
}

public function user_status_update(Request $request, $id)
{
    Log::info($request->all());
    try {
        $user = User::find($id);
        Log::info($user);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found!');
        }

        $user->status = $request->input('status');
        $user->save();
        Log::info($user);
        return redirect()->back()->with('success', 'User status updated successfully!');
    } catch (\Exception $e) {
        Log::error('Error in updating user status: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Something went wrong! Please try again.');
    }
}


public function reset_password_handle(Request $request)
{
    try {
       
        $validator = Validator::make($request->all(), [
            'password'             => ['required', 'string', 'min:5'],
            'new_pwd'              => ['required', 'string', 'min:5', 'confirmed'],
            'new_pwd_confirmation' => ['required', 'string', 'min:5'],
        ]);

        if ($validator->fails()) {
            $errors = implode(', ', $validator->errors()->all()); 
            return redirect()->back()->with('error', $errors)->withInput();
        }

        $user = Auth::guard('admin')->user();

        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()->back()
                ->withErrors(['password' => 'The old password is incorrect.'])
                ->withInput();
        }

        $user->password = Hash::make($request->input('new_pwd'));
        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Password has been updated successfully.');
    } catch (\Exception $e) {
        Log::error('Password reset error: ' . $e->getMessage());
        return redirect()->back()
            ->withErrors(['error' => 'An unexpected error occurred while updating the password. Please try again later.'])
            ->withInput();
    }
}

public function admin_logout(Request $request)
    {
        try {
            Auth::guard('admin')->logout();
            $request->session()->forget('admin_guard');
            $request->session()->regenerateToken();
            Log::info('Admin user logged out successfully');
            return redirect()->route('login')->with('status', 'Logout successful!');
        } catch (Exception $e) {
            Log::error('Admin logout error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An unexpected error occurred during logout. Please try again later.']);
        }
    }

}
