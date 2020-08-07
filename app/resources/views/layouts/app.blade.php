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
        @php
            /** @var TYPE_NAME $fromIntranet */
            // navbarの背景色決め
            $navBarBgColor = '#fff';
            if (Auth::user()) {
                $navBarBgColor = '#68ceff3b';
            } else if ($fromIntranet) {
                $navBarBgColor = '#ffb8f63b';
            }
        @endphp
        <nav class="navbar navbar-expand-md navbar-light shadow-sm" style="background-color: {{ $navBarBgColor }};">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ $systemName }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="メニューを開閉">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">
                                    ダッシュボード
                                </a>
                            </li>
                        @else
                            @if ($fromIntranet)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('home') }}">
                                        {{ $intranetData->office->name }}
                                    </a>
                                </li>
                            @endif
                        @endauth
                            <li class="nav-item dropdown">
                                <a id="boardSelectorDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    ボード <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="boardSelectorDropdown">
                                    @guest
                                        @if ($fromIntranet)
                                            @foreach ($intranetData->office->boards as $board)
                                                <a class="dropdown-item" href="{{ route('board.show', $board->id)}}">
                                                    {{ $board->name }}
                                                </a>
                                            @endforeach
                                            <div class="dropdown-divider"></div>
                                        @endif
                                    @endauth
                                    <a class="dropdown-item" href="{{ route('board.index')}}">
                                        ボードリスト
                                    </a>
                                    <a class="dropdown-item" href="{{ route('board.create')}}">
                                        新規作成
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="taskDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    タスク <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="taskDropdown">
                                    <a class="dropdown-item" href="{{ route('task.index')}}">
                                        タスクリスト
                                    </a>
                                    <a class="dropdown-item" href="{{ route('task.create')}}">
                                        新規作成
                                    </a>
                                </div>
                            </li>
                        @canany(['edit users', 'register users'])
                            <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        管理メニュー
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">勤怠管理</a>
                                            <ul class="dropdown-menu">
                                                @can('edit users')
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('attendance.index') }}">
                                                            勤怠記録リスト
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('register users')
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('attendance.create') }}">
                                                            勤怠記録作成
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                        <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">スタッフ管理</a>
                                            <ul class="dropdown-menu">
                                                @can('edit users')
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('user.list') }}">
                                                            スタッフリスト
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('register users')
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('user.new') }}">
                                                            スタッフ登録
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                        @endcanany
                        @can('edit master settings')
                            <li class="nav-item dropdown">
                                <a id="masterSettingsDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    システム設定 <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="masterSettingsDropdown">
                                    <a class="dropdown-item" href="{{ route('settings')}}">
                                        全般設定
                                    </a>
                                    <a class="dropdown-item" href="{{ route('office.list')}}">
                                        部署設定
                                    </a>
                                    <a class="dropdown-item" href="{{ route('intranet.list')}}">
                                        イントラネット設定
                                    </a>
                                    <a class="dropdown-item" href="{{ route('role.list')}}">
                                        権限グループ設定
                                    </a>
                                </div>
                            </li>
                        @endcan
                    </ul>

                    <div class="navbar-nav ml-auto">
                        @guest
                            @if ($fromIntranet)
                                <operator-selector api-operator-list-url="{{ route('attendance.api.listMembers') }}" class="mr-md-2 mt-2 mt-md-0"></operator-selector>
                                <a class="nav-item btn btn-outline-success mr-md-2 mt-2 mt-md-0" href="{{ route('attendance.begin') }}">出勤登録</a>
                            @endif
                            <a class="nav-item btn btn-outline-primary mt-2 mt-md-0" href="{{ route('login') }}">ログイン</a>
                        @else
                            <div class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        プロフィールの編集
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        ログアウト
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

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
            @yield('content')
        </main>
    </div>
    @yield('jsData')
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('script')
</body>
</html>
