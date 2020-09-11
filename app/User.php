<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{

    use Notifiable,
    HasPushSubscriptions;

    protected $fillable = [
        'endpoint', 'name', 'email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Determine if the given subscription belongs to this user.
     *
     * @param  \NotificationChannels\WebPush\PushSubscription $subscription
     * @return bool
     */
    public function pushSubscriptionBelongsToUser($subscription){
        return (int) $subscription->guest_id === (int) $this->id;
    }
}
