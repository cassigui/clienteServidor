<div class="profile-container">
    <section class="profile-section">
        <header>
            <h2>Informações do Perfil</h2>
            <p>Atualize os dados da sua conta e seu currículo.</p>
        </header>

        <form id="update-form" api-url-update="{{ $apiBaseUrl }}/users/{{ $userId }}">
            @csrf

            <div>
                <label for="name">Nome</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus
                    autocomplete="name" />
            </div>

            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                    autocomplete="username" />
            </div>

            <div>
                <label for="phone">Telefone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}"
                    autocomplete="tel" />
            </div>

            <div>
                <label for="password">Nova Senha</label>
                <input id="password" name="password" type="password" autocomplete="new-password" />
            </div>

            <div>
                <label for="experience">Experiência Profissional</label>
                <textarea id="experience" name="experience"
                    rows="4">{{ old('experience', $user->experience) }}</textarea>
            </div>

            <div>
                <label for="education">Formação/Educação</label>
                <textarea id="education" name="education" rows="4">{{ old('education', $user->education) }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit">Salvar Alterações</button>
                @if (session('status') === 'profile-updated')
                    <p class="success-message">Salvo com sucesso!</p>
                @endif
            </div>
        </form>
    </section>
    <section class="profile-section mt-5 border-top pt-4">
        <header>
            <h2>Sair da Conta</h2>
            <p>Clique abaixo para finalizar sua sessão em todos os seus dispositivos.</p>
        </header>

        {{-- FORMULÁRIO DE LOGOUT --}}
        <form id="logout-form" api-url-logout="{{ $apiBaseUrl }}/logout">
            @csrf

            <div class="form-actions">
                <button type="submit" class="btn btn-danger">
                    Sair / Logout
                </button>
            </div>
        </form>
    </section>

    <section class="profile-section mt-5 border-top pt-4">
        <header>
            <h2>Deletar Conta</h2>
            <p>Clique abaixo para DELETAR sua conta.</p>
        </header>

        {{-- FORMULÁRIO DE LOGOUT --}}
        <form id="delete-form" api-url-delete="{{ $apiBaseUrl }}/users/{{ $userId }}">
            @csrf

            <div class="form-actions">
                <button type="submit" class="btn btn-danger">
                    Deletar conta
                </button>
            </div>
        </form>
    </section>

    {{-- <section class="profile-section">
        <header>
            <h2>Atualizar Senha</h2>
            <p>Garanta que sua conta esteja usando uma senha longa e aleatória para se manter segura.</p>
        </header>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div>
                <label for="current_password">Senha Atual</label>
                <input id="current_password" name="current_password" type="password" autocomplete="current-password" />
            </div>

            <div>
                <label for="password">Nova Senha</label>
                <input id="password" name="password" type="password" autocomplete="new-password" />
            </div>

            <div>
                <label for="password_confirmation">Confirmar Nova Senha</label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                    autocomplete="new-password" />
            </div>

            <div class="form-actions">
                <button type="submit">Salvar Senha</button>
                @if (session('status') === 'password-updated')
                <p class="success-message">Senha alterada com sucesso!</p>
                @endif
            </div>
        </form>
    </section> --}}
</div>