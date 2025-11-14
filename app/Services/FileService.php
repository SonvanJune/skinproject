<?php

namespace App\Services;

use App\DTOs\ZipDTO;
use App\Mail\DownloadFileMail;
use App\Models\Product;
use App\Models\User;
use DirectoryIterator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use TheSeer\Tokenizer\Exception;
use ZipArchive;

class FileService
{

    public const FILE_NAME_REGEX = "/^[\w,\s-]+\.[A-Za-z]{3}$/";

    //constructor
    public function __construct() {}

    /**
     * Update the image file path for a product in the 'product_images' table.
     *
     * @param string $oldImageFilePath
     * @param string $newImageFilePath
     * @return int
     */
    public function updateImageFilePath($oldImageFilePath, $newImageFilePath)
    {
        $productImage = DB::table('product_images')
            ->where('product_image_path', $oldImageFilePath)
            ->update(['product_image_path' => $newImageFilePath]);

        return $productImage;
    }

    /**
     * Check if a file path is an image file.
     *
     * @param string $filePath
     * @return bool
     */
    public function isImagePath($filePath)
    {
        $extensionFile = strtolower(substr($filePath, strrpos($filePath, '.')));
        $allowExtensions = ['.png', '.jpg', ',jpeg'];

        return in_array($extensionFile, $allowExtensions);
    }

    /**
     * Download file with the specified path and file name
     *
     * @param Request $request includes path as the file's local path, file name as the downloaded file name
     * @return string if there is any errors during processing
     * @return BinaryFileResponse as the response including the file
     */
    public function download(Request $request, UserService $userService): string| BinaryFileResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'path' => 'required|string',
                'file_name' => 'required|max:255|regex:' . self::FILE_NAME_REGEX
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $key = config("app.key", UserService::DEFAULT_ENCRYPT_KEY);

        $decryptedPath = $userService->decrypt_with_key($request->path, $key);

        if (!file_exists($decryptedPath)) {
            echo "Cannot download file: file not found";
        }

