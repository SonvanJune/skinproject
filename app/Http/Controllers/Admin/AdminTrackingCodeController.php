<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TrackingCodeService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AdminTrackingCodeController extends Controller
{
    protected TrackingCodeService $trackingCodeService;
    protected UserService $userService;
    public function __construct(TrackingCodeService $trackingCodeService, UserService $userService)
    {
        $this->trackingCodeService = $trackingCodeService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the tracking codes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $page = $request->query('page');
        $per_page = $request->query('per_page');

        if (!$page || !is_numeric($page) || $page < 1) {
            $page = 1;
        }

        if (!$per_page || !is_numeric($per_page) || $per_page < 1) {
            $per_page = UserService::PER_PAGE;
        }

        $request->merge(["page" => $page, "per_page" => $per_page]);

        $paginatedDTO = $this->trackingCodeService->getAllTrackingCodes($request);

        $HTML_TYPE = TrackingCodeService::TRACKING_CODE_TYPE_HTML;
        $CSS_TYPE = TrackingCodeService::TRACKING_CODE_TYPE_CSS;
        $JS_TYPE = TrackingCodeService::TRACKING_CODE_TYPE_JAVASCRIPT;

        return view(
            'admin.tracking_codes.index',
            compact('paginatedDTO', 'HTML_TYPE', 'CSS_TYPE', 'JS_TYPE')
        );
    }

    /**
     * Show the form for creating a new tracking code.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $HTML_TYPE = TrackingCodeService::TRACKING_CODE_TYPE_HTML;
        $CSS_TYPE = TrackingCodeService::TRACKING_CODE_TYPE_CSS;
        $JS_TYPE = TrackingCodeService::TRACKING_CODE_TYPE_JAVASCRIPT;

        return view(
            'admin.tracking_codes.create',
            compact('HTML_TYPE', 'CSS_TYPE', 'JS_TYPE')
        );
    }

    /**
     * Store a newly created tracking code in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $code = $request->get('tracking_code');
        $type = $request->get('tracking_code_type');

        if (!$code) {
            return back()->with('error', 'Tracking code is required');
        }

        if (!$type) {
            return back()->with('error', 'Tracking code comes with invalid language');
        }

        $storedTrackingCodeDTO = $this->trackingCodeService->createTrackingCode($request);

        if (is_string($storedTrackingCodeDTO)) {
            return back()->with('error', $storedTrackingCodeDTO);
        }

        return redirect()->route('admin.tracking-codes')->with('success', 'Tracking code stored successfully!');
    }

    /**
     * Update the specified tracking code in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $updatedTrackingCodeDTO = $this->trackingCodeService->updateTrackingCode($request);

        if (is_string($updatedTrackingCodeDTO)) {
            return back()->with('error', $updatedTrackingCodeDTO);
        }

        return redirect()->route('admin.tracking-codes')->with('success', 'Tracking code updated successfully!');
    }

    /**
     * Remove the specified tracking code from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $id)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $request->merge(['tracking_code_id' => $id]);

        $trackingCode = $this->trackingCodeService->deleteTrackingCode($request);

        if (is_string($trackingCode)) {
            return back()->with('error', $trackingCode);
        }

        if ($trackingCode) {
            return redirect()->route('admin.tracking-codes')->with('success', 'Tracking code deleted successfully!');
        } else {
            return back()->with('error', 'Failed to delete tracking code');
        }
    }
}
