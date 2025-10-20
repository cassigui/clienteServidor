<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ViewErrorBag;
use App\Services\Auth\RegisterUserService;
// 👈 Importe a classe necessária para o objeto de erro
use App\Http\Requests\Auth\RegisterUserRequest;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class RegisterController extends Controller
{
    protected RegisterUserService $registerUserService;

    public function __construct(RegisterUserService $registerUserService)
    {
        $this->registerUserService = $registerUserService;
    }

    public function create()
    {
        $data = [];

        if (!session()->has('errors')) {
            $data['errors'] = new ViewErrorBag();
        }
        $apiBaseUrl = config('services.api.base_url');
        return view('site.register.index', compact('data', 'apiBaseUrl'));
    }

    public function store(RegisterUserRequest $request): JsonResponse
    {
        $user = $this->registerUserService->createUser($request->validated());

        return response()->json([
            "message" => "Created",
        ], 201); // 👈 Retorno 201 Created
    }

    // public function store(RegisterUserRequest $request)
    // {
    //     $user = $this->registerUserService->createUser($request->validated());
    //     Auth::login($user);
    //     return redirect()->route('site.home.index')->with('success', 'Sua conta foi criada com sucesso! Boas-vindas!');
    // }
}