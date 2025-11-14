<?php

namespace App\Services;

use App\DTOs\CreateTrackingCodeDTO;
use App\DTOs\GetTrackingCodeDTO;
use App\DTOs\PaginatedDTO;
use App\DTOs\UpdateTrackingCodeDTO;
use App\Models\TrackingCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Service class for managing tracking codes.
 */
class TrackingCodeService
{
    public const TRACKING_CODE_TYPE_HTML = 1;
    public const TRACKING_CODE_TYPE_CSS = 2;
    public const TRACKING_CODE_TYPE_JAVASCRIPT = 3;
    public const TRACKING_CODE_TYPE_DELETE = -1;

    public const TRACKING_CODE_PATH_CSS = 'css/track-css.css';
    public const TRACKING_CODE_PATH_JAVASCRIPT = 'js/track-js.js';
    public const TRACKING_CODE_PATH_HTML = 'views/component/tracking-codes/index.blade.php';

    public const PER_PAGE_DEFAULT = 10;
    public const DEFAULT_PAGE = 1;

    /**
     * get all tracking codes and paginate them
     *
     * @param Request $request The HTTP request containing tracking code data.
     * @return mixed The created tracking code DTO or error message.
     */
    public function getAllTrackingCodes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|numeric|integer',
            'per_page' => 'nullable|numeric|integer',
            'key' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $perPage = $request->input('per_page', self::PER_PAGE_DEFAULT);
        $page = $request->input('page', self::DEFAULT_PAGE);
        $skip = ($page - 1) * $perPage;
        $key = $request->input('key', '');

        $trackingCodes = TrackingCode::orderBy('updated_at', 'desc')
            ->where('tracking_code_type', '<>', self::TRACKING_CODE_TYPE_DELETE)
            ->skip($skip)
            ->take($perPage)
            ->get();

        foreach ($trackingCodes as $code) {
            switch ($code->tracking_code_type) {
                case self::TRACKING_CODE_TYPE_HTML:
                    $code->tracking_code = $this->formatHtmlWithPrettier($code->tracking_code) ?? $code->tracing_code;
                    break;
                case self::TRACKING_CODE_TYPE_CSS:
                    $code->tracking_code = $this->formatCssWithPrettier($code->tracking_code) ?? $code->tracing_code;
                    break;
                case self::TRACKING_CODE_TYPE_JAVASCRIPT:
                    $code->tracking_code = $this->formatJsWithPrettier($code->tracking_code) ?? $code->tracing_code;
                    break;
                default:
            }
        }

        $total = TrackingCode::orderBy('updated_at', 'desc')
            ->where('tracking_code_type', '<>', self::TRACKING_CODE_TYPE_DELETE)
            ->count();

