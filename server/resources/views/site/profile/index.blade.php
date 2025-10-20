@extends('layouts.site')

@push('head')
@section('title', 'Meu Perfil')
<link rel="stylesheet" href="{{ asset('css/site/profile/style.css') }}" />
@endpush

@section('content')
    @include('site.profile.content')
@endsection

@push('scripts')

    {{-- Script de update --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("update-form");
            const apiUrl = form.getAttribute('api-url-update');

            form.addEventListener("submit", async function (e) {
                e.preventDefault();

                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                delete data._token;

                document.querySelectorAll('.alert').forEach(a => a.remove());

                const jwtToken = localStorage.getItem('jwt_token');

                if (!jwtToken) {
                    alert("Sessão expirada ou Token de autenticação não encontrado. Faça login novamente.");
                    window.location.href = "{{ route('login') }}";
                    return;
                }

                try {
                    const response = await fetch(apiUrl, {
                        method: "PATCH",
                        headers: {
                            "Authorization": `Bearer ${jwtToken}`,
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(data)
                    });

                    if (response.ok) {

                        if (response.status === 204) {
                            alert("Atualização realizada com sucesso!");
                        } else {
                            const result = await response.json();
                            alert("Atualização realizada com sucesso!");
                        }

                    } else {
                        let errorDetails = `Erro ${response.status}: Ocorreu uma falha ao atualizar.`;
                        let isValidationError = false;

                        try {
                            const result = await response.json();

                            if (result.errors) {
                                let ul = document.createElement('ul');
                                ul.classList.add('mb-0');
                                for (const key in result.errors) {
                                    result.errors[key].forEach(msg => {
                                        const li = document.createElement('li');
                                        li.textContent = msg;
                                        ul.appendChild(li);
                                    });
                                }

                                const div = document.createElement('div');
                                div.classList.add('alert', 'alert-danger');
                                div.innerHTML = `<h4 class="alert-heading">Ops! Erro de Validação:</h4>`;
                                div.appendChild(ul);
                                form.prepend(div);
                                isValidationError = true;

                            } else if (result.message) {
                                errorDetails = `Erro ${response.status}: ${result.message}`;
                            }

                        } catch (e) {
                            console.warn("Falha ao ler JSON de erro. O corpo da resposta pode estar vazio ou não ser JSON válido.", e);
                            if (response.status === 401) {
                                errorDetails = "Não autorizado. Sua sessão pode ter expirado.";
                            }
                        }

                        if (!isValidationError) {
                            alert(errorDetails);
                        }
                    }
                } catch (error) {
                    console.error("Erro na requisição:", error);
                    alert("Erro de conexão com o servidor.");
                }
            });
        });
    </script>

    {{-- Script de logout --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const formLogout = document.getElementById("logout-form");
            const apiUrl = formLogout.getAttribute('api-url-logout');

            formLogout.addEventListener("submit", async function (e) {
                e.preventDefault();
                document.querySelectorAll('.alert').forEach(a => a.remove());

                const jwtToken = localStorage.getItem('jwt_token');

                if (!jwtToken) {
                    alert("Você já está desconectado.");
                    localStorage.removeItem('jwt_token');
                    window.location.href = "{{ route('login') }}";
                    return;
                }

                try {
                    // Requisição de Logout (POST)
                    const response = await fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Authorization": `Bearer ${jwtToken}`,
                            "X-CSRF-TOKEN": document.querySelector('#logout-form input[name="_token"]').value,
                            "Accept": "application/json",
                        },
                    });

                    if (response.ok || response.status === 401) {
                        localStorage.removeItem('jwt_token');
                        let logoutMessage = "Sessão encerrada com sucesso.";
                        if (response.status === 200) {
                            try {
                                const result = await response.json();
                                logoutMessage = result.message || logoutMessage;
                            } catch (e) {
                            }
                        }

                        alert(logoutMessage);
                        window.location.href = "{{ route('login') }}";

                    } else {
                        // 3. Outros Erros (403, 500, etc.)
                        let errorMessage = `Erro ${response.status}: Não foi possível encerrar a sessão.`;
                        try {
                            const result = await response.json();
                            errorMessage = result.message || errorMessage;
                        } catch (e) {
                            // Falha ao ler JSON, usa a mensagem padrão
                        }
                        alert(errorMessage);
                    }
                } catch (error) {
                    // 4. Erro de Rede
                    console.error("Erro de rede no logout:", error);

                    // Em caso de erro de rede, presumimos sucesso e limpamos localmente
                    localStorage.removeItem('jwt_token');

                    alert("Erro de conexão com o servidor. A sessão local foi encerrada.");
                    window.location.href = "{{ route('login') }}";
                }
            });
        });
    </script>

    {{-- Script para deletar conta --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const formDelete = document.getElementById("delete-form");
            const apiUrlDelete = formDelete.getAttribute('api-url-delete');

            formDelete.addEventListener("submit", async function (e) {
                e.preventDefault();
                document.querySelectorAll('.alert').forEach(a => a.remove());

                if (!confirm("Tem certeza que deseja DELETAR sua conta permanentemente? Esta ação é irreversível.")) {
                    return;
                }

                const jwtToken = localStorage.getItem('jwt_token');

                if (!jwtToken) {
                    alert("Sessão expirada. Redirecionando para login.");
                    localStorage.removeItem('jwt_token');
                    window.location.href = "{{ route('login') }}";
                    return;
                }

                try {
                    const response = await fetch(apiUrlDelete, {
                        method: "DELETE",
                        headers: {
                            "Authorization": `Bearer ${jwtToken}`,
                            "X-CSRF-TOKEN": document.querySelector('#delete-form input[name="_token"]').value,
                            "Accept": "application/json",
                        },
                    });

                    if (response.ok || response.status === 204) {

                        localStorage.removeItem('jwt_token');

                        alert("Sua conta foi deletada com sucesso. Redirecionando.");
                        window.location.href = "{{ route('login') }}";

                    } else {
                        let errorMessage = `Erro ${response.status}: Falha ao deletar a conta.`;

                        try {
                            const result = await response.json();
                            errorMessage = result.message || errorMessage;
                        } catch (e) {
                            console.warn("Falha ao ler JSON de erro. O servidor retornou texto/vazio.", e);
                            if (response.status === 401) {
                                errorMessage = "Não autorizado. Sua sessão pode ter expirado.";
                            } else if (response.status === 403) {
                                errorMessage = "Acesso negado. Você não tem permissão para esta ação.";
                            }
                        }

                        alert(errorMessage);
                    }
                } catch (error) {
                    console.error("Erro de rede ao deletar conta:", error);
                    alert("Erro de conexão com o servidor. Tente novamente.");
                }
            });
            // -----------------------------------------------------------------
        });
    </script>

@endpush