        return response()->download($decryptedPath, $request->file_name);
    }

    /**
     * Creates a password-protected (AES-256) zip archive from a source folder.
     *
     * This function takes a directory path and a password, compresses the directory's
     * contents into a zip file using AES-256 encryption, and returns the binary content
     * of the zip file as a string. It uses a temporary file during the process,
     * which is automatically deleted afterwards. Requires PHP >= 7.2 and the Zip extension
     * for AES encryption. On failure, it returns a descriptive error message string.
     *
     * @param string $sourceFolderPath The absolute path to the directory that needs to be zipped.
     * The directory must exist and be readable by the PHP process.
     * @param string $password         The password to encrypt the zip file with (using AES-256).
     * An empty password might lead to unexpected behavior or weak encryption depending on the unzipping tool.
     *
     * @return ZipDtostring Returns the binary string content of the generated zip file on success.
     * Returns a string containing a specific error message on failure (e.g., if the
     * source directory doesn't exist, encryption cannot be set, or file operations fail).
     * The calling code should check the return value (e.g., by verifying if it starts
     * with 'PK' which typically indicates zip data) to distinguish between success
     * and failure.
     */
    function createPasswordProtectedZipInMemory(string $sourceFolderPath, string $password): string|ZipDTO
    {
        $basePath = base_path();
        $filePath = $basePath . '/' . ltrim($sourceFolderPath, '/');
        $filePath = str_replace('\\', '/', $filePath);
        if (!is_dir($filePath)) {
            return "Error: Source path does not exist or is not a directory: " . $sourceFolderPath;
        }

        $canEncrypt = class_exists('ZipArchive') && defined('ZipArchive::EM_AES_256');
        if (!$canEncrypt) {
            return "Error: AES encryption requires PHP >= 7.2 with the Zip extension enabled.";
        }

        $tempZipFilePath = tempnam(sys_get_temp_dir(), 'laravel_zip_');
        if ($tempZipFilePath === false) {
            return "Error: Could not create a temporary file in the system's temporary directory.";
        }

        $zip = new ZipArchive();

        try {
            if ($zip->open($tempZipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                return "Error: Could not open temporary zip file for writing: " . $tempZipFilePath;
            }

            if (!$zip->setPassword($password)) {
                $zip->close();
                return "Error: Failed to set password on the ZipArchive object.";
            }

            $basePathLength = strlen($filePath);
            self::addFilesToZipRecursive($filePath, $zip, $basePathLength, $password);

            if (!$zip->close()) {
                return "Error: Failed to close the zip archive after writing.";
            }

            $zipContent = file_get_contents($tempZipFilePath);

            if ($zipContent === false) {
                return "Error: Failed to read the content of the generated temporary zip file.";
            }

            return ZipDTO::create($zipContent);
        } catch (\Exception $e) {
            return "Exception Error: An unexpected error occurred during zip creation: " . $e->getMessage();
        } finally {
            if (isset($tempZipFilePath) && file_exists($tempZipFilePath)) {
                @unlink($tempZipFilePath);
            }
        }
    }

    function createPasswordProtectedZipWithMorePathInMemory(Collection $products, string $password, bool $getFilePath = false): string|ZipDTO
    {
        $canEncrypt = class_exists('ZipArchive') && defined('ZipArchive::EM_AES_256');
        if (!$canEncrypt) {
            return "Error: AES encryption requires PHP >= 7.2 with the Zip extension enabled.";
        }

        $tempZipFilePath = tempnam(sys_get_temp_dir(), 'laravel_zip_');
        if ($tempZipFilePath === false) {
            return "Error: Could not create a temporary file in the system's temporary directory.";
        }

        $zip = new ZipArchive();

        try {
            if ($zip->open($tempZipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                @unlink($tempZipFilePath);
                return "Error: Could not open temporary zip file for writing: " . $tempZipFilePath;
            }

            if (!$zip->setPassword($password)) {
                $zip->close();
                @unlink($tempZipFilePath);
                return "Error: Failed to set password on the ZipArchive object.";
            }

            foreach ($products as $product) {
                $basePath = base_path();
                $filePath = $basePath . '/' . ltrim($product->product_file_path, '/');
                $filePath = str_replace('\\', '/', $filePath);
                if (!is_dir($filePath)) {
                    throw new Exception("Error: Source path does not exist or is not a directory: " . $filePath);
                }

                $basePathLength = strlen($filePath);
                $file_name = str_replace(' ', '', $product->product_name);
                $subFolderName = basename($file_name);

                if ($zip->addEmptyDir($subFolderName) === false) {
                    $zip->close();
                    @unlink($tempZipFilePath);
                    throw new Exception("Error: Failed to add directory to zip: " . $subFolderName);
                }
                self::addFilesToZipProductRecursive($filePath, $subFolderName, $tempZipFilePath, $zip, $password, $basePathLength);
            }

            if (!$zip->close()) {
                @unlink($tempZipFilePath);
                return "Error: Failed to close the zip archive after writing.";
            }

            $zipContent = file_get_contents($tempZipFilePath);

            if ($zipContent === false) {
                @unlink($tempZipFilePath);
                return "Error: Failed to read the content of the generated temporary zip file.";
            }

            if ($getFilePath == true) {
                return $tempZipFilePath;
            }
            return ZipDTO::create($zipContent);
        } catch (\Exception $e) {
            return "Exception Error: An unexpected error occurred during zip creation: " . $e->getMessage();
        } finally {
            if ($getFilePath == false) {
                if (isset($tempZipFilePath) && file_exists($tempZipFilePath)) {
                    @unlink($tempZipFilePath);
                }
            }
        }
    }

    function addFilesToZipRecursive(string $folderPath, ZipArchive $zip, int $basePathLength, string $password): void
    {
        $items = new DirectoryIterator($folderPath);
        foreach ($items as $item) {
            if ($item->isDot()) continue;

            $fullPath = $item->getRealPath();
            $relativePath = substr($fullPath, $basePathLength + 1);

            if ($item->isDir()) {
                $zip->addEmptyDir($relativePath);
                self::addFilesToZipRecursive($fullPath, $zip, $basePathLength, $password);
            } elseif ($item->isFile()) {
                if (!$zip->addFile($fullPath, $relativePath)) {
                    throw new Exception("Error: Failed to add file to zip: " . $fullPath);
                }

                if (!$zip->setEncryptionName($relativePath, ZipArchive::EM_AES_256, $password)) {
                    throw new Exception("Error: Failed to set AES encryption for file: " . $relativePath);
                }
            }
        }
    }

    function addFilesToZipProductRecursive(string $filePath, string $subFolderName, string $tempZipFilePath, ZipArchive $zip, string $password, int $basePathLength, string $relativePathInSubFolderNew = ""): void
    {
        $files = new DirectoryIterator($filePath);

        foreach ($files as $file) {
            if ($file->isDot()) continue;
            if ($file->isDir()) {
                $fullPath = $file->getRealPath();
                $relativePath = substr($fullPath, $basePathLength + 1);
                $relativePathInSubFolder = $subFolderName . '/' . $relativePath;
                $zip->addEmptyDir($relativePathInSubFolder);
                self::addFilesToZipProductRecursive($fullPath, $subFolderName, $tempZipFilePath, $zip, $password, $basePathLength, $relativePathInSubFolder);
            } elseif ($file->isFile()) {
                $filePath = $file->getRealPath();
                $relativePath = $file->getFilename();
                if ($relativePathInSubFolderNew != "") {
                    $subFolderName = $relativePathInSubFolderNew;
                }
                $relativePathInSubFolder = $subFolderName . '/' . $relativePath;
                if ($zip->addFile($filePath, $relativePathInSubFolder) === false) {
                    $zip->close();
                    @unlink($tempZipFilePath);
                    throw new Exception("Error: Failed to add file to zip: " . $filePath);
                }

                if (!$zip->setEncryptionName($relativePathInSubFolder, ZipArchive::EM_AES_256, $password)) {
                    $zip->close();
                    @unlink($tempZipFilePath);
                    throw new Exception("Error: Failed to set AES encryption for file: " . $relativePathInSubFolder);
                }
            }
        }
    }
}
