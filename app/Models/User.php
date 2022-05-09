<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department'
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

    /*
    */

    function latest_attend($users){
        foreach($users as $user){
            $latest_attends[] = $user -> attendance -> created_at -> max();
        }
        return $latest_attends;
    }

    function departments(){
        return $this ->hasOne(department::class,'id','department');
    }

    function attendance(){
        return $this ->hasOne(attendance::class,'user_id','id');
    }

    function user_memo(){
        return $this ->hasOne(user_memo::class,'user_id','id');
    }
}