        return PaginatedDTO::fromData(GetTrackingCodeDTO::fromModels($trackingCodes), $page, $perPage, $total, $key ?? "");
    }

    /**
     * Creates a new tracking code.
     *
     * @param Request $request The HTTP request containing tracking code data.
     * @return mixed The created tracking code DTO or error message.
     */
    public function createTrackingCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tracking_code' => 'required',
            'tracking_code_type' => 'required',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $validateTrackingCode = self::validateTrackingCode($request);
        if ($validateTrackingCode) {
            return $validateTrackingCode;
        }

        $editFile = $this->getHTMLAndEditFileCssJs($request);
        if (is_string($editFile)) {
            return $editFile;
        }

        DB::beginTransaction();
        try {
            $trackingCode = new TrackingCode();
            $trackingCode->tracking_code_id = Str::uuid();
            $trackingCode->tracking_code = $request->tracking_code;
            $trackingCode->tracking_code_type = $request->tracking_code_type;
            $trackingCode->save();

            DB::commit();

            return CreateTrackingCodeDTO::fromModel($trackingCode);
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Create tracking code failed: ' . $e->getMessage();
        }
    }

    /**
     * Updates an existing tracking code.
     *
     * @param Request $request The HTTP request containing updated tracking code data.
     * @return mixed The updated tracking code DTO or error message.
     */
    public function updateTrackingCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tracking_code_id' => 'required',
            'tracking_code' => 'required',
            'tracking_code_type' => 'required'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $trackingCode = TrackingCode::where('tracking_code_id', $request->input('tracking_code_id'))->first();
        if (!$trackingCode) {
            return 'Tracking code not found';
        }

        $trackingCodeType = $request->input('tracking_code_type');
        $changedType = $trackingCode->tracking_code_type != $trackingCodeType;

        $validateTrackingCode = self::validateTrackingCode($request, $changedType);
        if ($validateTrackingCode) {
            return $validateTrackingCode;
        }

        $editFile = $this->getHTMLAndEditFileCssJs($request);
        if (is_string($editFile)) {
            return $editFile;
        }

        DB::beginTransaction();
        try {
            $trackingCode->tracking_code = $request->input('tracking_code');
            $trackingCode->tracking_code_type = $request->input('tracking_code_type');
            $trackingCode->save();

            DB::commit();
            return UpdateTrackingCodeDTO::fromModel($trackingCode);
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Update tracking code failed: ' . $e->getMessage();
        }
    }

    /**
     * Marks a tracking code as deleted.
     *
     * @param Request $request The HTTP request containing the tracking code ID.
     * @return mixed The updated tracking code model or error message.
     */
    public function deleteTrackingCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tracking_code_id' => 'required',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $trackingCode = TrackingCode::where('tracking_code_id', $request->input('tracking_code_id'))->first();
        if (!$trackingCode) {
            return 'Tracking code not found';
        }

        switch ($trackingCode->tracking_code_type) {
            case self::TRACKING_CODE_TYPE_HTML:
                $htmlFilePath = resource_path(self::TRACKING_CODE_PATH_HTML);
                if (!file_exists($htmlFilePath)) {
                    return 'Html file not found';
                }
                $trackingCodeString = '<div></div>';
                file_put_contents($htmlFilePath, $trackingCodeString);
                if (function_exists('opcache_invalidate')) {
                    opcache_invalidate(resource_path($htmlFilePath), true);
                }

            case self::TRACKING_CODE_TYPE_CSS:
                $cssFilePath = public_path(self::TRACKING_CODE_PATH_CSS);
                if (!file_exists($cssFilePath)) {
                    return 'CSS file not found';
                }
                $trackingCodeString = "/* Tracking CSS - empty */";

                file_put_contents($cssFilePath, $trackingCodeString);
                if (function_exists('opcache_invalidate')) {
                    opcache_invalidate(resource_path($cssFilePath), true);
                }

            case self::TRACKING_CODE_TYPE_JAVASCRIPT:
                $jsFilePath = public_path(self::TRACKING_CODE_PATH_JAVASCRIPT);
                if (!file_exists($jsFilePath)) {
                    return 'JavaScript file not found';
                }

                $trackingCodeString = '// Tracking JS - empty';

                file_put_contents($jsFilePath, $trackingCodeString);
                if (function_exists('opcache_invalidate')) {
                    opcache_invalidate(resource_path($jsFilePath), true);
                }
        }

        DB::beginTransaction();
        try {
            $trackingCode->tracking_code_type = self::TRACKING_CODE_TYPE_DELETE;
            $trackingCode->save();

            DB::commit();

            return $trackingCode;
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Delete tracking code failed: ' . $e->getMessage();
        }
    }

    /**
     * Determines if the provided string contains HTML code.
     *
     * @param string $code The tracking code to evaluate.
     * @return bool True if HTML is detected, false otherwise.
     */
    public function isHtml($code)
    {
        return preg_match('/<!--[\s\S]*?-->|<([a-zA-Z0-9-]+)([^>]*)>(.*?)<\/\1>|<([a-zA-Z0-9-]+)([^>]*)\/?>/i', $code);
    }

    /**
     * Determines if the provided string contains CSS code.
     *
     * @param string $code The tracking code to evaluate.
     * @return bool True if CSS is detected, false otherwise.
     */
    public function isCss($code)
    {
        return preg_match('/\/\*[\s\S]*?\*\/|\b([a-zA-Z0-9.#:_-]+)\s*\{([^}]+)\}|\b([a-zA-Z-]+)\s*:\s*([^;]+);|\@media\s*\(([^)]+)\)\s*\{([\s\S]*?)\}/i', $code);
    }

    /**
     * Determines if the provided string contains JavaScript code.
     *
     * @param string $code The tracking code to evaluate.
     * @return bool True if JavaScript is detected, false otherwise.
     */
    public function isJs($code)
    {
        return preg_match('/(?:\/\/.*|\/\*[\s\S]*?\*\/)|(?:var|let|const)\s+([a-zA-Z_$][a-zA-Z0-9_$]*)\s*=\s*(.*?);|function\s+([a-zA-Z_$][a-zA-Z0-9_$]*)\s*\(([^)]*)\)\s*\{([\s\S]*?)\}|([a-zA-Z_$][a-zA-Z0-9_$]*)\s*=\s*\(([^)]*)\)\s*=>\s*\{([\s\S]*?)\}|console\.log\((.*?)\);/i', $code);
    }

    /**
     * Retrieves tracking codes by type and updates corresponding files.
     *
     * @param Request $request The HTTP request containing the tracking code type.
     * @return mixed The concatenated tracking codes or error message.
     */
    public function getHTMLAndEditFileCssJs(Request $request)
    {
        $trackingCodeString = '';

        switch ($request->input('tracking_code_type')) {
            case self::TRACKING_CODE_TYPE_HTML:
                $htmlFilePath = resource_path(self::TRACKING_CODE_PATH_HTML);
                if (!file_exists($htmlFilePath)) {
                    return 'Html file not found';
                }
                $trackingCodeString = $request->tracking_code;
                file_put_contents($htmlFilePath, $trackingCodeString);
                if (function_exists('opcache_invalidate')) {
                    opcache_invalidate(resource_path($htmlFilePath), true);
                }
                return null;

            case self::TRACKING_CODE_TYPE_CSS:
                $cssFilePath = public_path(self::TRACKING_CODE_PATH_CSS);
                if (!file_exists($cssFilePath)) {
                    return 'CSS file not found';
                }

                $trackingCodeString = $request->tracking_code;

                file_put_contents($cssFilePath, $trackingCodeString);
                if (function_exists('opcache_invalidate')) {
                    opcache_invalidate(resource_path($cssFilePath), true);
                }
                return null;

            case self::TRACKING_CODE_TYPE_JAVASCRIPT:
                $jsFilePath = public_path(self::TRACKING_CODE_PATH_JAVASCRIPT);
                if (!file_exists($jsFilePath)) {
                    return 'JavaScript file not found';
                }

                $trackingCodeString = $request->tracking_code;

                file_put_contents($jsFilePath, $trackingCodeString);
                if (function_exists('opcache_invalidate')) {
                    opcache_invalidate(resource_path($jsFilePath), true);
                }
                return null;
            default:
                return 'Invalid tracking code type';
        }
    }

    /**
     * Validates the tracking code content and ensures uniqueness.
     *
     * @param Request $request The HTTP request containing tracking code data.
     * @return string|null An error message if validation fails, otherwise null.
     */
    public function validateTrackingCode(Request $request, bool $changedType = true)
    {
        $trackingCode = $request->input('tracking_code');
        $trackingCodeType = $request->input('tracking_code_type');

        if ($trackingCode) {
            if (preg_match('/[\n\t\r]/', $trackingCode)) {
                $trackingCode = str_replace(["\n", "\t"], "", $trackingCode);
            }

            $isHtml = $this->isHtml($trackingCode);
            $isCss = $this->isCss($trackingCode);
            $isJs = $this->isJs($trackingCode);

            switch ($trackingCodeType) {
                case self::TRACKING_CODE_TYPE_HTML:
                    if (!$isHtml) {
                        return 'Tracking code must be valid HTML.';
                    }
                    break;

                case self::TRACKING_CODE_TYPE_CSS:
                    if (!$isCss) {
                        return 'Tracking code must be valid CSS.';
                    }
                    break;

                case self::TRACKING_CODE_TYPE_JAVASCRIPT:
                    if (!$isJs) {
                        return 'Tracking code must be valid JavaScript.';
                    }
                    break;

                default:
                    return 'Invalid tracking code type specified.';
            }
        }

        if ($changedType && TrackingCode::where('tracking_code_type', $trackingCodeType)->exists()) {
            return 'Tracking code already exists for the specified type.';
        }

        return null;
    }

    /**
     * format html content with prettier
     * 
     * @param string $html
     * @return string formatted html content
     */
    public function formatHtmlWithPrettier($html): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'prettier_') . '.html';

        file_put_contents($tempFile, $html);

        $prettierPath = base_path('node_modules/.bin/prettier');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $prettierPath .= '.cmd';
        }

        $cmd = "\"$prettierPath\" \"$tempFile\" --parser html";
        $output = shell_exec($cmd);

        unlink($tempFile);

        return $output ?? $html;
    }

    /**
     * format css content with prettier
     * 
     * @param string $css
     * @return string formatted css content
     */
    function formatCssWithPrettier($css): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'prettier_') . '.css';

        file_put_contents($tempFile, $css);

        $prettierPath = base_path('node_modules/.bin/prettier');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $prettierPath .= '.cmd';
        }

        $cmd = "\"$prettierPath\" \"$tempFile\" --parser css";
        $output = shell_exec($cmd);

        unlink($tempFile);

        return $output ?? $css;
    }

    /**
     * format html content with prettier
     * 
     * @param string $js
     * @return string formatted js content
     */
    function formatJsWithPrettier($js): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'prettier_') . '.js';

        file_put_contents($tempFile, $js);

        $prettierPath = base_path('node_modules/.bin/prettier');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $prettierPath .= '.cmd';
        }

        $cmd = "\"$prettierPath\" \"$tempFile\" --parser babel";
        $output = shell_exec($cmd);

        unlink($tempFile);

        return $output ?? $js;
    }
}
