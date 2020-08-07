@extends($fromIntranet ? 'layouts.app' : 'layouts.appNoNav')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <form method="POST" action="{{ route('login') }}" style="max-width: 300px">
            @csrf

            <h1 class="text-center my-4">ログイン</h1>

            @if ($fromIntranet)
                <div class="row alert alert-warning">
                    <strong>この画面は出勤登録画面ではありません</strong>
                    これは個人の権限に基づく手続きのための個人認証画面です。出勤登録をする場合は<a href="{{ route('attendance.begin') }}">こちら</a>をクリックして切り替えてください
                </div>
            @endif

            <div class="form-group row">
                <label for="identity">社員コードまたはメールアドレス</label>
                <input id="identity" type="text" class="form-control @error('identity') is-invalid @enderror" name="identity" value="{{ old('identity') }}" required autocomplete="identity" autofocus>
                @error('identity')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row">
                <label for="password">パスワード</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        ログイン状態を継続
                    </label>
                </div>
            </div>

            <div class="form-group row mb-3">
                <button type="submit" class="btn btn-primary">
                    ログイン
                </button>

                @if (Route::has('password.request'))
                    <a class="btn btn-link ml-auto" href="{{ route('password.request') }}">
                        パスワードの再発行
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
