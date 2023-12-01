<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


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
        $input = $request->all();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $input['role'],
        ]);
        
        // Log user creation
        Activity::create([
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
        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'Update User',
            'message' => $message,
            'created_at' => now(),
        ]);
    
        return redirect('users')->with('flash_message', 'User Updated!');
    }
    
    
    public function destroy(string $id): RedirectResponse
{
    // Retrieve the user before deleting it
    $user = User::find($id);

    // Delete the user and get the number of deleted records
    User::destroy($id);

    // Log user deletion if the user was found and deleted

    Activity::create([
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
        'password' => 'nullable|min:6|confirmed', // Add password rules
    ]);

    $data = [
        'name' => $request->input('name'),
        'email' => $request->input('email'),
    ];

    // Only update the password if a new one is provided
    if ($request->filled('password')) {
        $data['password'] = bcrypt($request->input('password'));
    }

    // Keep track of the original user data
    $originalUserData = $user->getOriginal();

    $user->update($data);

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

    // Check if 'password' was updated
    if ($request->filled('password')) {
        $details[] = 'Password updated';
    }

    // Create log message with details
    $message = 'User updated profile. ' . implode('. ', $details);

    // Log user profile update
    Activity::create([
        'user_id' => Auth::id(),
        'activity' => 'Update Profile',
        'message' => $message,
        'created_at' => now(),
    ]);

    return redirect()->route('profile.show')->with('success_message', 'Profile updated successfully!');
}

    
    

}
