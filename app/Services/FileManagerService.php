<?php

namespace App\Services;

use App\DTOs\GetFilesInFolderDTO;
use App\DTOs\RemoveFileDTO;
use App\Services\FileService;
use App\DTOs\CreateFileDTO;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SlideshowImage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class FileManagerService
 *
 * This class provides file management functionalities such as listing files in directories, uploading files,
 * deleting files, and renaming files.
 */
class FileManagerService
{
    protected $fileService;
    protected $productService;
    public function __construct(FileService $fileService, ProductService $productService)
    {
        $this->fileService = $fileService;
        $this->productService = $productService;
    }
    /** VALIDATION 
     * Validate the file input.
     *
     * @param Request $request The HTTP request containing the file input.
     * @return void
     */
    public function validateFile(Request $request): ?string
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpg,jpeg,png,zip,rar|max:20000',
            'base_directory' => 'required|string'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        } else {
            return "true";
        }
    }
    public function validateAvatar(Request $request): ?string
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|file|mimes:jpg,jpeg,png|max:10000',
            'base_directory' => 'required|string'
        ]);
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        } else {
            return "true";
        }
    }


    /**
     * Retrieve a list of files and directories in a given directory.
     *
     * @param string $directory The directory to list the contents of.
     * @return array An array containing files and directories.
     */
    public function getListItemInDirectory(string $directory): array
    {
        $complete_direct = str_replace(".", "/", $directory);
        $directory_path = base_path($complete_direct);

        $result = [
            'base_directory' => $complete_direct,
            'files' => [],
            'directories' => []
        ];

        $items = scandir($directory_path);

        foreach ($items as $item) {
            // Skip special entries '.' and '..'
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (is_dir($directory_path . '/' . $item)) {
                $result['directories'][] = $item;
            } else {

                $file_path = $directory_path . '/' . $item;
                $file_size = filesize($file_path);
                $formatted_size = $this->formatFileSize($file_size);
                $last_modified = date("Y-m-d H:i:s", filemtime($file_path));

                // Get the MIME type of the file
                $mime_type = mime_content_type($file_path);

                $getFilesInFolderDTO = new GetFilesInFolderDTO([
                    'name' => $item,
                    'filepath' => $complete_direct . '/' . $item,
                    'size' => $formatted_size,
                    'type' => substr($item, strrpos($item, '.') + 1),
                    'mine' => $mime_type,
                    'last_modified' => $last_modified
                ]);
                $result['files'][] = $getFilesInFolderDTO;
            }
        }

        return $result;
    }

    /**
     * Format file size to a human-readable format (e.g., KB, MB, GB).
     *
     * @param int $size File size in bytes.
     * @return string The formatted size with a unit (e.g., '1.23 MB').
     */
    public function formatFileSize(int $size): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $size >= 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * Handle file upload.
     *
     * @param Request $request The HTTP request containing the file.
     * @return CreateFileDTO|null Returns a DTO containing file details on success, null on failure.
     */
    public function uploadFile(Request $request): CreateFileDTO|string
    {
        $this->validateFile($request);

        if ($request->file()) {
            return $this->saveFile($request);
        }
        return "No file was uploaded";
    }

    /**
     * Handle multiple file uploads.
     *
     * @param Request $request The HTTP request containing multiple files.
     * @return array An array of CreateFileDTOs for each uploaded file.
     */
    public function uploadMultiFile(Request $request): array
    {
        $this->validateFile($request);

        $fileDTOs = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileDTOItem = $this->saveFile($file);
                $fileDTOs[] = $fileDTOItem;
            }
        }
        return $fileDTOs;
    }

    /**
     * Save an uploaded file and return its details as a DTO.
     *
     * @param Request $request The HTTP request containing the file.
     * @return CreateFileDTO The DTO containing the uploaded file details.
     */
    public function saveFile(Request $request): CreateFileDTO
    {
        // Get the file from the request
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $size = $file->getSize();
        $baseDirectory = base_path($request->input('base_directory'));

        if (!file_exists($baseDirectory)) {
            mkdir($baseDirectory, 0777, true);
        }

        $filename = $this->getUniqueFilename($baseDirectory, $filename);

        $finalPath = $file->move($baseDirectory, $filename);
        $formattedSize = $this->formatFileSize($size);
        $dateModified = date("Y-m-d H:i:s", filemtime($finalPath));

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];

        $link =  $protocol . $host . '/' . $request->input('base_directory') . '/' . $filename;
        return new CreateFileDTO([
            'name' => $filename,
            'size' => floatval(explode(' ', $formattedSize)[0]),
            'unitSize' => explode(' ', $formattedSize)[1],
            'path' => $finalPath,
            'link' => $link,
            'date_modified' => $dateModified,
        ]);
    }

    /**
     * Generate a unique filename by appending (copy), (copy 1), etc., if needed.
     */
    private function getUniqueFilename(string $directory, string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $baseName = pathinfo($filename, PATHINFO_FILENAME);
        $uniqueFilename = $filename;
        $counter = 0;

        while (file_exists($directory . '/' . $uniqueFilename)) {
            $counter++;
            $suffix = $counter === 1 ? "(copy)" : "(copy $counter)";
            $uniqueFilename = $baseName . " $suffix." . $extension;
        }

        return $uniqueFilename;
    }

    /**
     * Remove a file from the specified directory.
     *
     * @param Request $request The HTTP request containing the file and directory names.
     * @return RemoveFileDTO
     */
    public function removeFile(Request $request): RemoveFileDTO
    {
        $fileName = $request->input('file');
        $baseDirectory = $request->input('base_directory');
        $relativePath = $baseDirectory . '/' . $fileName;
        $filePath = base_path($baseDirectory . '/' . $fileName);

        if ($this->isSeriousFile($baseDirectory, $fileName)) {
            return new RemoveFileDTO([
                'status' => false,
                'message' => 'The file ' . $relativePath . ' be used!'
            ]);
        }

        if (file_exists($filePath)) {
            if ($this->productService->isUsedInProduct($filePath)) {
                return new RemoveFileDTO([
                    'status' => false,
                    'message' => 'File is already used, can\'t be deleted!'
                ]);
            }

            unlink($filePath);

            return new RemoveFileDTO([
                'status' => true,
                'message' => 'File deleted successfully.'
            ]);
        }

        return new RemoveFileDTO([
            'status' => false,
            'message' => 'File not found.'
        ]);
    }

    /**
     * Rename a file in the specified directory.
     *
     * @param string $basePath The base directory where the file is located.
     * @param string $currentFileName The current name of the file.
     * @param string $newName The new name for the file.
     * @param bool $isImage Whether the file is an image (optional).
     * @return string The status message after attempting the rename.
     */
    public function renameFile(string $basePath, string $currentFileName, string $newName, bool $isImage = false): string
    {
        $extensionName = substr($currentFileName, strrpos($currentFileName, '.'));
        $currentFilePath = base_path($basePath . '/' . $currentFileName);
        $currentFilePath = str_replace('\\', '/', $currentFilePath);
        $newFilePath = base_path($basePath . '/' . $newName . $extensionName);
        $newFilePath = str_replace('\\', '/', $newFilePath);
        if (File::exists($currentFilePath)) {
            if (strcmp($currentFilePath, $newFilePath) === 0) {
                return "This name already exists!";
            }

            if (File::exists($newFilePath)) {
                return "This name already exists!";
            }

            if ($this->isSeriousFile($basePath, $currentFileName)) {
                return "File already be used";
            }

            if (File::move($currentFilePath, $newFilePath)) {
                $oldFileUrl = url($currentFilePath);
                $newFileUrl = url($newFilePath);

                if ($isImage) {
                    $this->fileService->updateImageFilePath($oldFileUrl, $newFileUrl);
                } else {
                    $this->productService->updateFilePathInProduct($oldFileUrl, $newFileUrl);
                }

                return "Rename successful";
            }

            return "Can't rename this file!";
        }

        return "File not found!";
    }

    /**
     * Move a file from one directory to another.
     *
     * @param string $oldBase The current directory of the file.
     * @param string $newBase The new directory to move the file to.
     * @param string $fileName The name of the file to move.
     * @return string|true Success or error message.
     */
    public function moveFile(string $oldBase, string $newBase, string $fileName): string|true
    {
        $currentFilePath = $oldBase . '/' . $fileName;
        $newFilePath = $newBase . '/' . $fileName;

        if (file_exists($newFilePath)) {
            return "File already exists";
        }

        if ($currentFilePath === $newFilePath) {
            return "File name already exists!";
        }

        if ($this->isSeriousFile($oldBase, $fileName)) {
            return "File already be used";
        }

        if (!copy($currentFilePath, $newFilePath)) {
            return "Can't move file!";
        }

        $request  = Request::create('/files/remove', 'POST', [
            'file' => $fileName,
            'base_directory' => $oldBase
        ]);
        $removeFile = $this->removeFile($request);
        if (!$removeFile->status) {
            return $removeFile->message;
        }

        return true;
    }

    /**
     * to check a file can be deleted/updated (be used in serious cases)
     * 
     * @param string $baseDirectory
     * @param string $fileName
     * @return bool
     */
    public function isSeriousFile(string $baseDirectory, string $fileName): bool
    {
        $currentFilePath = $baseDirectory . '/' . $fileName;
        $currentFilePath = str_replace('\\', '/', $currentFilePath);

        if (
            ProductImage::where('product_image_path', $currentFilePath)->get()->count() ||
            !!SlideshowImage::where('slideshow_image_url', $currentFilePath)->get()->count() ||
            !!Post::where('post_image_path', $currentFilePath)->get()->count() ||
            !!Category::where('category_image_path', $currentFilePath)->get()->count()
        ) {
            return true;
        }

        return false;
    }
}
