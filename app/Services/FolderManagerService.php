<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FolderManagerService
{

    protected $fileManagerService;
    public const MAX_FOLDER_LEVEL = 5;

    public function __construct(FileManagerService $fileManagerService)
    {
        $this->fileManagerService = $fileManagerService;
    }
    public function setFileManagerService(FileManagerService $fileManagerService)
    {
        $this->fileManagerService = $fileManagerService;
    }
    /**
     * Create a new directory
     *
     * @param string $dirName Name of the directory to be created.
     * @return string Success or error message.
     * @return string|true 
     */
    public function createDirectory(string $dirName, string $baseDirectory): string|true
    {
        if ($dirName == "") {
            return "Folder name can't be empty!";
        }

        if (!preg_match("/^[a-zA-Z0-9 _-]+$/", $dirName)) {
            return "Invalid directory name";
        }

        if (count(preg_split('/[\/\\\\]+/', $baseDirectory)) > self::MAX_FOLDER_LEVEL) {
            return "Total of folder levels was reached";
        }
        $baseDir = base_path('') . DIRECTORY_SEPARATOR . $baseDirectory;
        $targetDir = $baseDir . '/' . $dirName;

        if (!is_dir($targetDir)) {
            if (mkdir($targetDir, 0777, true)) {
                return true;
            } else {
                return "Something went wrong when create " . $dirName . ".";
            }
        }

        return "this folder name is already exists!";
    }
    /**
     * Rename an existing directory
     *
     * @param string $basePath The base path where the directory is located.
     * @param string $currentName The current name of the directory.
     * @param string $newName The new name for the directory.
     * @return true|string Success or error message.
     */
    public function renameDirectory($basePath, $currentName, $newName): true|string
    {
        $currentPath = $basePath . DIRECTORY_SEPARATOR .  $currentName;
        $newPath = $basePath . DIRECTORY_SEPARATOR . $newName;
        $fullCurrentPath = base_path($currentPath);
        $fullNewPath = base_path($newPath);

        if (!is_dir($fullCurrentPath)) {
            return "Folder not exists!";
        }

        if ($this->isSeriousFolder($currentPath)) {
            return "Folder includes used file(s)/folders or Folder is used";
        }

        if (rename($fullCurrentPath, $fullNewPath)) {
            return true;
        }

        return "Cannot rename folder";
    }

    /**
     * Move a directory and its contents to a new location.
     *
     * This function recursively moves all files and subdirectories from the source directory
     * to the target directory. After moving, the source directory will be deleted if it's empty.
     *
     * @param string $currentPath The current path of the directory.
     * @param string $newPath The target path where the directory will be moved.
     * @return bool Returns true if the directory was successfully moved, false otherwise.
     */
    public function moveDirectory($currentPath, $newPath)
    {
        if (!is_dir($currentPath)) {
            return false;
        }

        if ($this->isSeriousFolder($currentPath)) {
            return false;
        }

        if (!is_dir($newPath)) {
            mkdir($newPath, 0755, true);
        }

        $items = scandir($currentPath);

        foreach ($items as $item) {
            // Skip special entries '.' and '..'
            if ($item = '.' || $item = '..') {
                continue;
            }

            $currentFilePath = $currentPath . DIRECTORY_SEPARATOR . $item;
            $newFilePath = $newPath . DIRECTORY_SEPARATOR . $item;

            if (is_dir($currentFilePath)) {
                $this->moveDirectory($currentFilePath, $newFilePath);
            } else {
                $this->fileManagerService->moveFile($currentPath, $newPath, $item);
            }
        }

        $this->deleteDirectory($currentPath);

        return true;
    }

    /**
     * Delete a directory and its contents
     *
     * @param string $directory Path of the directory to be deleted.
     * @return true|string Success or error message.
     */
    public function deleteDirectory($directory): true|string
    {
        $fullBase = base_path($directory);
        if (!is_dir($fullBase)) {
            return "this folder is not exist!";
        }

        if ($this->isSeriousFolder($directory)) {
            return "Folder includes used file(s)/folders or Folder is used";
        }

        $items = scandir($fullBase);

        foreach ($items as $item) {
            $path = $directory . DIRECTORY_SEPARATOR . $item;
            if ($item === '.' || $item === '..') {
                continue;
            }
            if (is_dir($path)) {
                $result = $this->deleteDirectory($path);
                if (is_string($result)) {
                    return $result;
                }
            } else {
                $request  = Request::create('/files/remove', 'POST', [
                    'file' => $item,
                    'base_directory' => $directory
                ]);
                $removeFile = $this->fileManagerService->removeFile($request);
                if (!$removeFile->status) {
                    return $removeFile->message;
                }
            }
        }
        rmdir($fullBase);
        return true;
    }

    /**
     * Check if a directory is empty.
     *
     * This function checks if the given directory is empty by opening it, reading through
     * its contents, and returning false if it finds any files or subdirectories.
     *
     * @param string $directory The path to the directory to check.
     * @return bool Returns true if the directory is empty, false if it is not or if the path is not a directory.
     */
    protected function isEmptyDirectory($directory)
    {
        if (!is_dir($directory)) {
            return false;
        }
        $handle = opendir($directory);

        while (false !== ($entry = readdir($handle))) {
            if ($entry != '.' && $entry != '..') {
                closedir($handle);
                return false;
            }
        }
        closedir($handle);
        return true;
    }

    /**
     * to check a folder can be deleted/updated or not (includes files that cannot deleted/updated)
     * 
     * @param string $directory
     * @return bool
     */
    protected function isSeriousFolder(string $directory): bool
    {
        $directory = str_replace('\\', '/', $directory);
        if (
            !!Product::where('product_file_path', $directory)->get()->count()
        ) {
            return true;
        }
        $fullBase = base_path($directory);
        $fullBase = str_replace('\\', '/', $fullBase);

        if (!is_dir($fullBase)) {
            return true;
        }
        $items = scandir($fullBase);
        foreach ($items as $item) {
            $path = $directory . DIRECTORY_SEPARATOR . $item;
            if ($item === '.' || $item === '..') {
                continue;
            }
            if (is_dir($fullBase)) {
                if ($this->isSeriousFolder($path)) {
                    return true;
                }
            } else {
                if ($this->fileManagerService->isSeriousFile($directory, $item)) {
                    return true;
                }
            }
        }
        return false;
    }
}