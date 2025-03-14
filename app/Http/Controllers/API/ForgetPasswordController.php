<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use App\Models\BannerImage;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ForgetPasswordController extends Controller
{
    public function forget_sendotp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $user = User::where('email', $request->email)->where('role', 'user')->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
        }
    
        $otp                  = rand(100000, 999999);
        $encryptedOtp         = Crypt::encryptString($otp);
        $expiryTime           = Carbon::now()->addMinutes(1);
        $user->otp            = $encryptedOtp;
        $user->otp_expired_at = $expiryTime;
        $user->save();
    
        try {
            Mail::to($request->email)->send(new ForgetPasswordMail($otp));
    
            return response()->json([
                'status'  => 'success',
                'message' => 'OTP sent successfully.',
                'email'   => $request->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP:', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Failed to send OTP. Please try again.'], 500);
        }
    }
    public function forget_verifyotp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp'   => 'required|string|min:6|max:6',
            ]);
    
            $user = User::where('email', $request->email)->where('role', 'user')->first();
    
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Invalid User'], 404);
            }
    
            if (Crypt::decryptString($user->otp) !== $request->otp) {
                return response()->json(['status' => 'error', 'message' => 'The verification code is incorrect.'], 400);
            }
    
            return response()->json([
                'status'  => 'success',
                'message' => 'OTP verified successfully.',
                'email'   => $request->email,
                'otp'     => $request->otp,
            ]);
        } catch (\Exception $e) {
            Log::error('Error verifying OTP', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['status' => 'error', 'message' => 'Something went wrong! Please try again.'], 500);
        }
    }
    public function reset_password_update(Request $request)
    {
        Log::info('Reset Password Request Data:', $request->all());
    
        $validator = Validator::make($request->all(), [
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'email'                 => 'required|email',
            'otp'                   => 'required|string|min:6|max:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
        }
    
        try {
            $user = User::where('email', $request->email)->where('role', 'user')->first();
    
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Invalid email or OTP.'], 400);
            }
    
            try {
                $storedOtp = Crypt::decryptString($user->otp);
                if ($storedOtp !== $request->otp) {
                    return response()->json(['status' => 'error', 'message' => 'The verification code is incorrect.'], 400);
                }
            } catch (\Exception $e) {
                Log::error('Error decrypting OTP', ['email' => $request->email, 'error' => $e->getMessage()]);
                return response()->json(['status' => 'error', 'message' => 'Invalid OTP.'], 400);
            }
    
            $user->password = Hash::make($request->password);
            $user->otp = null;
            $user->save();
    
            return response()->json(['status' => 'success', 'message' => 'Password has been successfully reset.']);
        } catch (\Exception $e) {
            Log::error('Error resetting password', ['email' => $request->email, 'error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'An error occurred. Please try again.'], 500);
        }
    }
    
}
    
        
   

