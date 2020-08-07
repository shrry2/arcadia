@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-secondary mb-4">
                    &laquo; スタッフ情報編集に戻る
                </a>
                <div class="card">
                    <div class="card-header">{{ $user->name }}さんのパスワードを変更</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('user.updatePassword', $user->id) }}">
                            @csrf

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">新しいパスワード</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">パスワードの確認</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="alert alert-warning text-center">
                                <strong>ご注意ください！</strong><br>
                                パスワードは不可逆暗号化されるため、変更後に確認することはできません。<br>
                                変更前に必ず控えて、該当のスタッフに通知してください。
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        パスワードを変更
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
