<?php

namespace App\Models;

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

    public function latestAttemdance()
    {
        // max(date) の 条件で １件取得する。
        return $this->hasOne(Attendance::class)->ofMany('date', 'max');
    }

    function departments(){
        return $this->hasOne(Department::class, 'id', 'department');
    }

    function attendance(){
        return $this->hasMany(Attendance::class, 'user_id', 'id');
    }

    function user_memo(){
        return $this->hasOne(UserMemo::class, 'user_id', 'id');
    }

    function various_requests(){
        return $this ->hasOne(various_request::class,'user_id','id');
    }
}
