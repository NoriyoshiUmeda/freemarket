<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ 'COACHTECH' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body>
    @include('layouts.header') {{-- SVGロゴを含むヘッダーを共通化 --}}
    <main class="main">
        @yield('content')
    </main>
</body>
</html>
