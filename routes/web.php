<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\ForgetPasswordController;
use App\Http\Controllers\Web\MembershipController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'login'])->name('login');
    Route::post('login_post', [AuthController::class, 'login_post'])->name('login_post');
    Route::get('login_otp/{email}', [AdminController::class, 'login_otp'])->name('login_otp');
    Route::post('login_verifyotp', [AuthController::class, 'login_verifyotp'])->name('login_verifyotp');
    Route::get('login_resend_otp/{email}', [AuthController::class, 'login_resend_otp'])->name('login_resend_otp');

    //forgetpassword
    Route::get('forgot_password', [ForgetPasswordController::class, 'forgot_password'])->name('forgot_password');
    Route::get('forget_otp/{email}', [ForgetPasswordController::class, 'forget_otp'])->name('forget_otp');
    Route::post('forget_sendotp', [ForgetPasswordController::class, 'forget_sendotp'])->name('forget_sendotp');
    Route::get('forgot_resend_otp/{email}', [ForgetPasswordController::class, 'forgot_resend_otp'])->name('forgot_resend_otp');
    Route::post('forget_verifyotp', [ForgetPasswordController::class, 'forget_verifyotp'])->name('forget_verifyotp');
    Route::get('change_password/{email}/{otp}', [ForgetPasswordController::class, 'change_password'])->name('change_password');
    Route::post('reset_password_update', [ForgetPasswordController::class, 'reset_password_update'])->name('reset_password_update');

    Route::middleware(['adminauth'])->group(function () {
       
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('user_management', [AdminController::class, 'user_management'])->name('user_management');
        Route::get('reset_password', [AdminController::class, 'reset_password'])->name('reset_password');
        Route::post('admin_logout', [AuthController::class, 'admin_logout'])->name('admin_logout');
        Route::post('reset_password_handle', [AuthController::class, 'reset_password_handle'])->name('reset_password_handle');
        Route::get('delete_user/{id}', [AuthController::class, 'delete_user'])->name('delete_user');
        Route::get('user_approval', [AdminController::class, 'user_approval'])->name('user_approval');
        Route::post('user_status_update/{id}', [AuthController::class, 'user_status_update'])->name('user_status_update');
        Route::get('membership_approval', [AdminController::class, 'membership_approval'])->name('membership_approval');
        Route::post('approve_membership', [MembershipController::class, 'approve_membership'])->name('approve_membership');
        Route::post('reject_membership', [MembershipController::class, 'reject_membership'])->name('reject_membership');
        Route::get('folder_access/{user_id}/{folder_id}', [AdminController::class, 'folder_access'])->name('folder_access');
        Route::get('membership_history', [AdminController::class, 'membership_history'])->name('membership_history');
        Route::get('add_ad', [AdminController::class, 'add_ad'])->name('add_ad');
        Route::post('upload_ad', [MembershipController::class, 'upload_ad'])->name('upload_ad');
        Route::get('ad_history', [AdminController::class, 'ad_history'])->name('ad_history');
        Route::post('delete_ad/{id}', [MembershipController::class, 'delete_ad'])->name('delete_ad');
        Route::get('support', [AdminController::class, 'support'])->name('support');
       
    });
});