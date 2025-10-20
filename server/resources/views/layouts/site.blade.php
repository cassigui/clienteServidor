<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Título Padrão')</title>
    <meta name="description" content="@yield('meta_description', 'Descrição Padrão.')">

    @stack('head')
</head>

<body>
    <header>

    </header>

    <main>
        @yield('content')
    </main>

    <footer>
    </footer>

    @stack('scripts')
</body>

</html>