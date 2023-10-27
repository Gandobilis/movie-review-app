<?php

namespace App\Repositories;

use App\Models\Subscription;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{

    public function subscribe(array $data): Subscription
    {
        return Subscription::firstOrCreate(
            ['email' => $data['email']]
        );
    }

    public function unsubscribe(Subscription $subscription): void
    {
        $subscription->delete();
    }
}
