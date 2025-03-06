<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ Use this instead of Model
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'full_name',
        'first_name',
        'last_name',
        'slug',
        'email',
        'password',
        'oauth_id',
        'oauth_type',
        'login_method',
        'linkedin_avatar',
        'facebook_id',
        'facebook_avatar',
        'profile_banner',
        'order-testing',
        'last_activity',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
