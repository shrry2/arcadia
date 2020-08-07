@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <form method="POST" action="{{ route('attendance.begin.store') }}" style="max-width: 300px">
            @csrf

            <h1 class="text-center my-4">出勤登録</h1>
            <h2 class="lead text-center">
                {{ $intranetData->office->name }}への出勤記録を残します<br>以下で個人認証を行ってください
            </h2>

            <div class="form-group row mt-4">
                <label for="identity">社員コードまたはメールアドレス</label>
                <input id="identity" type="text" class="form-control" name="identity" value="{{ old('identity') }}" required autocomplete="identity" autofocus>
            </div>

            <div class="form-group row">
                <label for="password">パスワード</label>
                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
            </div>

            @if ($errors->any())
                <div class="row alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="form-group row mb-3 justify-content-center">
                <button type="submit" class="btn btn-success btn-lg">
                    認証して出勤記録を登録
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
