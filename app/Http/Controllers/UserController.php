<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\activity;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserController extends Controller
{
    public function index(): View
    {
        $users = User::all();
        return view ('users.index')->with('users', $users);
    }
 
    public function create(): View
    {
        return view('users.create');
    }
  
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'role' => 'required|string',
        ], [
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one number.',
        ]);
    
        $input = $request->all();
    
        // Check if a user with the same email exists (including soft-deleted users)
        $existingUser = User::withTrashed()->where('email', $input['email'])->first();

        if ($existingUser) {
            // If the user exists and is soft deleted, restore and update the status to 'active'
            if ($existingUser->trashed()) {
                $dataToUpdate = [
                    'status' => User::STATUS_ACTIVE,
                ];
    
                // Update the name only if it's different from the existing name
                if ($existingUser->name !== $input['name']) {
                    $dataToUpdate['name'] = $input['name'];
                }
    
                $existingUser->restore();
                $existingUser->update($dataToUpdate);
    
                // Log user reactivation
                activity::create([
                    'user_id' => Auth::id(),
                    'activity' => 'Reactivate User',
                    'message' => 'Admin reactivated user with email "' . $existingUser->email . '".',
                    'created_at' => now(),
                ]);
    
                return redirect('users')->with('flash_message', 'User Reactivated!');
            }
    
            // If the user exists and is not soft deleted, do not create a new one
            return redirect('users')->with('error_message', 'User with the same email already exists.');
        }
    
        // If the user does not exist, create a new one
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $input['role'],
        ]);
    
        // Log user creation
        activity::create([
            'user_id' => Auth::id(),
            'activity' => 'Create User',
            'message' => 'Admin created user with email "' . $user->email . '".',
            'created_at' => now(),
        ]);
    
        return redirect('users')->with('flash_message', 'User Added!');
    }
    

    public function show(string $id): View
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    public function edit(string $id): View
    {
        $user = User::find($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $user = User::find($id);

        // Check if the target user is the first admin with ID 1
        if ($user->id === 1) {
            return redirect('users')->with('error_message', 'Cannot update the superadmin.');
        }
        $input = $request->all();
    
        // Keep track of the original user data
        $originalUserData = $user->getOriginal();
    
        $user->update([
            'name' => $input['name'],
            'email' => $input['email'],
            'role' => $input['role'],
        ]);
    
        // Log user update with details
        $details = [];
    
        // Check if 'name' was updated
        if ($originalUserData['name'] !== $user->name) {
            $details[] = 'Name updated from "' . $originalUserData['name'] . '" to "' . $user->name . '"';
        }
    
        // Check if 'email' was updated
        if ($originalUserData['email'] !== $user->email) {
            $details[] = 'Email updated from "' . $originalUserData['email'] . '" to "' . $user->email . '"';
        }
    
        // Check if 'role' was updated
        if ($originalUserData['role'] !== $user->role) {
            $details[] = 'Role updated from "' . $originalUserData['role'] . '" to "' . $user->role . '"';
        }
    
        // Create log message with details
        $message = 'Admin updated user. ' . implode('. ', $details);
        
        // Log user update
        activity::create([
            'user_id' => Auth::id(),
            'activity' => 'Update User',
            'message' => $message,
            'created_at' => now(),
        ]);
    
        return redirect('users')->with('flash_message', 'User Updated!');
    }
    
    
    public function destroy(string $id): RedirectResponse
{
    // Retrieve the user before soft deleting it
    $user = User::withTrashed()->find($id);

    if (!$user) {
        return redirect('users')->with('error_message', 'User not found.');
    }

    if ($user->id === 1) {
        return redirect('users')->with('error_message', 'Cannot delete the superadmin.');
    }

    // Check if the target user is the currently logged-in user
    if ($user->id === Auth::id()) {
        return redirect('users')->with('error_message', 'Cannot delete yourself.');
    }

    if ($user->trashed()) {
        // If the user is already soft deleted, reactivate and update the status to 'active'
        $user->restore();
        $user->update(['status' => User::STATUS_ACTIVE]);

        // Log user reactivation
        activity::create([
            'user_id' => Auth::id(),
            'activity' => 'Reactivate User',
            'message' => 'Admin reactivated user with email "' . $user->email . '".',
            'created_at' => now(),
        ]);

        return redirect('users')->with('flash_message', 'User Reactivated!');
    }

    // Soft delete the user and update the status to 'deleted'
    $user->delete();
    $user->update(['status' => User::STATUS_DELETED]);

    // Log soft delete
    activity::create([
        'user_id' => Auth::id(),
        'activity' => 'Delete User',
        'message' => 'Admin deleted user: ' . $user->email,
        'created_at' => now(),
    ]);

    return redirect('users')->with('flash_message', 'User deleted!');
}

    public function showProfile()
    {
        $user = Auth::user();
        return view('users.showProfile', compact('user'));
    }

    public function updateProfile(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
    ]);

    $user->name = $request->input('name');
    $user->email = $request->input('email');

    // Check if there are changes
    if ($user->isDirty()) {
        $user->save();

        // Log user update with details
        $details = [];

        // Check if 'name' was updated
        if ($user->isDirty('name')) {
            $details[] = 'Name updated from "' . $user->getOriginal('name') . '" to "' . $user->name . '"';
        }

        // Check if 'email' was updated
        if ($user->isDirty('email')) {
            $details[] = 'Email updated from "' . $user->getOriginal('email') . '" to "' . $user->email . '"';
        }

        // Create log message with details
        $message = 'User updated profile. ' . implode('. ', $details);

        // Log user profile update
        activity::create([
            'user_id' => Auth::id(),
            'activity' => 'Update Profile',
            'message' => $message,
            'created_at' => now(),
        ]);

        return redirect()->route('profile.show')->with('success_message_profile', 'Profile updated successfully!');
    }

    return redirect()->route('profile.show')->with('info_message_profile', 'No changes made to the profile.');
}

public function updatePassword(Request $request)
{

        $user = Auth::user();

    $request->validate([
        'old_password' => 'required|password',
        'new_password' => [
            'required',
            'min:8',
            'different:old_password',
            'confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ],
    ], [
        'new_password.required' => 'The new password is required.',
        'new_password.min' => 'The new password must be at least 8 characters.',
        'new_password.different' => 'The new password must be different from the old password.',
        'new_password.confirmed' => 'The new password confirmation does not match.',
        'new_password.regex' => 'The new password must contain at least one uppercase letter, one lowercase letter, and one number.',
        'old_password.password' => 'The provided old password is incorrect.',
    ]);




    $user->update([
        'password' => Hash::make($request->input('new_password')),
    ]);

    // Log password change activity
    activity::create([
        'user_id' => Auth::id(),
        'activity' => 'Change Password',
        'message' => 'User changed password.',
        'created_at' => now(),
    ]);

    return redirect()->route('profile.show')->with('success_message_password', 'Password changed successfully!');
}
    
    

}
