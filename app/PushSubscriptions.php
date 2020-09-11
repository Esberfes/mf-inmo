<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class PushSubscriptions extends Model
{
    use Notifiable;

    protected $table = 'push_subscriptions';

    protected $fillable = [
        "id_user"
    ];

}
