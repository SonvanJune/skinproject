<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PaypalService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AdminPaypalSettingController extends Controller
{
    protected $userService;
    protected $paypalService;
    public function __construct(UserService $userService, PaypalService $paypalService)
    {
        $this->userService = $userService;
        $this->paypalService = $paypalService;
    }

    public function showForm(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService); 
        parent::checkAdminInPage($user);
        $paypalData = $this->paypalService->getCredential($this->userService);
        return view('admin.paypal-setting.index', compact('paypalData'));
    }

    public function save(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService); 
        parent::checkAdminInPage($user);
        $edit = $this->paypalService->saveCredentials($request , $this->userService);
        if(parent::checkIsString($edit)){
            return redirect()->back()->with('error', $edit);
        }
        return redirect()->route('admin.paypal.form')->with('success', $edit->message);
    }
}
