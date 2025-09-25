<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    {{-- <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" /> --}}
    {{-- <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" /> --}}
    <title>@yield('title')</title>
</head>

<body class="font-sans box-border mx-auto min-h-screen bg-gradient-to-br from-[#0a2342] to-[#193a5e] text-white">
    @include('components.navbar')
    @yield('content')
    {{-- <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script> --}}
</body>

</html>
