<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\recuperarContrasenaMail;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    public function register(Request $request) {

        // Validación de los datos
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422); // Cambia el código de estado según tu preferencia
        }

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            return response(["ok" => false, "message" => "El correo electrónico ya está en uso."],  HttpFoundationResponse::HTTP_CONFLICT);
        }

        // Alta del usuario
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        Mail::to('mijel.dev@gmail.com')->send(new ResetPasswordMail('token_de_prueba'));


        // return response($user, HttpFoundationResponse::HTTP_CREATED);

        return response([
            "ok" => true,
            "message" => "Registro exitoso",
            "user" => $user,  // Agregar la información del usuario aquí
        ], HttpFoundationResponse::HTTP_CREATED);
    }

    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422); // Cambia el código de estado según tu preferencia
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie("cookie_token", $token, 60*24);
            return response([
            "ok" => true,
            "message" => "Ingreso exitoso",
            "user" => $user,  // Agregar la información del usuario aquí
            "token" => $token
            ], HttpFoundationResponse::HTTP_OK)->withoutCookie($cookie);
        } else {
            return response(["ok" => false,"message" => "Credenciales inválidas"],  HttpFoundationResponse::HTTP_UNAUTHORIZED);
        }
    }


    public function sendRequestForRecoverpassword(Request $request) {
        // Validación de los datos
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $existingUser = User::where('email', $request->email)->first();

        if (!$existingUser) {
            return response(["ok" => false, "message" => "El correo electrónico no existe."],  HttpFoundationResponse::HTTP_CONFLICT);
        }

        // TODO: Generar en la base de datos el token con el usuario
        $token = Str::random(60);
        $expiration = Carbon::now()->addMinutes(60);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );

        $this->sendEmail($token);

        return response(["ok" => true, "message" => "Se te envió un correo electrónico para que recuperes tu contraseña.", "token" => $token], 200);
    }

    // Envio de correo electrónico
    public function sendEmail($token) {
        $link = "www.google.com/$token"; // Ajusta la ruta según tus necesidades

        foreach(['mijel.dev@gmail.com'] as $recipient) {
            Mail::to($recipient)->send(new recuperarContrasenaMail($link));
        }

        return response([
            "ok" => true,
            "message" => "Envío exitoso",
            ], HttpFoundationResponse::HTTP_OK);
    }
}
