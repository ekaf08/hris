<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            /** Validate Request */
            $request->validate([
                'email' => 'required|email',
                'email' => 'password'
            ]);

            /** Find User by email */
            $credentials = $request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponApiFormatter::error('Unauthorized', 401);
            }

            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password)) {
                throw new Exception('Invalid password');
            }

            /** Generate token */
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            /** Return Response */
            return ResponApiFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Login Success.');
        } catch (Exception $e) {
            return ResponApiFormatter::error('Authentication Failed');
        }
    }
    public function register()
    {
    }
    public function logout()
    {
    }
    public function fetch()
    {
    }
}
