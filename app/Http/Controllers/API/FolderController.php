<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class FolderController extends Controller
{
    public function folder_before(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Folder opened successfully!',
            'image' => asset('assets/images/bg-left.svg'),
            'code' => 200,
        ], 200);
    }
    public function folder_after(Request $request)
    {
        $user      = auth()->user();
        $validator = Validator::make($request->all(), [
            'folder_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors occurred.',
                'data' => $validator->errors(),
                'code' => 422,
            ], 422);
        }
        do {
            $folderId = 'FDR' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        } while (Folder::where('folder_id', $folderId)->exists());

        $folder = Folder::create([
            'folder_id' => $folderId,
            'user_id'   => $user->user_id,
            'parent_id' => null,
            'name'      => $request->folder_name,
            'path'      => null,
            'type'      => 'folder',
            'is_hidden' => 0,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Folder created successfully',
            'folder'  => $folder,
            'code'    => 200,
        ]);
    }
    public function files_upload(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'file'      => 'required|file|mimes:pdf,png,jpg,jpeg',
            'folder_id' => 'nullable|exists:folders,folder_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors occurred.',
                'data' => $validator->errors(),
                'code' => 422,
            ], 422);
        }

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $folderPath = 'uploads/user_' . $user->id;

        $parentFolderId = null;
        if ($request->folder_id) {
            $parentFolder = Folder::where('folder_id', $request->folder_id)->first();
            if ($parentFolder) {
                $folderPath .= '/folder_' . $parentFolder->folder_id;
                $parentFolderId = $parentFolder->folder_id;
            }
        }

        $destinationPath = public_path($folderPath);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $file->move($destinationPath, $fileName);
        $filePath = $folderPath . '/' . $fileName;

        do {
            $fileId = 'FILE' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Folder::where('file_id', $fileId)->exists());

        $uploadedFile = Folder::create([
            'user_id'   => $user->user_id,
            'folder_id' => $parentFolderId,   
            'file_id'   => $fileId,         
            'name'      => $fileName,
            'path'      => $filePath,
            'type'      => 'file',
            'is_hidden' => 0,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'File uploaded successfully',
            'file'    => [
                'file_id'    => $uploadedFile->file_id,
                'user_id'    => $uploadedFile->user_id,
                'folder_id'  => $uploadedFile->folder_id,
                'name'       => $uploadedFile->name,
                'url'        => asset($filePath),
                'type'       => $uploadedFile->type,
                'is_hidden'  => $uploadedFile->is_hidden,
                'created_at' => $uploadedFile->created_at->format('Y-m-d H:i:s'),
            ],
            'code' => 200
        ]);
    }
    public function getfolder_files()
    {
        $user = auth()->user();
        Log::info("Fetching folders and files for user: " . $user->id);

        try {
            $folders = Folder::where('user_id', $user->user_id)
                ->where('type', 'folder')
                ->where('is_hidden', 0)
                ->with('children')
                ->get();

            Log::info("Folders retrieved: " . $folders->count());

            $files = Folder::where('user_id', $user->user_id)
                ->where('type', 'file')
                ->where('is_hidden', 0)
                ->get()
                ->map(function ($file) {
                    return [
                        'id'         => $file->id,
                        'user_id'    => $file->user_id,
                        'folder_id'  => $file->folder_id,
                        'file_id'    => $file->file_id,
                        'name'       => $file->name,
                        'type'       => $file->type,
                        'url'        => asset($file->path),
                        'is_hidden'  => $file->is_hidden,
                        'created_at' => $file->created_at,
                        'updated_at' => $file->updated_at,
                    ];
                });

            Log::info("Files retrieved: " . $files->count());

            if ($folders->isEmpty() && $files->isEmpty()) {
                Log::warning("No folders or files found for user: " . $user->id);
                return response()->json([
                    'status' => 'error',
                    'data' => [],
                    'message' => 'No folders or files found',
                    'code' => 404
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Folders and files retrieved successfully',
                'folders' => $folders,
                'files' => $files,
                'code' => 200,
            ]);

        } catch (\Exception $e) {
            Log::error("Error in getUserFoldersAndFiles: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'code' => 500
            ], 500);
        }
    }
    public function get_files($folderId)
    {
        $user = auth()->user();
        Log::info("Fetching files for folder ID: " . $folderId . " by user: " . $user->id);
    
        try {
            $folder = Folder::where('folder_id', $folderId)
                ->where('user_id', $user->user_id)
                ->where('type', 'folder')
                ->where('is_hidden', 0)
                ->first();
    
            if (!$folder) {
                Log::warning("Folder not found or does not belong to user: " . $user->id);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Folder not found',
                    'code' => 404
                ], 404);
            }
    
            $files = Folder::where('folder_id', $folderId)
                ->where('user_id', $user->user_id)
                ->where('type', 'file')
                ->where('is_hidden', 0)
                ->get()
                ->map(function ($file) {
                    return [
                        'id'         => $file->id,
                        'user_id'    => $file->user_id,
                        'folder_id'  => $file->folder_id,
                        'name'       => $file->name,
                        'type'       => $file->type,
                        'url'        => asset($file->path),
                        'is_hidden'  => $file->is_hidden,
                        'created_at' => $file->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $file->updated_at->format('Y-m-d H:i:s'),
                    ];
                });
    
            Log::info("Files retrieved: " . $files->count() . " for folder ID: " . $folderId);
    
            return response()->json([
                'status'  => 'success',
                'message' => 'Files retrieved successfully',
                'folder'  => $folder,
                'files'   => $files,
                'code'    => 200,
            ]);
    
        } catch (\Exception $e) {
            Log::error("Error in getFilesByFolderId: " . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'code'    => 500
            ], 500);
        }
    }
    public function getFiles_without_folderid()
    {
        $user = auth()->user();
        Log::info("Fetching files without folder for user: " . $user->id);

        try {
            $files = Folder::where('user_id', $user->user_id)
                ->whereNull('folder_id') 
                ->where('type', 'file')
                ->where('is_hidden', 0)
                ->get()
                ->map(function ($file) {
                    return [
                        'id'         => $file->id,
                        'user_id'    => $file->user_id,
                        'folder_id'  => $file->folder_id,
                        'file_id'    => $file->file_id,
                        'name'       => $file->name,
                        'type'       => $file->type,
                        'url'        => asset($file->path),
                        'is_hidden'  => $file->is_hidden,
                        'created_at' => $file->created_at,
                        'updated_at' => $file->updated_at,
                    ];
                });


            if ($files->isEmpty()) {
                Log::warning("No standalone files found for user: " . $user->id);
                return response()->json([
                    'status'  => 'error',
                    'message' => 'No standalone files found',
                    'files'   => [],
                    'code'    => 404
                ], 404);
            }

            Log::info("Files retrieved successfully for user: " . $user->id);
            return response()->json([
                'status'  => 'success',
                'message' => 'Standalone files retrieved successfully',
                'files'   => $files,
                'code'    => 200,
            ]);

        } catch (\Exception $e) {
            Log::error("Error in getFilesWithoutFolder: " . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'code'    => 500
            ], 500);
        }
    }

    public function get_file($file_id)
{
    $user = auth()->user();
    Log::info("Fetching file with ID: " . $file_id . " for user: " . $user->id);

    try {
        $file = Folder::where('user_id', $user->user_id)
            ->where('file_id', $file_id)
            ->where('type', 'file')
            ->where('is_hidden', 0)
            ->first();

        if (!$file) {
            Log::warning("File not found with ID: " . $file_id . " for user: " . $user->user_id);
            return response()->json([
                'status' => 'error',
                'message' => 'File not found',
                'code' => 404
            ], 404);
        }

        Log::info("File retrieved successfully: " . $file->name);

        return response()->json([
            'status' => 'success',
            'message' => 'File retrieved successfully',
            'file' => [
                'id'         => $file->id,
                'user_id'    => $file->user_id,
                'name'       => $file->name,
                'type'       => $file->type,
                'url'        => asset($file->path),
                'is_hidden'  => $file->is_hidden,
                'created_at' => $file->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $file->updated_at->format('Y-m-d H:i:s'),
            ],
            'code' => 200,
        ]);

    } catch (\Exception $e) {
        Log::error("Error fetching file: " . $e->getMessage());
        return response()->json([
            'status'  => 'error',
            'message' => 'Something went wrong',
            'code'    => 500
        ], 500);
    }
}

