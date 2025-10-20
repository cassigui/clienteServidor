<section id="register-form-section" class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h2 class="text-center mb-4">Crie sua Conta</h2>

            <form id="register-form" api-url-register="{{ $apiBaseUrl }}/users" class="needs-validation" novalidate>
                @csrf

                {{-- 🛑 GRID BOOTSTRAP: Define o layout de 2 colunas --}}
                <div class="row g-3">

                    {{-- 1. CAMPO NOME (1/2 Coluna) --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nome</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            autocomplete="name" class="form-control">
                    </div>

                    {{-- 2. CAMPO USERNAME (1/2 Coluna) --}}
                    <div class="col-md-6">
                        <label for="username" class="form-label">Nome de Usuário</label>
                        <input id="username" type="text" name="username" value="{{ old('username') }}" required
                            class="form-control">
                    </div>

                    {{-- 3. CAMPO EMAIL (1/2 Coluna) --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="form-control">
                    </div>

                    {{-- 4. CAMPO PHONE (1/2 Coluna) --}}
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Telefone</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                    </div>

                    {{-- 5. CAMPO SENHA (Ocupa 1/2 Coluna - Coluna 1 na próxima linha) --}}
                    <div class="col-md-6">
                        <label for="password" class="form-label">Senha</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="form-control">
                    </div>

                    {{-- Espaço Vazio na 2ª Coluna (ou adicione o confirmar senha se necessário) --}}
                    <div class="col-md-6">
                        {{-- Pode ser usado para confirmar senha ou outro campo --}}
                    </div>

                    {{-- 6. CAMPO EXPERIENCE (Ocupa a Largura Total - 2 Colunas) --}}
                    <div class="col-12">
                        <label for="experience" class="form-label">Experiência</label>
                        <textarea id="experience" name="experience" class="form-control"
                            rows="3">{{ old('experience') }}</textarea>
                    </div>

                    {{-- 7. CAMPO EDUCATION (Ocupa a Largura Total - 2 Colunas) --}}
                    <div class="col-12">
                        <label for="education" class="form-label">Educação</label>
                        <textarea id="education" name="education" class="form-control"
                            rows="3">{{ old('education') }}</textarea>
                    </div>

                </div>
                {{-- FIM DO GRID --}}

                {{-- Botões e Links (Fora do Grid, com margem superior) --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a class="text-decoration-none" href="{{ route('login') }}">
                        Já tem uma conta?
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Cadastrar
                    </button>
                </div>
            </form>

            {{-- Link final (opcional, dependendo do design) --}}
            <div class="text-center mt-3">
                <p class="text-muted">
                    Não tem uma conta?
                    <a href="{{ route('register') }}" class="text-decoration-none">
                        Cadastre-se
                    </a>
                </p>
            </div>

        </div>
    </div>
</section>