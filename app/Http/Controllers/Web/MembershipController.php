<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\District;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MembershipController extends Controller
{
    public function approve_membership(Request $request)
    {
        try {
            Log::info('Accept Request initiated.', ['accept_id' => $request->accept_id]);

            $subscription = UserSubscription::find($request->accept_id);

            if (!$subscription) {
                Log::warning('Subscription not found.', ['accept_id' => $request->accept_id]);
                return redirect()->route('subscription_requests')->with('errormessage', "Subscription not found!");
            }

            Log::info('Subscription found.', ['subscription_id' => $subscription->id, 'user_id' => $subscription->user_id]);

            $subscription->status              = "Accepted";
            $subscription->remark              = "Subscription Accepted";
            $subscription->is_active           = 1;
            $subscription->admin_approved_date = now();
            $subscription->expiry_date         = now()->addYear();
            $subscription->save();

            $user = User::where('user_id', $subscription->user_id)->first();

            if (!$user) {
                Log::warning('User not found.', ['user_id' => $subscription->user_id]);
                return redirect()->route('subscription_requests')->with('errormessage', "User not found!");
            }

            Log::info('User found.', ['user_id' => $user->user_id]);

            $user->membership            = $subscription->membership_id;
            $user->is_subscribed         = 1;
            $user->subscribed_at         = now();
            $user->subscription_end_date = now()->addYear();
            $user->save();

            Log::info('User subscription updated.', ['user_id' => $user->user_id]);

            return redirect()->route('subscription_requests')->with('successmessage', "Subscription accepted successfully!");
        } catch (\Exception $e) {
            Log::error('Exception Error in accept_request:', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('membership_approval')->with('errormessage', "Exception Error: " . $e->getMessage());
        }
    }
    public function reject_membership(Request $request)
    {

        try {
            $subscription = UserSubscription::find($request->reject_id);

            if ($subscription) {

                $subscription->status              = "Rejected";
                $subscription->remark              = "Subscription Rejected";
                $subscription->admin_approved_date = now();
                $subscription->save();

                return redirect()->route('membership_approval')->with('successmessage', "Subscription deleted successfully!");
            } else {
                return redirect()->route('membership_approval')->with('errormessage', "Subscription not Found!");
            }
        } catch (\Exception $e) {
            return redirect()->route('membership_approval')->with('errormessage', "Exception Error: " . $e->getMessage());
        }
    }
   
    public function upload_ad(Request $request)
    {
        try {
            Log::info('Upload Ad Request Received', $request->all());
    
            $validator = Validator::make($request->all(), [
                'image'    => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240',
                'video'    => 'nullable|file|mimes:mp4,mov,avi|max:20480',
                'district' => 'required|exists:districts,id',
                'date'     => 'required|date',
                'time'     => 'required',
            ]);
    
            if ($validator->fails()) {
                $errors = implode(', ', $validator->errors()->all());
                Log::warning('Validation Failed', ['errors' => $errors]);
    
                return redirect()->back()->with('error', $errors)->withInput();
            }
    
            $imagePath = null;
            if ($request->hasFile('image')) {
                Log::info('Processing Image Upload...');
                
                $image = $request->file('image'); 
                $fileName = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('assets/images/products');
                
                $image->move($destinationPath, $fileName);
                $imagePath = 'assets/images/products/' . $fileName;
                
                Log::info('Image Uploaded Successfully', ['path' => $imagePath]);
            }
            
            $videopath = null;
            if ($request->hasFile('video')) {
                Log::info('Processing Video Upload...');
                $fileName = time() . '_' . $request->file('video')->getClientOriginalName();
                $destinationPath = public_path('assets/images/products');
                
                $request->file('video')->move($destinationPath, $fileName);
                $videopath = 'assets/images/products/' . $fileName;
                
                Log::info('Video Uploaded Successfully', ['video_path' => $videopath]);
            }
            $district_name = District::where('id', $request->district)->value('name');
            $ad = Ad::create([
                'image'     => $imagePath,
                'video'     => $videopath,
                'district'  => $district_name,
                'date'      => $request->date,
                'time'      => $request->time,
                'is_active' => 1,
            ]);
    
            Log::info('Ad Created Successfully', ['ad_id' => $ad->id]);
    
            return redirect()->back()->with('success', 'Ad uploaded successfully!');
    
        } catch (\Exception $e) {
            Log::error('Error in upload_ad', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine()
            ]);
    
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }
    public function delete_ad($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->delete();

        return redirect()->route('add_ad')->with('success', 'Ad deleted successfully!');
    }
}

