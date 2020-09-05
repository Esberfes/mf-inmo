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
        'endpoint',
    ];

    public function pushSubscriptionBelongsToUser($subscription){
        return (int) $subscription->subscribable_id === (int) $this->id;
    }
}