public function toggle_visibility(Request $request)
{
    $user = auth()->user();

    $validator = Validator::make($request->all(), [
        'folder_id' => 'nullable|exists:folders,folder_id',
        'file_id' => 'nullable|exists:folders,file_id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation errors occurred.',
            'data' => $validator->errors(),
            'code' => 422,
        ], 422);
    }

    if ($request->filled('folder_id')) {
        $folder = Folder::where('folder_id', $request->folder_id)
            ->where('user_id', $user->user_id)
            ->first();

        if (!$folder) {
            return response()->json([
                'status' => 'error',
                'message' => 'Folder not found.',
                'code' => 404,
            ], 404);
        }

        $newVisibility = $folder->is_hidden ? 0 : 1;
        $folder->update(['is_hidden' => $newVisibility]);

        Folder::where('folder_id', $folder->folder_id)->update(['is_hidden' => $newVisibility]);

        $action = $newVisibility ? 'hidden' : 'unhidden';
        Log::info("Folder ID: {$folder->id} and its contents have been {$action} by user: " . $user->id);

        return response()->json([
            'status' => 'success',
            'message' => "Folder and its contents {$action} successfully",
            'code' => 200,
        ]);
    }

    if ($request->filled('file_id')) {
        $file = Folder::where('file_id', $request->file_id)
            ->where('user_id', $user->user_id)
            ->where('type', 'file')
            ->first();

        if (!$file) {
            return response()->json([
                'status' => 'error',
                'message' => 'File not found.',
                'code' => 404,
            ], 404);
        }

        $file->update(['is_hidden' => $file->is_hidden ? 0 : 1]);

        $action = $file->is_hidden ? 'hidden' : 'unhidden';
        Log::info("File ID: {$file->id} has been {$action} by user: " . $user->id);

        return response()->json([
            'status' => 'success',
            'message' => "File {$action} successfully",
            'code' => 200,
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'No valid folder_id or file_id provided.',
        'code' => 400,
    ], 400);
}

    public function generate_usercard()
    {
        $user = Auth::user();

        if (!$user) {
            Log::error('User not found while generating user card.');
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'code' => 404
            ], 404);
        }

        Log::info('User found:', ['id' => $user->user_id, 'name' => $user->name]);

        $cardId = base64_encode($user->user_id . '-' . time());
        Log::info('Generated Card ID:', ['card_id' => $cardId]);

        $accessUrl = route('shared_folder', ['card_id' => $cardId]);
        Log::info('Generated Access URL:', ['access_url' => $accessUrl]);

        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($accessUrl);
        Log::info('Generated QR Code URL:', ['qr_code_url' => $qrCodeUrl]);

        $response = [
            'status' => 'success',
            'message' => 'User card generated successfully',
            'user_card' => [
                'name'          => $user->name,
                'phone'         => $user->phone_number,
                'user_id'       => $user->user_id,
                'profile_image' => $user->profile_image ? url($user->profile_image) : null,
                'qr_code'       => $qrCodeUrl,
                'access_url'    => $accessUrl,
            ],
            'code' => 200
        ];

        Log::info('Final Response:', $response);

        return response()->json($response);
    }


}