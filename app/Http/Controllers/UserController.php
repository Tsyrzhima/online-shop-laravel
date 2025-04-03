<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditProfileRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController
{
    public function getRegistrationForm()
    {
        return view('registrationForm');
    }

    public function getLogin()
    {
        return view('loginForm');
    }
    public function getProfile()
    {
        $user = Auth::user();
        return view('profile', ['user' => $user]);
    }
    public function getEditProfile()
    {
        $user = Auth::user();
        return view('editProfileForm', ['user' => $user]);
    }

    public function editProfile(EditProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->all();
        if(!empty($request['name']) && ($user->name !== $data['name'])){
            User::query()->update(['name' => $data['name']]);
        };
        if(!empty($request['email']) && ($user->email !== $data['email'])){
            User::query()->update(['email' => $data['email']]);
        };
        if(!empty($request['password']) && ($user->password !== Hash::make($data['password']))){
            User::query()->update(['password' => Hash::make($request['password'])]);
        };
        return response()->redirectTo('/profile');
    }

    public function registrate(RegistrateRequest $request)
    {
        $data = $request->all();
        User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        return response()->redirectTo('/login');
    }
    public function login(LoginRequest $request)
    {
        if(Auth::attempt($request->only('email', 'password')))
        {
            return response()->redirectTo('/catalog');
        };

        return back()->withErrors([
            'Auth' => 'Неверные учетные данные',
        ]);

    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
