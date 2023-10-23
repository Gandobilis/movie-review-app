<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Http\Response;

class SubscriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function subscribe(SubscriptionRequest $request): Response
    {
        $data = $request->validated();

        $subscription = Subscription::firstOrCreate(
            ['email' => $data['email']]
        );

        return response([
            'message' => __('subscription.subscribe'),
            'subscription' => $subscription
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function unsubscribe(Subscription $subscription): Response
    {
        $subscription->delete();

        return response([
            'message' => __('subscription.unsubscribe'),
        ]);
    }
}
