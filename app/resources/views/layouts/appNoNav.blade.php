<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $systemName }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <main class="py-4">
            @if (session('status'))
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    </div>
                </div>
            @endif
            @if (session('message'))
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="alert alert-{{ session('message')['status'] }}" role="alert">
                            {{ session('message')['body'] }}
                        </div>
                    </div>
                </div>
            @endif
            <div class="container my-4">
                <div class="row justify-content-center">
                    <div class="container">
                        <div class="container">
                            <h1 class="text-center">{{ $systemName }}</h1>
                        </div>
                    </div>
                </div>
            </div>
            @yield('content')
        </main>
    </div>
    @yield('jsData')
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
