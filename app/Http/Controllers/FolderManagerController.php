<?php

namespace App\Http\Controllers;

use App\Services\FolderManagerService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FolderManagerController extends Controller
{
    protected $folderManagerService;
    protected $userService;

    public function __construct(FolderManagerService $folderManagerService, UserService $userService)
    {
        $this->folderManagerService = $folderManagerService;
        $this->userService = $userService;
    }
    public function store(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $folderName = $request->input('folder_name');
        $baseDirectory = $request->input('base_directory');

        $msg = $this->folderManagerService->createDirectory($folderName, $baseDirectory);

        if (is_string($msg)) {
            return response()->json([
                'success' => false,
                'message' => $msg
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => "Create folder successfully!"
        ], 200);
    }

    public function renameFolder(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $baseDirectory = $request->input('base_directory');
        $currentName = $request->input('current_name');
        $newName = $request->input('new_name');

        $result = $this->folderManagerService->renameDirectory($baseDirectory, $currentName, $newName);

        if (is_string($result)) {
            return response()->json(
                [
                    'message' => "Rename folder unsuccessfully! " . $result,
                ]
            );
        }

        return response()->json(
            [
                'message' => "Rename folder successfully!",
            ]
        );
    }

    public function deleteFolder(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $baseDirectory = $request->input('base_directory');

        $result = $this->folderManagerService->deleteDirectory($baseDirectory);
        if (is_string($result)) {
            return response()->json(
                [
                    'message' => "Delete folder unsuccessfully! " . $result,
                ]
            );
        }

        return response()->json(
            [
                'message' => "Delete folder successfully!",
            ]
        );
    }
}
