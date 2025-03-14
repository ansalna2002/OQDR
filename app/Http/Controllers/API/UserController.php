<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function profile_update(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'phone_number'  => 'required|numeric|min:10',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'district'      => 'required|exists:districts,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation errors occurred.',
                'data'    => $validator->errors(),
                'code'    => 422,
            ], 422);
        }

        $user->name         = $request->name;
        $user->district     = $request->district;
        $user->phone_number = $request->phone_number;

        if ($request->hasFile('profile_image')) {
            $fileName = time() . '_' . $request->file('profile_image')->getClientOriginalName();
            $destinationPath = public_path('assets/images/profile');
            $request->file('profile_image')->move($destinationPath, $fileName);
            $imagePath = 'assets/images/profile/' . $fileName;

            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                unlink(public_path($user->profile_image));
            }

            $user->profile_image = $imagePath;
        }

        $user->save();
        return response()->json([
            'status' => 'success',
            'data' => [
                'name'          => $user->name,
                'user_id'       => $user->user_id,
                'email'         => $user->email,
                'phone_number'  => $user->phone_number,
                'profile_image' => $user->profile_image ? url($user->profile_image) : null,
                'district'      => $user->district,
            ],
            'message' => 'Profile updated successfully.',
            'code' => 200,
        ], 200);
    }
    public function support(Request $request)
    {
        $user = auth()->user();
    
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation errors occurred.',
                'data'    => $validator->errors(),
                'code'    => 422,
            ], 422);
        }
    
        $support          = new Support();
        $support->user_id = $user->user_id;
        $support->query   = $request->input('query');  
        $support->save();
    
        return response()->json([
            'status'  => 'success',
            'data'    => $support,
            'message' => 'Support query submitted successfully.',
            'code'    => 200,
        ], 200);
    }
    public function usercard_access($card_id)
    {
        return response()->json([
            'message' => 'User card access granted',
            'card_id' => $card_id
        ]);
    }
    public function logout(Request $request)
    {
        $user = auth()->user();
    
        if ($user) {
            $user->currentAccessToken()->delete(); 
            return response()->json([
                'status'  => 'success',
                'message' => 'Logged out successfully.',
                'code'    => 200,
            ], 200);
        }
        return response()->json([
            'status'  => 'error',
            'message' => 'User not authenticated.',
            'code'    => 401,
        ], 401);
    }
    


}