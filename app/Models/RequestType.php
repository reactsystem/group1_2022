<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'deleted_at',
    ];

    public function various_requests()
    {
        return $this->hasOne(VariousRequest::class, 'type', 'id');
    }
}
