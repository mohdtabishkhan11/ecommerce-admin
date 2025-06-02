<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'gender',
        'designation',
        'skills',
    ];

    protected $casts = [
        'skills' => 'array', // Ensure skills is casted properly if stored as JSON
    ];

}
