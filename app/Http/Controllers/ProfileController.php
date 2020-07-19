<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Fetch user profile
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function show()
    {
        return response()->json([
            'status' => 'success',
            'user'   => auth()->user(),
        ]);
    }

    /**
     * Update the profile of a user
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'  => 'required',
            'email' => "required|email|unique:users,email,$user->id",
        ]);

        $user->update($validated);

        return messageResponse('success', 'Profile updated successfully');
    }

    /**
     * Change a users password
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required|min:6',
            'password'        => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->currentPassword, $user->password)) {

            return messageResponse('error', 'Current password does not match, please enter your current password.', 400);
        }

        $user->update(['password' => $request->password]);

        return messageResponse('success', 'Password changed successfully.');

    }

}
