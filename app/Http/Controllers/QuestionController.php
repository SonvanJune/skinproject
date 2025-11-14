<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FileService;
use App\Services\OTPService;
use App\Services\UserService;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public OTPService $otpservice;
    public FileService $fileService;
    public UserService $userService;

    public function __construct(OTPService $otpservice, FileService $fileService, UserService $userService)
    {
        $this->otpservice = $otpservice;
        $this->fileService = $fileService;
        $this->userService = $userService;
    }
}
