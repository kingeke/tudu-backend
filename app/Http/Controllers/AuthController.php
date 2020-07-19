<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Register new users to the app
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        //validate input
        $validated = $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create($validated);

        return response()->json([
            'status' => 'success',
            'user'   => $user,
            'token'  => auth()->login($user),
        ]);
    }

    /**
     * Login a user
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        //validate input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($token = auth()->attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => 'success',
                'user'   => auth()->user(),
                'token'  => $token,
            ]);
        } else {
            return messageResponse('error', 'Invalid email or password provided.', 401);
        }
    }

    /**
     * Log out a user
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function logOut()
    {
        auth()->logout(true);

        return messageResponse('success', 'You just logged out');
    }
}
