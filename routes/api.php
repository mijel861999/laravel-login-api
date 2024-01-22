<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\Api\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

Route::post('password/email', [ForgetPasswordController::class, 'forgotPassword']);

Route::post('password/reset', [ResetPasswordController::class, 'passwordReset']);

Route::post('resetPassword', [AuthController::class, 'sendRequestForRecoverpassword']);

Route::get('sendEmailPrueba', [AuthController::class, 'sendEmail']);
