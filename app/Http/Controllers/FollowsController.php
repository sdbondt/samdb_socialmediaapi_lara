<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowsController extends Controller
{
    public function toggleUserFollow(User $user) {
        if($user->profile->public) {
                request()->user()->following()->toggle($user->profile);
                return [
                    'msg' => request()->user()->following->contains($user->profile->id) ? 'Started following user': 'Stopped following user'
                ];
        } else {
            return [
                'msg' => 'Private profile'
            ];
        }
    }
}
