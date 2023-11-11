<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Illuminate\Support\Facades\Validator;


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

        $existingUser = User::where('email', $request->name)->first();

        if (!$existingUser) {
            return response(["ok" => false, "message" => "El correo electrónico ya está en uso."],  HttpFoundationResponse::HTTP_CONFLICT);
        }

        // Alta del usuario
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

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
}
