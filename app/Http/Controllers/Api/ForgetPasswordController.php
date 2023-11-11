<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Api\ForgetPasswordRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Models\User;
use App\Notifications\ResetPasswordVerificationNotification;

class ForgetPasswordController extends Controller
{
    public function forgotPassword(ForgetPasswordRequest $request) {
        $input = $request->only("email");
        $user = User::where("email", $input)->first();
        $user->notify(new ResetPasswordVerificationNotification());
        $success["success"] = true;
        return response()->json($success, 200);
    }
}
