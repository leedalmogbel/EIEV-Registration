<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Session;
use Hash;

use App\Validators\User as UserValidator;
use App\Exceptions\FieldException;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const AVAILABLE_LOCATIONS = [
        'Abu Dhabi',
        'Dubai',
        'Al Ain',
        'Ajman',
        'Sharjah',
        'Ras Al Khaimah',
        'Fujaira',
        'Umm Al Quwain'
    ];

    const AVAILABLE_DISCIPLINES = [
        'E' => 'Endurance',
        'J' =>'Jumping',
        'D' => 'Dressage',
        'F' => 'Flat'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'dob',
        'location',
        'discipline',
        'emirates_id',
        'username',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $primaryKey = 'user_id';

    public function role() {
        return $this->belongsTo('App\Models\Role', 'role', 'role_id');
    }
}
