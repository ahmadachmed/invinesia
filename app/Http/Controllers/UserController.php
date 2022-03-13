<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return response()->json(['status' => 'success', 'data' => $users]); 
    }

    public function store(Request $request)
    {
        //validasi    
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png',
            'password' => 'required|min:6',
            'member' => 'required',
            'role' => 'required', 
            'status' => 'required'
        ]);

        $filename = null;
        if($request->hasfile('picture')) {
            $file = $request->file('picture');
            $filename = Str::random(5) . $request->email . '.' . $file->getClientOriginalExtension(); 
            $file->move('images', $filename);
        }
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'picture' => $filename,
            'password' => app('hash')->make($request->password),
            'status' => $request->status,
            'role' => $request->role,
            'member' => $request->member,
            //'token' => 'test',
        ]);
        return response()->json(['status' => 'success']);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return response()->json(['status' => 'success', 'data' => $user]);
    }

    public function update(Request $request, $id)
    {   
        //validasi
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'username' => 'required|string|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'picture' => 'nullable|image|mimes:jpg,jpeg,png',
            'password' => 'required|min:6',
            'member' => 'required',
            'role' => 'required', 
            'status' => 'required'
        ]);
       
        $user = User::find($id);

        $password = $request->password != '' ? app('hash')->make($request->password):$user->password;

        $filename = $user->picture;
        if($request->hasfile('picture')) {
            $filename = Str::random(5) . $user->email . '.jpg'; 
            $file = $request->file('picture');
            $file->move('images', $filename);
            File::delete(base_path('public/images/' . $user->picture)); 
        }

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'picture' => $filename,
            'password' => $password,
            'status' => $request->status,
            'role' => $request->role,
            'member' => $request->member,
        ]);
        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        unlink(base_path('public/images/' . $user->picture));
        $user->delete();
        return response()->json(['status' => 'success']);
    }

    public function login(Request $request)
    {
        $this->validate($request,  [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6'
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = Str::random(40);
            $user->update(['token' => $token]); 
            return response(['status' => 'success', 'data' => $token]);        
        }
        return response(['status' => 'error', 'data' => 'Wrong Password']);  
    }

    // public function sendResetToken(Request $request)
    // {
    //     $this->validate($request,[
    //         'email' => 'required|email|exists:users'
    //     ]);
    //     $user = User::where('email', $request->email)->first();
    //     $user->update(['reset_token' => Str::random(40)]);
    //     //send auth confirm via email
    //    // Mail::to($user->email)->send(new ResetPasswordMail($user));
    //     return response(['status' => 'success', 'data' => $user->reset_token]); 
    // }

    public function getUserLogin(Request $request)
    {
        return response(['status' => 'success', 'data' => $request->user()]); 
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->update(['token' => null]);
        return response()->json(['status' => 'success']); 
    }
}