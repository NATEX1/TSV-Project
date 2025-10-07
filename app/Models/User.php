<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $connection = 'mongodb'; // บังคับใช้ MongoDB connection
    protected $collection = 'user'; // ชื่อ collection

    protected $fillable = [
        'username',
        'email',
        'password',
        'fname_th',
        'lname_th',
        'phone',
        'skills',
        'status_register',
        'user_rights',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'skills' => 'array',
        'email_verified_at' => 'datetime',
    ];
}
