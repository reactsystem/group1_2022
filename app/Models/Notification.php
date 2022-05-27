<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'data',
        'url',
        'badge_color',
        'status'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public static function publish($param)
    {
        $user = User::find($param['user_id']);
        if ($param['user_id'] != 0 && $user == null) {
            return null;
        }
        $data = Notification::create($param);
        if ($param['user_id'] != 0) {
            Mail::send('emails.notification', [
                "mail_param" => $param,
                "push_data" => $data,
            ], function ($message) use ($user, $param) {
                $message
                    ->to($user['email'])
                    ->subject(config('app.name', '勤怠管理システム') . " 新着通知 / " . $param['title']);
            });
        }
        return $data;
    }
}
