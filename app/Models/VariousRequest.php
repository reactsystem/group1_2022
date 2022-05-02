<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariousRequest extends Model
{
    use HasFactory;

    public function various_request(): BelongsTo
    {
        return $this -> belongsTo(User::class,'id','user_id');
    }
}
