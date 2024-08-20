<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserImage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['nullable', 'string', 'max:15'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => [
                'required',
                Rule::in(['user', 'admin']),
            ],
            'user_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->string('password')),
        ]);

        // Handle the image upload
        if ($request->hasFile('user_image')) {
            $image = $request->file('user_image');
            $image_name = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move the image to the public directory
            $image->move(public_path('/uploads/UserImage'), $image_name);

            // Store the image name in the database
            UserImage::create([
                'user_id' => $user->id,
                'user_image' => $image_name,
            ]);
        }
        event(new Registered($user));
        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'token' => $user->createToken('USER API TOKEN')->plainTextToken,
            'data' => $user
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', Password::defaults()],
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        $token = $user->createToken('USER API TOKEN')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'data' => $user,
        ], 200);
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
