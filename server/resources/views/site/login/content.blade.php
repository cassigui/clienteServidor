<section id="login-form-section" class="max-w-md mx-auto p-6 bg-white shadow-xl rounded-lg">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Acesse sua Conta</h2>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <div id="login-error-message" class="mb-4 p-3 bg-red-100 text-red-600 rounded-md" style="display: none;"></div>

    <form id="login-form" data-api-url="{{ $apiBaseUrl }}/login" data-profile-url="{{ route('profile.edit') }}">
        @csrf

        {{-- CAMPO USERNAME --}}
        <div>
            <label for="username" class="block font-medium text-sm text-gray-700">Nome de usuário</label>
            <input id="username"
                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                type="text" name="username" required autofocus />
            <span id="error-username" class="text-sm text-red-600 mt-2"></span>
        </div>

        {{-- CAMPO SENHA --}}
        <div class="mt-4">
            <label for="password" class="block font-medium text-sm text-gray-700">Senha</label>
            <input id="password"
                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                type="password" name="password" required autocomplete="current-password" />
            <span id="error-password" class="text-sm text-red-600 mt-2"></span>
        </div>

        {{-- BOTÕES --}}
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="#">
                Esqueceu sua senha?
            </a>
            <button type="submit" id="login-submit-button"
                class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Entrar
            </button>
        </div>

        {{-- LINK PARA CADASTRO --}}
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Não tem uma conta?
                <a class="underline font-medium text-indigo-600 hover:text-indigo-800" href="{{ route('register') }}">
                    Cadastre-se
                </a>
            </p>
        </div>
    </form>
</section>