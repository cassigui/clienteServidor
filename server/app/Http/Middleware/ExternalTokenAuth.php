<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class ExternalTokenAuth
{

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ])->get(self::EXTERNAL_API_VALIDATION_URL);

            // 3. Verifica o Status da Resposta
            if ($response->successful()) {
                $userData = $response->json();

                // 4. Autentica o Usuário Localmente (Cria uma sessão ou encontra o usuário local)
                $user = User::firstOrCreate(
                    ['username' => $userData['username']],
                    ['name' => $userData['name'], 'password' => ''] // Sem senha hashada, apenas para sessão
                );

                // 🛑 Autentica o usuário na sessão do Laravel
                Auth::login($user);

                // Token válido e autenticado. Permite o acesso.
                return $next($request);
            }

        } catch (\Exception $e) {
            // Loga o erro de conexão/API, mas nega o acesso.
            logger()->error("Falha na validação externa do token: " . $e->getMessage());
        }

        // 5. Falha na validação da API Externa ou Token Inválido
        return redirect()->route('login')->withErrors(['global' => 'Sessão inválida ou expirada.']);
    }
}
