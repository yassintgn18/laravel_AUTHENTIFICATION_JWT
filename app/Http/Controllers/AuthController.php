<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    // POST /api/auth/register
    // Creates a new user account
    public function register(Request $request)
    {
        // Step 1: Validate the incoming request data
        // Laravel checks these rules automatically and returns 422 if they fail
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Step 2: Create the user in the database
        // Because 'password' is in $casts as 'hashed', Laravel
        // automatically bcrypt-hashes it before saving. No bcrypt() call needed.
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        // Step 3: Return the created user as JSON with 201 status
        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $user,
        ], 201);
    }

    // POST /api/auth/login
    // Authenticates a user and returns a JWT token
    public function login(Request $request)
    {
        // Step 1: Validate the incoming request data
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Step 2: Try to authenticate using email and password
        // auth()->attempt() does THREE things automatically:
        // 1. Finds the user by email in the database
        // 2. Verifies the password against the bcrypt hash
        // 3. If correct, generates a JWT token and returns it
        // If wrong credentials, it returns false (no exception)
        $token = auth('api')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        // Step 3: If credentials are wrong, return 401
        if (!$token) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Step 4: Return the token to the client
        return response()->json([
            'message'    => 'Login successful',
            'token'      => $token,
            'token_type' => 'bearer',
        ]);
    }

 public function me()
{
    return response()->json(auth('api')->user());
}

public function logout()
{
    auth('api')->logout();

    return response()->json([
        'message' => 'Logged out successfully',
    ]);
}
}