<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class Guest extends Model
{

    use Notifiable,
    HasPushSubscriptions;

    protected $fillable = [
        'endpoint', "ip", "id_user"
    ];

    public function pushSubscriptionBelongsToUser($subscription){
        return (int) $subscription->subscribable_id === (int) $this->id;
    }
}
