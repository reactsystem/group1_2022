<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'user_id',
        'mode',
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
