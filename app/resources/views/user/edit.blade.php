@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('user.list') }}" class="btn btn-secondary mb-4">
                    &laquo; スタッフ一覧に戻る
                </a>
                <div class="card mb-4">
                    <div class="card-header">スタッフID: {{ $user->id  }} の情報を編集</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('user.update', $user->id) }}">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">氏名</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="username" class="col-md-4 col-form-label text-md-right">
                                    社員コード
                                </label>

                                <div class="col-md-6">
                                    <input id="username" type="text"
                                           class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                           name="username" value="{{ old('username', $user->username) }}" required>

                                    @if ($errors->has('username'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス（任意）</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="office" class="col-md-4 col-form-label text-md-right">所属</label>

                                <div class="col-md-6">
                                    @foreach($offices as $office)
                                        <div class="form-check form-check-inline">
                                            <input name="office[]" class="form-check-input" type="checkbox" id="officeCheckbox_{{ $office->id }}" value="{{ $office->id }}"
                                                {{ in_array($office->id, $user->offices->pluck('id')->toArray(), true) ? 'checked="checked"' : ''}}>
                                            <label class="form-check-label" for="officeCheckbox_{{ $office->id  }}">{{ $office->name }}</label>
                                        </div>
                                    @endforeach

                                    @error('office')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            @can('edit permissions')
                            <div class="form-group row">
                                <label for="role" class="col-md-4 col-form-label text-md-right">権限グループ</label>

                                <div class="col-md-6">
                                    @csrf
                                    @foreach($roles as $role)
                                        <div class="form-check form-check-inline">
                                            <input name="role[]" class="form-check-input" type="checkbox" id="roleCheckbox_{{ $role->id }}" value="{{ $role->id }}"
                                                {{ in_array($role->id, $user->roles->pluck('id')->toArray(), true) ? 'checked="checked"' : ''}}>
                                            <label class="form-check-label" for="roleCheckbox_{{ $role->id  }}">{{ $role->name }}</label>
                                        </div>
                                    @endforeach

                                    @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            @endcan

                            <div class="form-group row mb-0">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-primary">
                                        変更を保存
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">パスワード</div>
                    <div class="card-body text-center">
                        @if($user->hasRole(['maintainer', 'admin']) && !Auth::user()->hasRole('maintainer'))
                            <div class="alert alert-warning">
                                管理者およびメンテナンススタッフのパスワードは、自身またはメンテナンススタッフのみ変更可能です。
                            </div>
                        @else
                            <a href="{{ route('user.editPassword', $user->id) }}" class="btn btn-warning">
                                パスワード変更画面を開く
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
