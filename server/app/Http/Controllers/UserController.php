<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Auth\UserService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUserRequest;

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

        if (!$authUser) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        $targetId = intval($id) ?: $authUser->id;

        if ($targetId !== $authUser->id) {
            return response()->json(['message' => 'Acesso negado. Você só pode ver seus próprios dados.'], 403);
        }

        $user = $this->userService->getUserById($targetId);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'phone' => $user->phone,
            'experience' => $user->experience,
            'education' => $user->education,
        ]);
    }

    public function destroy(Request $request, int $id = null): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }
        $targetId = $id ?? $authUser->id;
        if ($targetId !== $authUser->id) {
            return response()->json(['message' => 'Acesso negado. Você só pode apagar sua própria conta.'], 403);
        }
        $user = $this->userService->getUserById($targetId);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }
        $deleted = $this->userService->deleteUser($user);

        if ($deleted) {
            return response()->json(null, 204);
        }

        return response()->json(['message' => 'Falha ao excluir o usuário.'], 500);
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        info($request);
        $user = $this->userService->getCurrentUser();
        if (!$user) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        $validatedData = $request->validated();
        if (empty($validatedData)) {
            return response()->json(['message' => 'Nenhum dado válido para atualização.'], 304);
        }

        $updatedUser = $this->userService->updateUser($user, $validatedData);
        return response()->json([
            'message' => 'Perfil atualizado com sucesso.',
            'user' => [
                'id' => $updatedUser->id,
                'name' => $updatedUser->name,
                'username' => $updatedUser->username,
                'email' => $updatedUser->email,
                'phone' => $updatedUser->phone,
                'experience' => $updatedUser->experience,
                'education' => $updatedUser->education,
            ]
        ]);
    }
}