<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register()
    {
        return view('register');
    }
 
    public function registerPost(Request $request)
    {
        $user = new User();
 
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
 
        $user->save();

        // Log the user registration activity
        Activity::create([
            'user_id' => $user->id,
            'activity' => 'register',
            'message' => 'User registered successfully.',
            'created_at' => now(),
        ]);
 
        return back()->with('success', 'Register successfully');
    }
 
    public function login()
    {
        return view('login');
    }
 
    public function loginPost(Request $request)
    {
        $credetials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
 
        if (Auth::attempt($credetials)) {
            // Log the user login activity
            $user = Auth::user();

            Activity::create([
                'user_id' => $user->id,
                'activity' => 'Login',
                'message' => 'User logged in successfully.',
                'created_at' => now(),
            ]);
            return redirect('/home')->with('success', 'Login Success');
        }
 
        return back()->with('error', 'Error Email or Password');
    }
 
    public function logout()
    {
        // Log the user logout activity
        $user = Auth::user();
        Activity::create([
            'user_id' => $user->id,
            'activity' => 'logout',
            'message' => 'User logged out.',
            'created_at' => now(),
        ]);

        Auth::logout();
 
        return redirect()->route('login');
    }
}
