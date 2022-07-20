<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function signup() {
        $userAttr = request()->validate([
            'username' => ['required', 'max:255', Rule::unique('users', 'username')],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'min:7', 'max:255', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/'],
        ]);
        $userAttr['slug'] = Str::slug($userAttr['username']);

        $profileAttr = request()->validate([
            'city' => ['sometimes', 'min:2', 'max:255'],
            'country' => ['sometimes', 'min:2', 'max:255'],
            'dob' => ['sometimes','before:today', 'date_format:m/d/Y'],
            'gender' => ['sometimes', Rule::in(['m', 'f', 'u'])],
            'description' => ['required', 'max:255'],
            'public' => ['sometimes', Rule::in([0, 1])]
        ]);

        if($profileAttr['dob'] ?? false) {
            $profileAttr['dob'] = date('Y-m-d', strtotime($profileAttr['dob']));
        }

        $user = User::create($userAttr);
        $user->profile()->create($profileAttr);
        $token = $user->createToken('authToken')->plainTextToken;

        return ['token' => $token];
    }

    public function login() {
        $attr = request()->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $user = User::where('username', request('username'))->first();
        if(!$user) {
            return ['msg' => 'Invalid credentials'];
        } elseif(!auth()->attempt($attr)) {
            return ['msg' => 'Invalid credentials'];
        } else {
            $token = $user->createToken('authToken')->plainTextToken;
            return ['token' => $token];
        }
    }

    public function logout() {
        request()->user()->currentAccessToken()->delete();
        return ['msg' => 'Logged out.'];
    }
}
