<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        // Aplica o middleware de autenticação a todos os métodos, exceto login e register
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * g) Processar corretamente dados recebidos de cadastro de usuário (comum)
     */
    public function register(Request $request)
    {
        // VALIDAR TODOS OS campos [cite: 1]
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Usuário cadastrado com sucesso!', 'user' => $user], 201);
    }

    /**
     * h) Processar corretamente dados de login
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * i) Enviar dados de cadastro do usuário comum para o cliente
     */
    public function user()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * j) Processar corretamente a atualização dos dados do usuário comum
     */
    public function update(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update($request->only('name', 'email'));

        return response()->json(['message' => 'Dados atualizados com sucesso!', 'user' => $user]);
    }

    /**
     * k) Apagar cadastro de usuário comum
     */
    public function destroy()
    {
        $user = auth('api')->user();
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'Usuário apagado com sucesso!']);
        }
        return response()->json(['error' => 'Usuário não encontrado'], 404);
    }

    /**
     * l) Processar corretamente pedido de logout
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }

    /**
     * Retorna a estrutura do token JWT
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}