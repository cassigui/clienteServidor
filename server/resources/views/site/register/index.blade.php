@extends('layouts.site')

@push('head')
@section('title', 'Cadastro - Crie sua Conta')
@section('meta_description', 'Página de cadastro para novos usuários no sistema.')
<link rel="stylesheet" href="{{ asset('css/site/register/style.css') }}" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

@endpush

@section('content')
    @include('site.register.content')
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("register-form");
            const apiUrl = form.getAttribute('api-url-register');

            form.addEventListener("submit", async function (e) {
                e.preventDefault();

                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                document.querySelectorAll('.alert').forEach(a => a.remove());

                try {
                    const response = await fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                            "Accept": "application/json",
                        },
                        body: formData
                    });

                    if (response.ok) {
                        const result = await response.json();
                        alert("Cadastro realizado com sucesso!");
                        window.location.href = "{{ route('login') }}";
                    } else {
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
                            div.innerHTML = `<h4 class="alert-heading">Ops! Algo deu errado:</h4>`;
                            div.appendChild(ul);

                            form.prepend(div);
                        } else {
                            alert("Erro inesperado ao cadastrar. Tente novamente.");
                        }
                    }
                } catch (error) {
                    console.error("Erro na requisição:", error);
                    alert("Erro de conexão com o servidor.");
                }
            });
        });
    </script>

@endpush