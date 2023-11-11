<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ResetPasswordRequest;
use Illuminate\Http\Request;
use Ichtrojan\Otp\Otp;
use Hash;

class ResetPasswordController extends Controller
{
    private $otp;

    public function __construct() {
        $this->otp = new Otp();
    }

    public function passwordReset(ResetPasswordRequest $request) {
        
    }
}
