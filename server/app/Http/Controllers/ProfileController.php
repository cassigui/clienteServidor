<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Helpers\JwtHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Services\Profile\UpdateProfileService;
use App\Services\Profile\UpdatePasswordService;

class ProfileController extends Controller
{

    public function edit(Request $request): View
    {
        $token = $request->get('token');
        $userId = JwtHelper::getUserIdFromToken($token);
        info("User ID extraído: {$userId}");

        if (!$token || !$userId) {
            return view('site.login.index');
        }

        $apiBaseUrl = config('services.api.base_url');
        $apiUrl = "{$apiBaseUrl}/users/{$userId}";
        info($apiUrl);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->get($apiUrl);

        if ($response->successful()) {
            $apiData = $response->json();
            $apiBaseUrl = config('services.api.base_url');
            $user = (object) $apiData;

            return view(
                'site.profile.index',
                compact('user', 'apiBaseUrl')
            );
        }

        logger()->error("Falha ao buscar dados do perfil via API.", ['response' => $response->body()]);

        return view('site.profile.index', [
            'api_error' => 'Não foi possível carregar dados detalhados do perfil.'
        ]);
    }

    public function update(ProfileUpdateRequest $request, UpdateProfileService $service): RedirectResponse
    {
        $service->updateUser($request->user(), $request->validated());

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    public function updatePassword(PasswordUpdateRequest $request, UpdatePasswordService $service): RedirectResponse
    {
        $service->updatePassword($request->user(), $request->validated());

        return Redirect::route('profile.edit')->with('status', 'password-updated');
    }
}