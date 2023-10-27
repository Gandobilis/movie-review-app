<?php

namespace App\Repositories\Interfaces;

use App\Models\Subscription;

interface SubscriptionRepositoryInterface {
    public function subscribe(array $data);
    public function unsubscribe(Subscription $subscription);
}
