<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariousRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'uuid',
        'type',
        'date',
        'status',
        'reason',
        'comment',
        'related_id',
        'time',
        'updated_at',
        'created_at'
    ];

    public function user(){
        return $this -> belongsTo(User::class,'user_id','id');
    }

    public function various_request(): BelongsTo
    {
        return $this->belongsTo(VariousRequest::class, 'id', 'user_id');
    }

    public function request_types()
    {
        return $this->hasOne(RequestTypes::class, 'id', 'type');
    }

    // リクエスト1つに対して関係しているリクエストをすべて取り出す
    public function related_request()
    {
        return VariousRequest::where('related_id','=',$this -> uuid)->get();   
    } 
}
