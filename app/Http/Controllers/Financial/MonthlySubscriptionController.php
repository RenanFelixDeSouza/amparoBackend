<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Services\Financial\MonthlySubscriptionService;
use App\Http\Resources\MonthlySubscriptionResource;
use Illuminate\Http\Request;

class MonthlySubscriptionController extends Controller
{
    protected $monthlySubscriptionService;

    public function __construct(MonthlySubscriptionService $monthlySubscriptionService)
    {
        $this->monthlySubscriptionService = $monthlySubscriptionService;
    }

    public function index(Request $request)
    {
        $subscriptions = $this->monthlySubscriptionService->getAllSubscriptions($request);
        return MonthlySubscriptionResource::collection($subscriptions);
    }

    public function show($id)
    {
        $subscription = $this->monthlySubscriptionService->getSubscriptionById($id);
        return new MonthlySubscriptionResource($subscription);
    }

    public function store(Request $request)
    {
        $subscription = $this->monthlySubscriptionService->createSubscription($request->all());
        return new MonthlySubscriptionResource($subscription);
    }

    public function update(Request $request, $id)
    {
        $subscription = $this->monthlySubscriptionService->updateSubscription($id, $request->all());
        return new MonthlySubscriptionResource($subscription);
    }

    public function destroy($id)
    {
        $this->monthlySubscriptionService->deleteSubscription($id);
        return response()->json(['message' => 'Subscription deleted successfully']);
    }
}