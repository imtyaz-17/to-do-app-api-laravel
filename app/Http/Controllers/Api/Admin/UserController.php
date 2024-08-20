<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest('id')->paginate(10);
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
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
            'password' => Hash::make($request->password),
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
        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'user' => $user
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'sometimes|string|in:user,admin',
        ]);
// Handle the image upload
        if ($request->hasFile('user_image')) {
            $oldImage = $user->image;
            //   Delete Old Image
            if ($oldImage){
                File::delete(public_path('/uploads/UserImage/' . $oldImage));
            }
            $image = $request->file('user_image');
            $image_name = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move the image to the public directory
            $image->move(public_path('/uploads/UserImage'), $image_name);

            // Store the image name in the database
            UserImage::updateOrCreate([
                'user_id' => $user->id,
                'user_image' => $image_name,
            ]);

        }
        $user->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'user' => $user
        ]);
    }

    public function destroy(User $user)
    {
        // Delete the user's image if it exists
        $userImage = UserImage::where('user_id', $user->id)->first();
        if ($userImage) {
            File::delete(public_path('/uploads/UserImage/' . $userImage));
            $userImage->delete();
        }
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
    }
}
