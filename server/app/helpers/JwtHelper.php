<?php

namespace App\Helpers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtHelper
{
    /**
     * Retorna o user_id (sub) do token JWT.
     * Se não conseguir decodificar, retorna null.
     */
    public static function getUserIdFromToken(?string $token): ?int
    {
        if (!$token) {
            return null;
        }

        // Tenta decodificar de forma segura com tymon/jwt-auth
        try {
            $payload = JWTAuth::setToken($token)->getPayload();
            return (int) $payload->get('sub');
        } catch (JWTException $e) {
            // fallback simples (sem validação da assinatura)
            $parts = explode('.', $token);
            if (count($parts) === 3) {
                $payload = json_decode(self::base64urlDecode($parts[1]), true);
                return isset($payload['sub']) ? (int) $payload['sub'] : null;
            }
        }

        return null;
    }

    private static function base64urlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/')) ?: '';
    }
}
