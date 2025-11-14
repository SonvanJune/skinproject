<?php

namespace App\Http\Controllers;

use App\Services\FileManagerService;
use App\Services\FileService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FileManagerController extends Controller
{

    protected $fileManagerService;
    protected $fileService;
    protected $userService;

    public function __construct(FileManagerService $fileManagerService, FileService $fileService, UserService $userService)
    {
        $this->fileManagerService = $fileManagerService;
        $this->fileService = $fileService;
        $this->userService = $userService;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fileManager(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        return view('file-management.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editor(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        return view('file-management.editor');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = $this->fileManagerService->getListItemInDirectory('images');
        return response()->json([
            'files' => $result['files'],
            'directories' => $result['directories'],
            'base_directory' => $result['base_directory']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $result = $this->fileManagerService->uploadFile($request);
        if (!is_string($result)) {
            return response()->json([
                'success' => true,
                'message' => 'Upload successful',
                'filePath' => $result,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getFolders(Request $request, $directoryName)
    {
        $result = $this->fileManagerService->getListItemInDirectory($directoryName);
        return response()->json([
            'files' => $result['files'],
            'directories' => $result['directories'],
            'base_directory' => $result['base_directory']
        ]);
    }

    public function renameFile(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $validator = Validator::make($request->all(), [
            'basePath' => 'required|string|max:255',      // Validate base path (required, string, max length)
            'currentFileName' => 'required|string|max:255', // Validate current file name (required, string, max length)
            'newFileName' => 'required|string|max:255',        // Validate new name (required, string, max length)
            'isImage' => 'sometimes|boolean',              // Optional parameter, must be boolean if provided
        ]);



        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $basePath = $request->input('basePath');
        $currentFileName = $request->input('currentFileName');
        $newName = $request->input('newFileName');
        $isImage = $request->input('isImage', false);


        $result = $this->fileManagerService->renameFile($basePath, $currentFileName, $newName,  $isImage);
        return response()->json([
            'message' => $result
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeFile(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $result = $this->fileManagerService->removeFile($request);
        return response()->json($result);
    }

    public function moveFile(Request $request)
    {
        // $user = parent::checkTokenWhenReload($request, $this->userService);
        // parent::checkAdminInPage($user);
        $oldBase = $request->input('oldBase');
        $newBase = $request->input('newBase');
        $fileName = $request->input('fileName');
        $isImage =  $this->fileService->isImagePath($fileName);

        $result = $this->fileManagerService->moveFile($oldBase, $newBase, $fileName, $isImage);
        return response()->json([
            'message' => $result
        ]);
    }

    /**
     * Download a file directly into client's device
     * 
     * @param Request $request
     * @param string $path path of the file
     * @param string $file_name the name of the file will be downloaded
     * 
     * return $result as a response include a file and will be directly download into user's device
     */
    public function download(Request $request, string $path, string $file_name)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if($user){
            parent::checkUseInPage($user->roles);
        }
        $request->merge(["path" => $path, "file_name" => $file_name]);

        $result = $this->fileService->download($request, $this->userService);

        if (is_string($result)) {
            return back()->with('error', $result);
        }

        return $result;
    }

    public function getFile($filename = null)
    {
        $basePath = base_path();

        if (empty($filename)) {
            return $basePath;
        }

        $path = base_path($filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}
