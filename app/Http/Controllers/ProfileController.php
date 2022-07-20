<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(User $user) {
        if(!$user->profile->public) {
            return [
                'msg' => 'Private profile'
            ];
        } else {
            return [
                'profile' => $user->profile,
                'following' => $user->following,
                'followers' => $user->profile->followers
            ];
        }
    }

    public function myprofile() {
        return [
            'user' => request()->user()
        ];
    }

    public function index() {
        $users = Profile::where('public', 1)->paginate();
        return [compact('users')];
    }

    public function update(User $user) {
        $this->authorize('update', [User::class, $user]);
        $userAttr = request()->validate([
            'username' => ['sometimes', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['sometimes', 'min:7', 'max:255', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/'],
        ]);

        if($userAttr['username'] ?? false) {
            $userAttr['slug'] = Str::slug($userAttr['username']);
        }
        
        $profileAttr = request()->validate([
            'city' => ['sometimes', 'min:2', 'max:255'],
            'country' => ['sometimes', 'min:2', 'max:255'],
            'dob' => ['sometimes','before:today', 'date_format:m/d/Y'],
            'gender' => ['sometimes', Rule::in(['m', 'f', 'u'])],
            'description' => ['sometimes', 'max:255'],
            'public' => ['sometimes', Rule::in([0, 1])]
        ]);

        if(count($userAttr) > 0 ?? false) {
            $user->update($userAttr);
        }

        if(count($profileAttr) > 0 ?? false) {
            $user->profile->update($profileAttr);
        }

        return [
            'user' => $user
        ];
    }

    public function destroy(User $user) {
        $this->authorize('delete', [User::class, $user]);
        $user->delete();
        return [
            'msg' => 'User got deleted.'
        ];
    }
}
