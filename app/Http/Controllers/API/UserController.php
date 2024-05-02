<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            /** Validate Request */
            $request->validate([
                'email' => 'required|email',
                'password' => 'string'
            ]);

            /** Find User by email */
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
            /** Return Response Error*/
            return ResponApiFormatter::error('Authentication Failed');
        }
    }

    public function register(Request $request)
    {
        try {
            /** Validate request */
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => [
                    'string',
                    Password::min(size: 8)
                        ->letters()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()
                ]
            ]);

            /** Response Validate fails */
            if ($validator->fails()) {
                return ResponApiFormatter::error(['message' => $validator->errors()], 422);
            }
            // dd($request->all(), htmlspecialchars($request->name));

            /** Create user */
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            /** Generate token */
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            /** Return Response */
            return ResponApiFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Register Success');
        } catch (Exception $error) {
            /** Return Response Error */
            return ResponApiFormatter::error($error->getMessage());
        }
    }

    public function logout(Request $request)
    {
        /** Revoke Token */
        $token = $request->user()->currentAccessToken()->delete();

        /** Return Response */
        return ResponApiFormatter::success($token, 'Logout Success');
    }
    public function fetch(Request $request)
    {
        /** Get User */
        $user = $request->user();

        /** Return Response */
        return ResponApiFormatter::success($user, 'Fetch Success');
    }
}
