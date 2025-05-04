namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Services\Financial\SubscriptionPlanService;
use App\Http\Resources\Financial\SubscriptionPlanResource;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    protected $subscriptionPlanService;

    public function __construct(SubscriptionPlanService $subscriptionPlanService)
    {
        $this->subscriptionPlanService = $subscriptionPlanService;
    }

    public function index(Request $request)
    {
        $subscriptionPlans = $this->subscriptionPlanService->getAll($request->all());
        return SubscriptionPlanResource::collection($subscriptionPlans);
    }

    public function show($id)
    {
        $subscriptionPlan = $this->subscriptionPlanService->getById($id);
        return new SubscriptionPlanResource($subscriptionPlan);
    }

    public function store(Request $request)
    {
        $subscriptionPlan = $this->subscriptionPlanService->create($request->all());
        return new SubscriptionPlanResource($subscriptionPlan);
    }

    public function update(Request $request, $id)
    {
        $subscriptionPlan = $this->subscriptionPlanService->update($id, $request->all());
        return new SubscriptionPlanResource($subscriptionPlan);
    }

    public function destroy($id)
    {
        $this->subscriptionPlanService->delete($id);
        return response()->json(null, 204);
    }
}