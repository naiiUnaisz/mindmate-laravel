<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdateSettingsRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => new UserResource($user)
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->only([
            'name',
            'email',
            'username',
            'birthday',
            'gender',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user->fresh()),
        ]);
    }

    public function changeEmail(ChangeEmailRequest $request)
    {
        $user = $request->user();

        $user->update([
            'email' => $request->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email changed successfully',
            'data' => new UserResource($user->fresh()),
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }

    public function updateSettings(UpdateSettingsRequest $request)
    {
        $user = $request->user();

        $user->update([
            'settings' => $request->settings,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully',
            'data' => [
                'settings' => $user->fresh()->settings,
            ],
        ]);
    }

    public function streak(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'current_streak' => $user->current_streak,
                'restday_quota' => $user->restday_quota,
            ]
        ]);
    }

    public function incrementStreak(Request $request)
    {
        $user = $request->user();
        $user->increment('current_streak');

        return response()->json([
            'success' => true,
            'message' => 'Streak berhasil ditambah',
            'data' => [
                'current_streak' => $user->current_streak,
            ]
        ]);
    }
}
