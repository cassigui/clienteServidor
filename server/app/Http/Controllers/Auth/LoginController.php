<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use App\Services\Auth\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ViewErrorBag;
use App\Services\Auth\LoginUserService;
use App\Http\Requests\Auth\LoginUserRequest;

class LoginController extends Controller
{
    protected LoginUserService $loginUserService;
    protected UserService $userService;

    public function __construct(LoginUserService $loginUserService, UserService $userService)
    {
        $this->loginUserService = $loginUserService;
        $this->userService = $userService;
    }
    public function create()
    {
        $data = [];
        if (!session()->has('errors')) {
            $data['errors'] = new ViewErrorBag();
        }
        $apiBaseUrl = config('services.api.base_url');
        return view('site.login.index', compact('data', 'apiBaseUrl'));
    }
    public function login(LoginUserRequest $request): JsonResponse
    {
        $token = $this->loginUserService->attemptLogin($request->validated());

        if (!$token) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        // 3. Retorna a resposta JSON no formato exigido
        return $this->respondWithToken($token);
    }

    public function logout(): JsonResponse
    {
        try {
            Auth::guard('api')->logout();

            return response()->json([
                'message' => 'Logout realizado com sucesso. Token invalidado.'
            ], 200);

        } catch (\Exception $e) {
            // Pode ocorrer se o token já estiver inválido ou expirado.
            return response()->json([
                'message' => 'Não foi possível fazer logout ou token já expirado.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function respondWithToken(string $token): JsonResponse
    {
        $ttlInMinutes = config('jwt.ttl');

        if (!$ttlInMinutes) {
            $ttlInMinutes = 60;
        }

        $expiresIn = $ttlInMinutes * 60;

        return response()->json([
            'token' => $token,
            'expires_in' => $expiresIn,
        ], 200);
    }
}