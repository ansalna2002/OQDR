<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\Loginotp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function registration_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'referral_id' => 'nullable|exists:users,user_id',
            'name'        => 'required|string|max:255',
            'email'       => [
                'required',
                'email',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    $allowedDomains = ['gmail.com', 'outlook.com', 'rediffmail.com', 'zoho.com', 'yahoo.com'];
                    $emailDomain = substr(strrchr($value, "@"), 1);
    
                    if (!in_array($emailDomain, $allowedDomains)) {
                        $fail('Only Gmail, Outlook, Rediff Mail, Zoho Mail, and Yahoo Mail addresses are allowed.');
                    }
                }
            ],
            'password'    => 'required|min:6|max:15',
            'district'    => 'required|exists:districts,name',
        ]);
        if ($validator->fails()) {
            Log::error('Validation errors:', $validator->errors()->toArray());
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation errors',
                'code'    => 422,
                'data'    => $validator->errors()
            ], 422);
        }
        do {
            $UserID = 'ODQR' . rand(1000, 9999);
        } while (User::where('user_id', $UserID)->exists());

        $user              = new User();
        $user->user_id     = $UserID;
        $user->name        = $request->name;
        $user->referral_id = $request->referral_id;
        $user->email       = $request->email;
        $user->district    = $request->district;
        $user->password    = Hash::make($request->password);
        $user->save();

        Log::info('User registered successfully:', $user->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully!',
            'image'   =>asset('assets/images/member.svg'),
            'data' => $user,
            'code' => 200,
        ], 200);
    }
    public function login_sendotp(Request $request)
    {
        Log::info('Received request for OTP login.', $request->all());

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            Log::error('Validation errors:', $validator->errors()->toArray());
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'code' => 422,
                'data' => $validator->errors()
            ], 422);
        }
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password',
                'code' => 401
            ], 401);
        }

        // $otp = rand(1000, 9999);
        $otp                  = '1234';
        $user->otp            = $otp;
        $user->otp_expired_at = now()->addMinutes(1);
        $user->save();

        Mail::to($user->email)->send(new Loginotp($otp));

        Log::info("OTP sent to: " . $user->email);

        return response()->json([
            'status' => 'success',
            'data' => [
                'otp' => $otp
            ],
            'message' => 'OTP sent successfully!',
            'code' => 200
        ]);
    }
    public function login_verifyotp(Request $request)
    {
        Log::info('Received request for OTP verification.', $request->all());

        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
            'otp'      => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            Log::error('Validation errors:', $validator->errors()->toArray());
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation errors',
                'code'    => 422,
                'data'    => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid email or password',
                'code'    => 401
            ], 401);
        }

        if ($user->otp != $request->otp || now()->gt($user->otp_expired_at)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or expired OTP',
                'code'    => 401
            ], 401);
        }

        $user->otp = null;
       
        $user->save();

        $token = $user->createToken('AuthToken')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Login successful!',
            'code'    => 200,
            'data'    => [
                'token' => $token,
                'user' => $user
                
            ]
        ]);
    }
    public function reset_password(Request $request)
    {
        Log::info('Received request for password reset.', $request->all());

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6|different:old_password',
        ]);
    
        if ($validator->fails()) {
            Log::error('Validation errors:', $validator->errors()->toArray());
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation errors',
                'code'    => 422,
                'data'    => $validator->errors()
            ], 422);
        }
    
        $user = auth()->user();
    
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Old password is incorrect',
                'code'    => 401
            ], 401);
        }
    
        $user->password = Hash::make($request->new_password);
        $user->save();
    
        Log::info('User password updated successfully:', ['email' => $user->email]);
    
        return response()->json([
            'status'  => 'success',
            'message' => 'Password updated successfully!',
            'code'    => 200
        ], 200);
    }
    
}

