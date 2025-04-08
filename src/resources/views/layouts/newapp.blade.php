
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COACHTECH')</title>
    <link rel="stylesheet" href="{{ asset('css/newapp.css') }}">
    @yield('styles')
</head>
<body>
    <!-- ヘッダー -->
    @include('layouts.newheader')

    <!-- メインコンテンツ -->
    <main class="main">
        @yield('content')
    </main>
        @yield('scripts')
</body>
</html>
