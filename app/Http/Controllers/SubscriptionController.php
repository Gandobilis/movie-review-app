<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use Illuminate\Http\Response;

class SubscriptionController extends Controller
{
    public function __construct(private readonly SubscriptionRepositoryInterface $subscriptionRepository)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function subscribe(SubscriptionRequest $request): Response
    {
        $data = $request->validated();

        $subscription = $this->subscriptionRepository->subscribe($data);

        return response([
            'message' => __('subscription.success.subscribe'),
            'subscription' => $subscription
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function unsubscribe(Subscription $subscription): Response
    {
        $this->subscriptionRepository->unsubscribe($subscription);

        return response([
            'message' => __('subscription.success.unsubscribe'),
        ]);
    }
}
