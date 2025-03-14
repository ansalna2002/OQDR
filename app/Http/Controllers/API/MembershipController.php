<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Membership;
use App\Models\RewardHistory;
use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MembershipController extends Controller
{
    public function membership()
    {
        try {
            $membership = Membership::where('is_active', 1)->get();
            if ($membership->isEmpty()) {
                return response()->json([
                    'status'  => 'error',
                    'data'    => [],
                    'message' => 'No membership found ',
                    'code'    => 404
                ], 404);
            }
            return response()->json([
                'status'  => 'success',
                'data'    => $membership,
                'message' => 'membership fetched successfully.',
                'code'    => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch membership.',
                'code'    => 500,
            ], 500);
        }
    }
    public function add_subscription(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id'        => 'required|exists:users,user_id',
                'membership_id'  => 'required|exists:memberships,membership_id',
                'transaction_id' => 'required|unique:user_subscriptions,transaction_id',
                'payment_image'  => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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

            $userdetails = User::where('user_id', $request->user_id)->first();
            $membership  = Membership::where('membership_id', $request->membership_id)->first();

            $imagepath = null;
            if ($request->hasFile('payment_image')) {
                $payment_image = $request->file('payment_image');
                $payment_imagename = time() . '_' . $payment_image->getClientOriginalName();
                $payment_image->move(public_path('assets/images/payment_images'), $payment_imagename);
                $imagepath = 'assets/images/payment_images/' . $payment_imagename;
            }

            $userdetails->subscribed_at         = Carbon::now();
            $userdetails->subscription_end_date = Carbon::now()->addYear();
            $userdetails->save();

            $data                 = new UserSubscription();
            $data->user_id        = $request->user_id;
            $data->name           = $userdetails->name;
            $data->membership_id  = $membership->membership_id;
            $data->title          = $membership->title;
            $data->amount         = $membership->amount;
            $data->validity       = $membership->days;
            $data->transaction_id = $request->transaction_id;
            $data->payment_image  = $imagepath;
            $data->status         = "Requested";
            $data->remark         = "Subscripiton Requested";
            $data->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Subscription added successfully',
                'data' => [
                    'user_id'        => $data->user_id,
                    'name'           => $data->name,
                    'membership_id'  => $data->membership_id,
                    'title'          => $data->title,
                    'amount'         => $data->amount,
                    'validity'       => $data->validity,
                    'transaction_id' => $data->transaction_id,
                    'payment_image'  => asset($imagepath),
                    'status'         => $data->status,
                    'remark'         => $data->remark,
                    'created_at'     => $data->created_at,
                    'updated_at'     => $data->updated_at,
                    'id'             => $data->id,
                ],
                'code' => 200,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Exception Error in add_subscription: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Exception Error',
                'code' => 500,
                'data' => 'Something went wrong! Please try again.'
            ], 500);
        }
    }
    public function get_user_membershipstatus()
    {

        $user = auth()->user();
        Log::info($user);
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not authenticated',
                'code'    => 401
            ], 401);
        }

        $user_id      = $user->user_id ?? $user->user_id;
        $subscription = UserSubscription::where('user_id', $user_id)->latest()->first();
        Log::info($subscription);
        if (!$subscription) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No subscription found for this user',
                'code'    => 404
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Membership details fetched successfully',
            'data'    => $subscription,
            'code'    => 200
        ], 200);
    }
    public function update_points(Request $request) {

        $validator = Validator::make($request->all(), [
            'user_id'    => 'required|exists:users,user_id',
            'watch_time' => 'required|numeric|min:0.01', 
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        $user = User::where('user_id', $request->user_id)->first();
    
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
    
        $pointsEarned = floor($request->watch_time / 60) * 10;
    
        if ($pointsEarned > 0) {
            $user->reward_points += $pointsEarned;
            $user->save();
    
            RewardHistory::create([
                'user_id'         => $user->user_id,
                'watch_time'      => $request->watch_time,
                'points_earned'   => $pointsEarned,
                'points_earn_date'=> now() 
            ]);
        }
    
        return response()->json([
            'status'       => 'success',
            'message'      => 'Points added successfully!',
            'watch_time'   => $request->watch_time . ' seconds watched',
            'points_added' => $pointsEarned,
            'total_points' => $user->reward_points
        ]);
    }
    public function get_reward_points() {
        $user = auth()->user();
    
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not authenticated',
                'code'    => 401
            ], 401);
        }
    
        $user_id           = $user->user_id;
        $totalRewardPoints = RewardHistory::where('user_id', $user_id)->sum('points_earned');
        $rewardHistory     = RewardHistory::where('user_id', $user_id)
            ->orderBy('points_earn_date', 'desc')
            ->get();
    
        if ($rewardHistory->isEmpty()) {
            return response()->json([
                'status'        => 'success',
                'message'       => 'No reward history found for the user.',
                'user_id'       => $user_id,
                'total_rewards' => 0,
                'reward_history'=> []
            ]);
        }
    
        return response()->json([
            'status'         => 'success',
            'message'        => 'User reward points fetched successfully!',
            'user_id'        => $user_id,
            'total_rewards'  => $totalRewardPoints,  
            'reward_history' => $rewardHistory       
        ]);
    }
    public function get_user_ads(Request $request)
    {
        try {
            $user = auth()->user(); 
    
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            $ads = Ad::where('district', $user->district)
                ->where('is_active', 1)
                ->get();
    
            if ($ads->isEmpty()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'data' => [],
                        'message' => 'No ad found ! ',
                        'code' => 404
                    ], 404);
            }
    
            $ads->transform(function ($ad) {
                $ad->image = $ad->image ? url($ad->image) : null;
                $ad->video = $ad->video ? url($ad->video) : null;
                return $ad;
            });
    
            return response()->json(['ads' => $ads], 200);
    
        } catch (\Exception $e) {
            Log::error('Error in get_user_ads', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine()
            ]);
    
            return response()->json(['error' => 'Something went wrong. Please try again later.'], 500);
        }
    }
    
    
    

}
