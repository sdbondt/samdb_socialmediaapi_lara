<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = ['country', 'gender', 'city', 'dob', 'description', 'public'];

    protected $casts = [
        'public' => 'boolean'
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function followers() {
        return $this->belongsToMany(User::class);
    }

}
