@extends('layouts.site')

@push('head')
@section('title', 'Login - Acesse sua Conta')
@section('meta_description', 'Página de login para acesso ao sistema.')
<link rel="stylesheet" href="{{ asset('css/site/login/style.css') }}" />

@endpush

@section('content')
    @include('site.login.content')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('login-form');
            const apiUrl = form.getAttribute('data-api-url');
            const profileUrl = form.getAttribute('data-profile-url');
            const errorMessageDiv = document.getElementById('login-error-message');

            function clearErrors() {
                errorMessageDiv.style.display = 'none';
                errorMessageDiv.textContent = '';
                document.querySelectorAll('[id^="error-"]').forEach(el => el.textContent = '');
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                clearErrors();

                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                err.status = response.status;
                                throw err;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        const token = data.token;
                        localStorage.setItem('jwt_token', token);
                        console.log(profileUrl + '?token=' + token)

                        window.location.href = profileUrl + '?token=' + token;
                    })
                    .catch(error => {
                        // FALHA: Tratamento de erro unificado

                        let status = error.status || 0;
                        let responseData = error;
                        let message = 'Ocorreu um erro desconhecido.';

                        if (status === 401) {
                            message = responseData.message || 'Credenciais inválidas.';
                        } else if (status === 422) {
                            // Tratamento de erros de validação (422)
                            const validationErrors = responseData.errors;
                            for (const field in validationErrors) {
                                const errorSpan = document.getElementById(`error-${field}`);
                                if (errorSpan) {
                                    errorSpan.textContent = validationErrors[field][0];
                                }
                            }
                            // Não exibe a mensagem global se houver erros de campo específicos
                            return;
                        } else if (status > 0) {
                            message = responseData.message || `Erro ${status} no servidor.`;
                        } else {
                            // Erro de rede ou CORS
                            message = 'Erro de conexão de rede. Verifique o servidor da API.';
                        }

                        errorMessageDiv.textContent = message;
                        errorMessageDiv.style.display = 'block';
                    });

                return false;
            });
        });
    </script>

    <script>
        console.log("Página de login carregada!");
    </script>
@endpush