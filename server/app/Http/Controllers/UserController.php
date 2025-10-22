<?php
namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Services\Auth\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('auth:api');
    }

    public function get(Request $request, int $id = null): JsonResponse
    {
        $authUser = $this->userService->getCurrentUser();

        if (! $authUser) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $targetId = intval($id) ?: $authUser->id;

        if ($targetId !== $authUser->id) {
            return response()->json(['message' => 'Acesso negado. Você só pode ver seus próprios dados.'], 403);
        }

        $user = $this->userService->getUserById($targetId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'name'       => $user->name,
            'username'   => $user->username,
            'email'      => $user->email,
            'phone'      => $user->phone,
            'experience' => $user->experience,
            'education'  => $user->education,
        ]);
    }

    public function destroy(Request $request, int $id = null): JsonResponse
    {
        $authUser = Auth::user();

        if (! $authUser) {
            return response()->json(['message' => 'Invalid token'], 401);
        }
        $targetId = $id ?? $authUser->id;
        if ($targetId !== $authUser->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $user = $this->userService->getUserById($targetId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $deleted = $this->userService->deleteUser($user);

        if ($deleted) {
            return response()->json(['message' => 'User deleted successfully'], 200);
        }

        return response()->json(['message' => 'Error deleting user.'], 500);
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        info($request);
        $user = $this->userService->getCurrentUser();

        if (! $user) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $validatedData = $request->validated();
        if (empty($validatedData)) {
            return response()->json(['message' => 'Unprocessable content'], 304);
        }

        $this->userService->updateUser($user, $validatedData);
        return response()->json([
            'message' => 'Perfil atualizado com sucesso.',
        ]);
    }
}
