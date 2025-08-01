<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'pass' => 'required|string',
            'newPass' => 'required|string|min:8',
            'confirmPass' => 'required|string|same:newPass'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Hash::check($request->pass, $user->password)) {
            return response()->json([
                'message' => 'La contraseña actual es incorrecta'
            ], 401);
        }

        $user->password = Hash::make($request->newPass);
        $user->save();

        return response()->json([
            'message' => 'Contraseña actualizada correctamente'
        ], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => $user,
            'person' => $user->persons,
            'token' => $token, 
        ]);
    }

    public function getUsers(Request $request)
    {
        $users = User::with(['persons', 'roll'])->get();

        if (!$users) {
            return response()->json([
                'message' => 'Lista de usuarios obtenida exitosamente'
            ], 401);
        }

        return response()->json([
            'message' => 'Lista de usuarios obtenida exitosamente',
            'users' => $users,
        ], 200);
    }

    public function createUser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_person' => 'required|integer',
            'id_roll' => 'required|integer', 
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Error al validar informacion',
                'errors' => $validate->errors(),
                'status'=> 401,
                ],401);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'id_person' => $request->id_person, 
            'id_roll' => $request->id_roll,    
        ]);

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'user' => $user,
        ], 201);
    }
}
