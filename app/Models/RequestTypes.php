<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestTypes extends Model
{
    use HasFactory;
    public function various_requests()
    {
        return $this->hasOne(VariousRequests::class, 'type', 'id');
    }
}
