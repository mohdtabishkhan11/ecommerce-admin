<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('dashboard', compact('users'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imageName = null;

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage'), $imageName); // saved in /public/uploads/
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // always encrypt passwords
            'profile_image' => $imageName,
            'gender' => $request->gender,
            'designation' => $request->designation,
            'skills' => $request->skills ? json_encode($request->skills) : null,
        ]);

        return redirect()->route('dashboard')->with('success', 'User added successfully!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('edit_user', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle image update
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && file_exists(public_path('storage/' . $user->profile_image))) {
                unlink(public_path('uploads/' . $user->profile_image));
            }

            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage'), $imageName);

            $user->profile_image = $imageName;
        }

        // Update user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->designation = $request->designation;
        $user->skills = $request->skills ? json_encode($request->skills) : null;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->profile_image && file_exists(public_path('uploads/' . $user->profile_image))) {
            unlink(public_path('uploads/' . $user->profile_image));
        }

        $user->delete();
        return redirect()->route('dashboard')->with('success', 'User deleted successfully!');
    }
}
