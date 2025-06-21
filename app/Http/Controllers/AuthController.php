<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Throwable;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'role' => 'nullable|in:admin,user'
            ]);

            $user = $request->user();
            $role = 'user';

            if ($user && $user->role === 'admin' && $request->filled('role')) {
                $role = $request->role;
            }

            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $role,
            ]);

            return response()->json([
                'message' => 'Usuario registrado correctamente.',
                'user' => $newUser
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Error en la base de datos',
                'error' => $e->getMessage()
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Las credenciales son incorrectas.'],
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'token' => $token,
                'user' => $user
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de autenticación',
                'errors' => $e->errors()
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Sesión cerrada exitosamente'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
