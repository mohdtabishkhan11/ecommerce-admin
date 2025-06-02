<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->password === $credentials['password']) {
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        // Flash error message for wrong credentials
        return back()->with('error', 'Invalid email or password.');
    }



    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate all fields
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gender' => 'nullable|string',
            'designation' => 'nullable|string',
            'skills' => 'nullable|array'
        ]);
    
        $imagePath = null;
    
        // Upload image if present
        if ($request->hasFile('profile_image')) {
            if ($request->file('profile_image')->isValid()) {
                $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            }
        }
    
        // Create user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Secure!
            'profile_image' => $imagePath,
            'gender' => $request->gender,
            'designation' => $request->designation,
            'skills' => $request->skills ? json_encode($request->skills) : null,
        ]);
    
        return redirect()->route('login')->with('success', 'Registered successfully!');
    }
    





    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
