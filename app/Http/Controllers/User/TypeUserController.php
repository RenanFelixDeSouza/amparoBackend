namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\TypeUserService;
use App\Http\Resources\User\TypeUserResource;
use Illuminate\Http\Request;

class TypeUserController extends Controller
{
    protected $typeUserService;

    public function __construct(TypeUserService $typeUserService)
    {
        $this->typeUserService = $typeUserService;
    }

    public function index()
    {
        $typeUsers = $this->typeUserService->getAll();
        return TypeUserResource::collection($typeUsers);
    }

    public function show($id)
    {
        $typeUser = $this->typeUserService->getById($id);
        return new TypeUserResource($typeUser);
    }

    public function store(Request $request)
    {
        $typeUser = $this->typeUserService->create($request->all());
        return new TypeUserResource($typeUser);
    }

    public function update(Request $request, $id)
    {
        $typeUser = $this->typeUserService->update($id, $request->all());
        return new TypeUserResource($typeUser);
    }

    public function destroy($id)
    {
        $this->typeUserService->delete($id);
        return response()->json(null, 204);
    }
}