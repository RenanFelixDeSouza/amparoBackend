<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUser() {
        $result = $this->userService->getUser();
        return response()->json([
            'user' => new UserResource($result['user']),
        ]);
    }
    public function uploadPhoto(Request $request)
    {
        $result = $this->userService->uploadPhoto($request);

        return response()->json([
            'message' => $result['message'],
            'photo_url' => $result['photo_url'],
        ]);
    }

    public function update(Request $request)
    {
        $result = $this->userService->update($request);

        return response()->json([
            'message' => $result['message'],
            'user' => new UserResource($result['user']),
        ]);
    }

    public function delete()
    {
        $result = $this->userService->delete();

        return response()->json([
            'message' => $result['message'],
        ]);
    }
}