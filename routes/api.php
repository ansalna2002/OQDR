<?php

use App\Http\Controllers\API\FolderController;
use App\Http\Controllers\API\ForgetPasswordController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\MembershipController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('api.key')->group(function () {
    Route::post('registration_post', [LoginController::class, 'registration_post']);
   
    Route::post('login_sendotp', [LoginController::class, 'login_sendotp']);
    Route::post('login_verifyotp', [LoginController::class, 'login_verifyotp']);

    Route::post('forget_sendotp', [ForgetPasswordController::class, 'forget_sendotp']);
    Route::post('forget_verifyotp', [ForgetPasswordController::class, 'forget_verifyotp']);
    Route::post('reset_password_update', [ForgetPasswordController::class, 'reset_password_update']);
    Route::get('membership',[MembershipController::class,'membership']);
   
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('reset_password', [LoginController::class, 'reset_password']);
        Route::post('profile_update', [UserController::class, 'profile_update']);
        Route::post('support', [UserController::class, 'support']);
       //folder
       Route::post('files_upload', [FolderController::class, 'files_upload']);
       Route::get('folder_before',[FolderController::class,'folder_before']);
       Route::post('folder_after', [FolderController::class, 'folder_after']);
       Route::get('getfolder_files',[FolderController::class,'getfolder_files']);
       Route::get('get_files/{folder_id}', [FolderController::class, 'get_files']);
       Route::get('getFiles_without_folderid', [FolderController::class, 'getFiles_without_folderid']);
       Route::get('get_file/{file_id}', [FolderController::class, 'get_file']);
       Route::post('toggle_visibility', [FolderController::class, 'toggle_visibility']);
     
       //subscriptionb
       Route::post('add_subscription', [MembershipController::class, 'add_subscription']);
       Route::get('get_user_membershipstatus', [MembershipController::class, 'get_user_membershipstatus']);
       Route::post('update_points', [MembershipController::class, 'update_points']);
       Route::get('get_reward_history', [MembershipController::class, 'get_reward_history']);
       Route::post('logout', [UserController::class, 'logout']);
       Route::post('generate_usercard', [FolderController::class, 'generate_usercard']);
       Route::get('get_user_ads', [MembershipController::class, 'get_user_ads']);
      


    });

});
